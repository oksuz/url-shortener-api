#!/bin/bash

# wait database for migrate/create db schema
wait-for-it mysql:3306

php /opt/app/bin/console doctrine:database:create --if-not-exists
php /opt/app/bin/console doctrine:migrations:migrate --no-interaction

php-fpm