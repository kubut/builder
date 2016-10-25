#!/usr/bin/env bash
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
         vn=""; for (i=0; i<indent; i++) {vn=(vn)(vname[i])("_")}
         printf("%s%s%s=\"%s\"\n", "'$prefix'",vn, $2, $3);
      }
   }'
}

eval $(parse_yaml /vagrant/vagrant.conf/vagrant.yml "config_");

apt-get update

# ustawia haslo dla root mysql
debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'

# ustawienie danych dla phpmyadmina
debconf-set-selections <<< 'phpmyadmin phpmyadmin/dbconfig-install boolean true'
debconf-set-selections <<< 'phpmyadmin phpmyadmin/app-password-confirm password applicationpass'
debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/admin-pass password root'
debconf-set-selections <<< 'phpmyadmin phpmyadmin/mysql/app-pass password applicationpass'
debconf-set-selections <<< 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2'


apt-get install -y --force-yes apache2 mysql-server-5.6 php5 php-pear php5-curl php5-intl php5-mcrypt php5-mysql php5-xdebug php5-imagick phpmyadmin mc git phpunit curl npm php5-sqlite
#merge pdf reports
sudo apt-get install -y --force-yes pdftk
if ! [ -L /var/www/page ]; then
  rm -rf /var/www/page
  ln -fs /vagrant /var/www/page
fi

cp /vagrant/vagrant.conf/page.conf /etc/apache2/sites-available/page.conf
cp /vagrant/vagrant.conf/page_ssl.conf /etc/apache2/sites-available/page_ssl.conf
a2ensite page.conf
a2ensite page_ssl.conf
a2enmod ssl
a2enmod rewrite

sed -i 's/;date.timezone =/date.timezone = Europe\/Warsaw/g' /etc/php5/apache2/php.ini
sed -i 's/;date.timezone =/date.timezone = Europe\/Warsaw/g' /etc/php5/cli/php.ini

sed -i 's/127.0.0.1/127.0.0.1 builder.vagrant/g' /etc/hosts

service apache2 restart

mysql -h 127.0.0.1 -u root -proot  -t -e "CREATE DATABASE IF NOT EXISTS $config_database_name; GRANT ALL ON $config_database_name.* to '$config_database_user'@'127.0.0.1' identified by '$config_database_password'; GRANT ALL ON $config_database_name.* to '$config_database_user'@'$config_database_ip' identified by '$config_database_password';"

less /vagrant/vagrant.conf/xdebug.ini >> /etc/php5/mods-available/xdebug.ini

ln -s /var/www/page /home/vagrant/www

#npm install -g grunt-cli bower
#ln -s /usr/bin/nodejs /usr/bin/node

# variable to change logs and cahce folder on developers machines
echo "VM_DEV_MACHINE=1" >> /etc/environment
