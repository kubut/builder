#!/usr/bin/env bash

gitPath="https://github.com/kubut/builder.git"

function printError {
    echo -e "\e[7m\e[1m\e[91mERROR:\e[0m ${1}"
}

function printWarning {
    echo -e "\e[7m\e[1m\e[33mWARNING:\e[0m ${1}"
}

function printInfo {
    echo -e "\e[7m\e[1m\e[34mINFO:\e[0m ${1}"
}

function printQuestion {
    echo -e "\e[7m\e[45mQUESTION:\e[0m ${1}"
}

function checkPHPVersion {
    PHPVersion=$(php -v|grep --only-matching --perl-regexp "5\.\\d+\.\\d+");
    currentVersion=${PHPVersion::0-2};
    minimumRequiredVersion=$1;
    if [ $(echo " $currentVersion >= $minimumRequiredVersion" | bc) -eq 1 ]; then
        printInfo "PHP Version is valid ...";
    else
        printError "PHP Version NOT valid for ${currentVersion} ...";
        exit 1
    fi
}

function isProgramInstalled {
    local return_=1
    type $1 >/dev/null 2>&1 || { local return_=0; }
    echo "$return_"
}

function requireSuccess {
    if [[ $? > 0 ]]; then
        printError "Command failed! Installation aborted!"
        exit 2
    fi
}

function installAsRoot {
    eval sudo ${pm} install ${1} -y
    if [[ $? > 0 ]]; then
        printError "Installation failed - Builder may not working correctly"
    fi
}

function installIfNeeded {
    if [ $(isProgramInstalled ${1}) == 0 ]; then
        printWarning "$1 not detected... Installing..."
        installAsRoot ${1}
    fi
}

function setSeLinux {
    printInfo "Checking SElinux"
    if [ $(isProgramInstalled sestatus) == 1 ]; then
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

function setApache {
    if [ -d "/etc/apache2/sites-enabled" ]; then
        serverPath="/etc/apache2/sites-enabled"
    elif [-d "/etc/httpd/sites-enabled"]; then
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

function setDatabase {
    if [ $(isProgramInstalled ${1}) == 0 ]; then
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

function installBackend {
    checkPHPVersion 5

    printInfo "Downloading composer.phar..."
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"

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

    installIfNeeded php5-mysql

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

function installFront {
    installIfNeeded npm

    printInfo "Checking NodeJS..."
    if [ $(isProgramInstalled nodejs) == 0 ] && [ $(isProgramInstalled node) == 0 ]; then
        printWarning "NodeJS not detected... Installing..."

        if [ ${pm} == "apt-get" ]; then
            curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
        else
            curl --silent --location https://rpm.nodesource.com/setup_6.x | bash -
        fi

        installAsRoot nodejs
        sudo ln -s `which nodejs` /usr/bin/node
        sudo npm update -g npm@4.0.1
    else
        sudo ln -s `which nodejs` /usr/bin/node
        printQuestion "Recommended version of NodeJS is 6.9.x. Should I update it (y/N)?"
        read agree
        if [ ${agree} == "y" ]; then
            sudo npm install -g n
            sudo n 6.9.1
        fi

        printQuestion "Recommended version of NPM is 4.0.x. Should I update it (y/N)?"
        read agree
        if [ ${agree} == "y" ]; then
            sudo npm update -g npm@4.0.1
        fi
    fi

    printInfo "Installing NPM dependencies..."
    npm i
    printInfo "Done"
}

########################################################################################33
########################################################################################33
########################################################################################33
#Install as sudo
if [ $(isProgramInstalled apt-get) == 1 ]; then
    pm="apt-get"
    printInfo "Setting apt-get as package manager"
elif [ $(isProgramInstalled yum) == 1 ]; then
    pm="yum"
    printInfo "Setting yum as package manager"
else
    printError "This script supports only systems with apt-get or yum"
    exit 1
fi

if [ $(isProgramInstalled apache2) == 1 ]; then
    serverService="apache2"
    printInfo "Setting apache2 as server service"
elif [ $(isProgramInstalled httpd) == 1 ]; then
    serverService="httpd"
    printInfo "Setting httpd as server service"
else
    printError "This script supports only systems with httpd or apache"
    exit 1
fi

printInfo "Cloning repository..."
installIfNeeded git
git clone ${gitPath} builder
cd builder

installBackend
installFront