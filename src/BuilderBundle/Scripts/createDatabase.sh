#!/bin/bash
parse_yaml() {
   local prefix=$2
   local s='[[:space:]]*' w='[a-zA-Z0-9_]*' fs=$(echo @|tr @ '\034')
   sed -ne "s|^\($s\)\($w\)$s:$s\"\(.*\)\"$s\$|\1$fs\2$fs\3|p" \
        -e "s|^\($s\)\($w\)$s:$s\(.*\)$s\$|\1$fs\2$fs\3|p"  $1 |
   awk -F$fs '{
      indent = length($1)/2;
      vname[indent] = $2;
      for (i in vname) {if (i > indent) {delete vname[i]}}
      if (length($3) > 0) {
         printf("%s%s=\"%s\"\n", "'$prefix'", $2, $3);
      }
   }'
}

eval $(parse_yaml ~/www/app/config/parameters.yml "config_")

DB_USER=$config_database_user
DB_PASS=$config_database_password
WEBSOCKET_URL="ws://"$config_portal_url":"$config_socket_port"/"$config_socket_databases
MESSAGE_SUCCESS=$3
MESSAGE_ERROR=$4

DATABASE_NAME=$1
SQL_FILE=$2
{
    echo "Creating Database" >&2
    mysql -u $DB_USER -p$DB_PASS -e "create database $DATABASE_NAME"
    echo "Uploading database file" >&2
    pv $SQL_FILE | mysql -u$DB_USER -p$DB_PASS $DATABASE_NAME
    echo "Sending response"
    nodejs src/BuilderBundle/Scripts/ServersClient.js $WEBSOCKET_URL ""$MESSAGE_SUCCESS""
} || {
    echo "Error. Sending info." >&2
    nodejs src/BuilderBundle/Scripts/ServersClient.js $WEBSOCKET_URL "'"$MESSAGE_ERROR"'"
}