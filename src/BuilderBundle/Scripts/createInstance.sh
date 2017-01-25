#!/usr/bin/env bash
INSTANCES_LOCATION=$1
PROJECT_ID=$2
INSTANCE_ID=$3
GIT_URL=$4
BUILD_SCRIPT=$5
NODE_CLIENT=$6
INSTANCE_NAME=$7
DATABASE_NAME=$8
SUCCESS=$9
WEBSOCKET_URL="ws://builder.vagrant:8080/instances"

sendError() {
    second="3"
    first=${SUCCESS/5T4TU5/$second}
    nodejs $NODE_CLIENT $WEBSOCKET_URL ""$first""
}

cd $INSTANCES_LOCATION;

{
if [ ! -d "$PROJECT_ID" ]; then
    mkdir $PROJECT_ID;
fi

cd $PROJECT_ID

if [ -d "$INSTANCE_ID" ]; then
    sendError
    exit 0

fi
    {
        second="0"
        first=${SUCCESS/5T4TU5/$second}
        nodejs  $NODE_CLIENT $WEBSOCKET_URL ""$first""
        git clone $GIT_URL $INSTANCE_ID;
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
    } || {
        sendError
        exit 5
    }
    {
        cd $INSTANCE_ID;
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
{
  if [ -d "/etc/apache2/sites-enabled" ]; then
        serverPath="/etc/apache2/sites-enabled"
        serverService="apache2"
    elif [ -d "/etc/httpd/sites-enabled" ]; then
        serverPath="/etc/httpd/sites-enabled"
        serverService="httpd"
    fi
    path=$(pwd)"/web"
    domain=${INSTANCE_NAME}

    sudo touch ${serverPath}/${INSTANCE_ID}_builder.conf
    sudo bash -c "cat >> ${serverPath}/${INSTANCE_ID}_builder.conf" << EOF
<VirtualHost *:80>
    ServerName www.${domain}
    ServerAlias ${domain}
    DocumentRoot "${path}"
    <Directory "${path}">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF

    sudo -- sh -c -e "echo '
    127.0.0.1  ${domain}' >> /etc/hosts"

    sudo service ${serverService} restart
} || {
    sendError
    exit 3
}

} || {
    sendError
    exit 4
}