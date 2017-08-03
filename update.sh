#! /bin/bash
clear
echo ''
echo ''
echo ' ==========================================='
echo ' Welcome to JahadPlatform Platform Update Tool'
echo ' ==========================================='
echo ''
echo ''
echo ' @author:	jahadPlatform team'
echo ' @link  :	www.jahadPlatform.ir'
echo ' @server_uptime:'; uptime
echo '--------------------------------------------------------------'
echo '<< NOTE >>'
echo 'PLEASE RUN THIS TOOL USING SUPER USER PRIVILEGE (ROOT)'
echo ''
read -r -p "Merge production branch with master? [y/N] " response
    case $response in [yY][eE][sS]|[yY])
	     sudo -u root git checkout prod
	     sudo -u root git merge master
	     sudo -u php-fpm php app/console jahadPlatform:actions:load
        echo 'jahadPlatform updated to master version.'
        read -r -p "Update schema for database? [y/N] " response
            case $response in [yY][eE][sS]|[yY])
                  sudo -u php-fpm php app/console doctrine:cache:clear-metadata
                  sudo -u php-fpm php app/console doctrine:cache:clear-result
                  php app/console doctrine:schema:update --force
                echo 'schema:update is done'
                echo 'press any key to continue...'
                ;;
                *)
                echo 'skipped schema update.'
                echo 'press any key to continue...'
            ;;
        esac
        read
        read -r -p "Clear Application cache? [y/N] " response
            case $response in [yY][eE][sS]|[yY])
                 chown -R php-fpm app/cache
                 chmod -R 777 app/cache/
                 sudo -u php-fpm php app/console cache:clear --env=dev --no-debug
                 sudo -u php-fpm php app/console cache:clear --env=prod --no-debug
		 sudo rm -rf app/cache/prod
            echo 'cache cleared!'
            echo 'press any key to continue...'
            ;;
            *)
            echo 'skipped cache clear.'
            echo 'press any key to continue...'
        ;;
        esac

        read
        read -r -p "Install Assets? [y/N] " response
        case $response in [yY][eE][sS]|[yY])
              sudo -u php-fpm php app/console assets:install
              sudo -u php-fpm php app/console assets:install --symlink
              sudo -u php-fpm php app/console assetic:dump --env=dev
              sudo -u php-fpm php app/console assetic:dump --env=prod
            echo 'assets installed!'
            echo 'bye !'
            ;;
            *)
            echo 'Installing assets has been skipped'
            echo 'press any key to exit...'
        ;;
        esac
    ;;
    *)
    echo 'skipped updating server.'
;;
esac
