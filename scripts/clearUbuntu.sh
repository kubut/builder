#!/usr/bin/env bash

echo "mysql-server mysql-server/root_password password root" | sudo debconf-set-selections
echo "mysql-server mysql-server/root_password_again password root" | sudo debconf-set-selections

apt-get install -y apache2 php5 mysql-server-5.6

sudo sed -i.old "s/export APACHE_RUN_USER=.*/export APACHE_RUN_USER=vagrant/g" /etc/apache2/envvars
sudo sed -i.old "s/export APACHE_RUN_GROUP=.*/export APACHE_RUN_GROUP=vagrant/g" /etc/apache2/envvars

sudo service apache2 restart