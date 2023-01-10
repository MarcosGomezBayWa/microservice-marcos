#!/bin/bash

exec php vendor/bin/phing init -propertyfile /etc/secrets/phing.ini && php create_documentation.php
exit $?
