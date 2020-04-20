#!/usr/bin/env bash

echo "Installing composer dependencies"
composer install

echo "Creating database file.."
sqlite3 /usr/src/app/db/mock.db "SELECT 1;"
chown -R www-data:www-data /usr/src/app/db
echo "Successfully created database file!"

echo "Running migration.."
/usr/src/app/bin/console migrate/up --interactive 0

echo "Starting php-fpm"
php-fpm