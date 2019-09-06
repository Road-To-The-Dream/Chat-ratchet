#!/bin/bash

php artisan key:generate
php artisan migrate:refresh --seed
npm install
npm run dev
php artisan serve
