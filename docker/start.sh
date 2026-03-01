#!/bin/bash

# Migrations
php artisan migrate --force

# Cache configuration 
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
apache2-foreground
