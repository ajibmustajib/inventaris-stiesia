#!/bin/bash

php artisan optimize:clear
php artisan filament:optimize-clear
php artisan filament:optimize
php artisan optimize
php artisan view:cache