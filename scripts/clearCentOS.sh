#!/bin/bash
wget http://repo.mysql.com/mysql-community-release-el6-5.noarch.rpm
sudo rpm -Uvh mysql-community-release-el6-5.noarch.rpm

sudo yum install -y httpd php mysql-server bc nano
sudo yum install -y php-xml

sudo service httpd restart
sudo systemctl enable mysqld
sudo service mysqld start
sudo mkdir /etc/httpd/sites-enabled
sudo echo "IncludeOptional sites-enabled/*.conf" >> /etc/httpd/conf/httpd.conf
sudo sed -i.old '/;date.timezone*/cdate.timezone=\"Europe/Warsaw\"' /etc/php.ini
sudo service httpd restart
mysqladmin -u root password 'root'
