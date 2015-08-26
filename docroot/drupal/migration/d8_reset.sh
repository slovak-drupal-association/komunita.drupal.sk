#!/bin/sh

# Removes an existing Drupal 8 install, then install again.
# Requires Drush 7 - https://github.com/drush-ops/drush
#
# Run this script from the docroot of a Drupal 8 checkout.
# Installs to mysql://localhost/drupal, user 1 user/pass is admin/admin.

cd ..
if [ ! -e ./core/core.services.yml ]
then
  echo "You need to run this script from the root of a Drupal 8 install."
  exit
fi

# MySQL
echo "Dropping and recreating 'drupal' database.\n"
mysql -uroot -phesloheslo -e "drop database drupal_sk"
mysql -uroot -phesloheslo -e "create database drupal_sk"


echo "Removing files created during drupal install. You will be asked for your password, for sudo.\n"
sudo rm -f ./sites/default/settings.php
sudo rm -rf ./sites/simpletest
sudo cp ./sites/default/default.settings.php ./sites/default/settings.php
sudo chmod 777 ./sites/default/settings.php
sudo rm -rf ./sites/default/files
sudo chmod 777 ./sites/default

# Now install a Drupal.
drush si --account-name=admin --account-pass=hesloheslo --db-url=mysql://root:hesloheslo@localhost/drupal_sk -yv
drush en migrate migrate_drupal migrate_tools migrate_plus -y

sudo chmod 777 ./sites/default/files

cd migration
