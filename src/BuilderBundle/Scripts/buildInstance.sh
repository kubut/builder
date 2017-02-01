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
INSTANCES_LOCATION=$1
PROJECT_ID=$2
INSTANCE_ID=$3
BRANCH=$4
BUILD_SCRIPT=$5
NODE_CLIENT=$6
DATABASE_NAME=$7
SUCCESS=$8
WEBSOCKET_URL="ws://"$config_portal_url":"$config_socket_port"/"$config_socket_instances

sendError() {
    second="3"
    first=${SUCCESS/5T4TU5/$second}
    nodejs $NODE_CLIENT $WEBSOCKET_URL ""$first""
}

cd $INSTANCES_LOCATION
cd $PROJECT_ID
cd $INSTANCE_ID


{
    second="0"
    first=${SUCCESS/5T4TU5/$second}
    nodejs $NODE_CLIENT $WEBSOCKET_URL ""$first""
    git fetch
    git pull
    git checkout origin/${BRANCH}

    } || {
    sendError
    exit 1
}
{
     if [ ! -f $INSTANCES_LOCATION'config/'$PROJECT_ID'/parameters.yml' ]; then
        sudo sed "s/DATABASE_NAME/"$DATABASE_NAME"/" $INSTANCES_LOCATION'config/'$PROJECT_ID'/database.php' > $INSTANCES_LOCATION$PROJECT_ID'/'$INSTANCE_ID'/app/config/database.php'
     else
        sudo sed "s/DATABASE_NAME/"$DATABASE_NAME"/" $INSTANCES_LOCATION'config/'$PROJECT_ID'/parameters.yml' > $INSTANCES_LOCATION$PROJECT_ID'/'$INSTANCE_ID'/app/config/parameters.yml'
     fi
         echo "done3\n";

} || {
    sendError
    exit 5
}
{
    second="1"
    first=${SUCCESS/5T4TU5/$second}
    nodejs  $NODE_CLIENT $WEBSOCKET_URL ""$first""
    if [ "$BUILD_SCRIPT" != "/" ]; then
        sudo sh $BUILD_SCRIPT
    fi
    second="2"
    first=${SUCCESS/5T4TU5/$second}
    nodejs  $NODE_CLIENT $WEBSOCKET_URL ""$first""
} || {
    sendError
    exit 2
}