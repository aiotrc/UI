#! /bin/bash

git pull
php app/console jahadPlatform:actions:load
php app/console cache:clear --env=dev
php app/console assets:install --env=dev
php app/console assets:install --symlink --env=dev
php app/console assetic:dump --env=dev
php app/console doctrine:cache:clear-metadata
php app/console doctrine:schema:update --force
