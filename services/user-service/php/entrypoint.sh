#!/bin/sh

echo "Setting Laravel permissions..."

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "Starting Supervisor..."
exec /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf


