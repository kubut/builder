#!/usr/bin/env bash

gitPath="https://github.com/kubut/builder.git"
quiet=false
flag=''
npmFlag=''

printError() {
    echo "$(tput setab 1)$(tput bold)--ERROR:$(tput sgr0) ${1}"
}

printWarning() {
    echo "$(tput setab 3)$(tput bold)--WARNING:$(tput sgr0) ${1}"
}

printInfo() {
    echo "$(tput setab 4)$(tput bold)--INFO:$(tput sgr0) ${1}"
}

printQuestion() {
    echo "$(tput setab 5)$(tput bold)--QUESTION:$(tput sgr0) ${1}"
}

cloneRepo() {
    printInfo "Cloning repository..."
    installIfNeeded git

    git clone ${gitPath} builder ${flag}
}

checkPHPVersion() {
    PHPVersion=$(php -v|grep --only-matching --perl-regexp "5\.\\d+\.\\d+");
    currentVersion=${PHPVersion:0:2};
    minimumRequiredVersion=$1;
    if [ $(echo " $currentVersion >= $minimumRequiredVersion" | bc) -eq 1 ]; then
        printInfo "PHP Version is valid ...";
    else
        printError "PHP Version NOT valid for ${currentVersion} ...";
        exit 1
    fi
}

isProgramInstalled() {
    local return_=1
    type $1 >/dev/null 2>&1 || { local return_=0; }
    echo "$return_"
}

requireSuccess() {
    if [[ $? > 0 ]]; then
        printError "Command failed! Installation aborted!"
        exit 2
    fi
}

installAsRoot() {
    eval sudo ${pm} ${flag} install ${1} -y
    if [[ $? > 0 ]]; then
        printError "Installation failed - Builder may not working correctly"
    fi
}

installIfNeeded() {
    if [ $(isProgramInstalled ${1}) -eq 0 ]; then
        printWarning "$1 not detected... Installing..."
        installAsRoot ${1}
    fi
}

setSeLinux() {
    printInfo "Checking SElinux"
    if [ $(isProgramInstalled sestatus) -eq 1 ]; then
        printQuestion "SELinux detected... Should I try to set permissions for you (y/N)?"
        read agree
        if [ ${agree} == "y" ]; then
            printInfo "Try to configure permissions for SELinux..."
            sudo chcon -R -t httpd_sys_script_rw_t app/logs
            sudo chcon -R -t httpd_sys_script_rw_t app/cache
            sudo setsebool -P httpd_can_network_connect=1
            printInfo "Done"
        fi
    fi
}

setApache() {
    if [ -d "/etc/apache2/sites-enabled" ]; then
        serverPath="/etc/apache2/sites-enabled"
    elif [ -d "/etc/httpd/sites-enabled" ]; then
        serverPath="/etc/httpd/sites-enabled"
    else
        printError "Directory with virtual hosts configuration doesn't found (I searched in /etc/apache2/sites-enabled and /etc/httpd/sites-enabled)"
        printQuestion "Specify absolute path to your virtual hosts directory"
        read serverPath
    fi

    printQuestion "Specify domain for Builder (without http://www.)"
    read domain
    path=$(pwd)"/web"

    sudo touch ${serverPath}/builder.conf
    sudo bash -c "cat >> ${serverPath}/builder.conf" << EOF
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

    printInfo "Restarting server..."
    sudo service ${serverService} restart
    requireSuccess
}

setDatabase() {
    if [ $(isProgramInstalled ${1}) -eq 0 ]; then
        printError "MySQL is required!"
        exit 3
    fi

    printQuestion "I need username of mysql with privileges to create database and users"
    read dbRootUser
    printQuestion "I need password too"
    read -s dbRootPass
    printQuestion "Specify database name for Builder"
    read dbName
    dbUser="builder_sql"
    printQuestion "Specify new password for mysql user for Builder (username: ${dbUser})"
    read -s dbPassword

    mysql -h 127.0.0.1 -u ${dbRootUser} -p${dbRootPass}  -t -e "CREATE DATABASE IF NOT EXISTS ${dbName}; GRANT ALL ON ${dbName}.* to '${dbUser}'@'127.0.0.1' identified by '${dbPassword}'; GRANT ALL ON ${dbName}.* to '${dbUser}'@'localhost' identified by '${dbPassword}';"
}

installBackend() {
    checkPHPVersion 5

    printInfo "Downloading composer.phar..."
    curl -sS https://getcomposer.org/installer | php

    printInfo "Setting permissions for Symfony cache"
    HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
    installIfNeeded acl

    sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
    sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
    if [[ $? > 0 ]]; then
        printError "ACL doesn't work properly and I can't set correct permissions for Symfony files. Try to enable ACL and set permissions manually. For more info see Symfony documentation"
    fi

    setSeLinux
    requireSuccess

    if [ ${pm} == "apt-get" ]; then
        installIfNeeded php5-mysql
    else
        installIfNeeded php-mysql
    fi

    setDatabase
    requireSuccess

    sudo /bin/dd if=/dev/zero of=/var/swap.1 bs=1M count=1024
    sudo /sbin/mkswap /var/swap.1
    sudo /sbin/swapon /var/swap.1
    dbname=${dbName} dbpass=${dbPassword} dbuser=${dbUser} bash  -c 'php composer.phar install'

    setApache
    requireSuccess

    php app/console doctrine:schema:create
    php app/console doctrine:fixtures:load --fixtures=src/BuilderBundle/DataFixtures/Prod/
}

installFront() {
    installIfNeeded npm

    printInfo "Checking NodeJS..."
    if [ $(isProgramInstalled nodejs) -eq 0 ] && [ $(isProgramInstalled node) -eq 0 ]; then
        printWarning "NodeJS not detected... Installing..."

        if [ ${pm} == "apt-get" ]; then
            curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
        else
            curl --silent --location https://rpm.nodesource.com/setup_6.x | bash -
        fi

        installAsRoot nodejs
        sudo ln -s `which nodejs` /usr/bin/node
        sudo npm update -g ${npmFlag} npm@4.0.1
    else
        sudo ln -s `which nodejs` /usr/bin/node
        printQuestion "Recommended version of NodeJS is 6.9.x. Should I update it (y/N)?"
        read agree
        if [ ${agree} == "y" ]; then
            sudo npm install -g ${npmFlag} n
            sudo n 6.9.1
        fi

        printQuestion "Recommended version of NPM is 4.0.x. Should I update it (y/N)?"
        read agree
        if [ ${agree} == "y" ]; then
            sudo npm update -g ${npmFlag} npm@4.0.1
        fi
    fi

    printInfo "Installing NPM dependencies..."
    npm ${npmFlag} i
    sudo npm install -g ${npmFlag} gulp
    sudo npm install ${npmFlag} gulp

    printInfo "Building front..."
    gulp build
}

########################################################################################33
########################################################################################33
########################################################################################33
if [ "$(whoami)" != "root" ]; then
    printError "Please run as root"
    exit 4
fi

if [ "$1" = '-q' ]; then
    printInfo "Running in quiet mode"
    quiet=true
    flag="-q"
    npmFlag="--silent"
fi

if [ $(isProgramInstalled apt-get) -eq 1 ]; then
    pm="apt-get"
    printInfo "Setting apt-get as package manager"
elif [ $(isProgramInstalled yum) -eq 1 ]; then
    pm="yum"
    printInfo "Setting yum as package manager"
else
    printError "This script supports only systems with apt-get or yum"
    exit 5
fi

if [ $(isProgramInstalled apache2) -eq 1 ]; then
    serverService="apache2"
    printInfo "Setting apache2 as server service"
elif [ $(isProgramInstalled httpd) -eq 1 ]; then
    serverService="httpd"
    printInfo "Setting httpd as server service"
else
    printError "This script supports only systems with httpd or apache"
    exit 6
fi

cloneRepo
cd builder

installBackend
installFront