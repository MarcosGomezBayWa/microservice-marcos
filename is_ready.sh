#!/bin/bash

FILE=/var/www/html/is_ready

if [ ! -f "$FILE" ]; then
  exec php vendor/bin/phing init -propertyfile /etc/secrets/phing.ini && php create_documentation.php
  exit $?
fi

exit 0
