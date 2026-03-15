FROM dontdrinkandroot/php:symfony-prod-8.5

# Healthcheck to ensure the web server is responding
HEALTHCHECK --start-period=60s CMD curl http://localhost/_health --silent --show-error --fail --output /dev/null || exit 1

ENV APP_ENV=prod
ENV APP_DEBUG=0

# Copy the application source code (excluding Docker-related files)
COPY --chown=www-data:www-data . ./

RUN set -eux; \
    composer install -a --no-progress --no-dev; \
    composer clear-cache;

# RUN caddy-add-messenger-consume async,scheduler_default
