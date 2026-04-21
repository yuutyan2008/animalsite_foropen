FROM php:8.2-fpm

# NginxとPHP拡張に必要なライブラリをインストール
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libicu-dev \
    nginx \
    git \
    unzip \
    curl \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql intl \
    && rm -rf /var/lib/apt/lists/*

# Node.jsをインストール（Viteのビルドに必要）
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Composerをインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Laravelのソースコードをコピー
COPY src/ .

# PHPの依存パッケージをインストール（本番用・最適化）
RUN composer install --no-dev --optimize-autoloader

# JSの依存パッケージをインストールしてViteでビルド
RUN npm install && npm run build

# .gitignore が消えていた場合に再生成（フォルダをgitで保持するために必要）
RUN mkdir -p storage/app/public storage/app/private \
           storage/framework/cache/data \
           storage/framework/sessions \
           storage/framework/testing \
           storage/framework/views \
           storage/logs \
           bootstrap/cache \
    && for f in \
         storage/app/.gitignore \
         storage/app/public/.gitignore \
         storage/app/private/.gitignore \
         storage/framework/.gitignore \
         storage/framework/cache/.gitignore \
         storage/framework/cache/data/.gitignore \
         storage/framework/sessions/.gitignore \
         storage/framework/testing/.gitignore \
         storage/framework/views/.gitignore \
         storage/logs/.gitignore \
         bootstrap/cache/.gitignore; do \
       [ -f "$f" ] || printf '*\n!.gitignore\n' > "$f"; \
    done

# ストレージとキャッシュのパーミッションを設定
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Render用Nginx設定をコピー（ポート10000）
COPY docker/nginx/render.conf /etc/nginx/conf.d/default.conf

# 起動スクリプトをコピーして実行権限を付与
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# Renderが使うポートを宣言
EXPOSE 10000

# PHP-FPMとNginxを同時に起動
CMD ["/start.sh"]
