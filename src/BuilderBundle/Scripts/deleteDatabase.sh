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

eval $(parse_yaml ~/public_html/builder/app/config/parameters.yml "config_")

DB_USER=$config_database_user
DB_PASS=$config_database_password
WEBSOCKET_URL="ws://builder.vagrant:8080/databases"
WEBSOCKET_URL="ws://"$config_portal_url":"$config_socket_port"/"$config_socket_databases

DATABASE_NAME=$1
MESSAGE_SUCCESS=$2
MESSAGE_ERROR=$3

{
    echo "Deleteing Database" >&2
    echo src/BuilderBundle/Scripts/ServersClient.js $WEBSOCKET_URL ""$MESSAGE_SUCCESS"" >&2
    mysql -u $DB_USER -p$DB_PASS -e "DROP DATABASE $DATABASE_NAME"
    nodejs src/BuilderBundle/Scripts/ServersClient.js $WEBSOCKET_URL ""$MESSAGE_SUCCESS""
    echo "Deleteing Database" >&2
} || {
    echo "Error. Sending info." >&2
    nodejs src/BuilderBundle/Scripts/ServersClient.js $WEBSOCKET_URL "'"$MESSAGE_ERROR"'"
}