#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cd ${DIR}/../docroot/sites/default

drush si --locale=sk --site-name="Drupal Slovensko" --site-mail="info@drupal.sk" -y
