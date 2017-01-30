#!/usr/bin/env bash
INSTANCES_LOCATION=$1
PROJECT_ID=$2
INSTANCE_ID=$3
BRANCH=$4
BUILD_SCRIPT=$5
NODE_CLIENT=$6
DATABASE_NAME=$7
SUCCESS=$8
WEBSOCKET_URL="ws://builder.vagrant:8080/instances"

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