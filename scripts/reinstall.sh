#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cd ${DIR}/../docroot/sites/default

drush si --uri=dsk.dd:8083 -y

drush cim sync -y

drush uli
