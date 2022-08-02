#!/usr/bin/env bash
set -e
role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

if [ "$env" != "local" ]; then
    echo "Caching configuration..."
    (cd /var/www/html && composer dump-autoload && php artisan config:cache && php artisan route:cache && php artisan view:cache)
fi
if [ "$role" = "app" ]; then
    echo "Running the project..."
    (php artisan migrate && php artisan db:seed php /var/www/html/artisan serve --host=0.0.0.0)
elif [ "$role" = "horizon" ]; then
    echo "Running the queue..."
    php /var/www/html/artisan horizon
elif [ "$role" = "scheduler" ]; then
    while [ true ]
    do
      php /var/www/html/artisan schedule:run --verbose --no-interaction &
      sleep 60
    done
else
    echo "Could not match the container role \"$role\""
    exit 1
fi
