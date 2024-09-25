FROM dontdrinkandroot/php:8-3-alpine-symfony-prod

# Change Workdir to Project
WORKDIR /var/www/

# User as www-data for following commands
USER www-data

# Copy source
COPY --chown=www-data:www-data ./ /var/www/

ENV COMPOSER_CACHE_DIR=composer-cache

RUN set -xe \
    && echo "APP_ENV=prod" >> .env.local \
    && echo "APP_DEBUG=0" >> .env.local \
    && DATABASE_URL=sqlite:///%kernel.project_dir%/var/default.db \
        composer install -a --no-progress --no-dev \
    && composer dump-autoload --no-dev --classmap-authoritative \
    && rm -rf \
        .dockerignore \
        ./docker/ \
    && composer clear-cache \
    && ls -la /var/www/

# Run Apache
USER root
#CMD su -s /bin/sh www-data -c "composer dump-env prod && bin/console doctrine:migrations:migrate --all-or-nothing -n" && httpd -D FOREGROUND
CMD su -s /bin/sh www-data -c "composer dump-env prod && httpd -D FOREGROUND"
EXPOSE 80
