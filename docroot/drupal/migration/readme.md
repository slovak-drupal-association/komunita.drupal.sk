cd migrate
sh d8_reset.sh
cd ..
drush migrate-manifest --legacy-db-url=mysql://root:hesloheslo@localhost/drupal_sk_old manifest_dsk.yml
