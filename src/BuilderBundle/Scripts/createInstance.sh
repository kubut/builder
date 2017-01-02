#!/usr/bin/env bash
INSTANCES_LOCATION=$1
PROJECT_ID=$2
INSTANCE_ID=$3
GIT_URL=$4
BUILD_SCRIPT=$5
NODE_CLIENT=$6
SUCCESS=$7
WEBSOCKET_URL="ws://builder.vagrant:8080/instances"

cd $INSTANCES_LOCATION;

{
if [ ! -d "$PROJECT_ID" ]; then
    mkdir $PROJECT_ID;
fi

cd $PROJECT_ID

if [ -d "$INSTANCE_ID" ]; then
    second="3"
    first=${SUCCESS/5T4TU5/$second}
    nodejs $NODE_CLIENT $WEBSOCKET_URL ""$first""

fi
    {
        second="0"
        first=${SUCCESS/5T4TU5/$second}
        nodejs  $NODE_CLIENT $WEBSOCKET_URL ""$first""
        git clone $GIT_URL $INSTANCE_ID;
        mkdir $INSTANCE_ID
        } || {
        second="3"
        first=${SUCCESS/5T4TU5/$second}
        nodejs $NODE_CLIENT $WEBSOCKET_URL ""$first""
    }
    {
        cd $INSTANCE_ID;
        second="1"
        first=${SUCCESS/5T4TU5/$second}
        nodejs  $NODE_CLIENT $WEBSOCKET_URL ""$first""
        sudo sh $BUILD_SCRIPT
        second="2"
        first=${SUCCESS/5T4TU5/$second}
        nodejs  $NODE_CLIENT $WEBSOCKET_URL ""$first""
    } || {
    second="3"
    first=${SUCCESS/5T4TU5/$second}
    nodejs $NODE_CLIENT $WEBSOCKET_URL ""$first""
}

} || {
    second="3"
    first=${SUCCESS/5T4TU5/$second}
        nodejs $NODE_CLIENT $WEBSOCKET_URL ""$first""
}