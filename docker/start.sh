#!/bin/bash

# 必要なディレクトリを作成（存在しない場合のみ作成）
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# マイグレーションを実行
php artisan migrate --force

# PHP-FPMをバックグラウンドで起動
php-fpm -D

# Nginxをフォアグラウンドで起動（これがメインプロセス）
nginx -g "daemon off;"
