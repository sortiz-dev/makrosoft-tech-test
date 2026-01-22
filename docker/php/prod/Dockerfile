# 1) Vendor
FROM php:8.4-cli-alpine AS vendor

WORKDIR /app

RUN apk --no-cache add curl git bash $PHPIZE_DEPS

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions \
 && install-php-extensions http

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock symfony.lock* ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --classmap-authoritative \
    --no-scripts

# 2) Runtime
FROM php:8.4-fpm-alpine AS runtime

RUN apk --no-cache add curl git wget bash dpkg icu-data-full

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions \
    opcache iconv soap \
    zip intl fileinfo \
    pdo redis mysqli pdo_mysql \
    gd \
    http

RUN { \
  echo "memory_limit=512M"; \
  echo "upload_max_filesize=64M"; \
  echo "post_max_size=64M"; \
  echo "opcache.enable=1"; \
  echo "opcache.enable_cli=1"; \
  echo "opcache.validate_timestamps=0"; \
  echo "opcache.max_accelerated_files=20000"; \
  echo "opcache.memory_consumption=256"; \
  echo "opcache.interned_strings_buffer=16"; \
} > /usr/local/etc/php/conf.d/99-app.ini

WORKDIR /var/www

COPY --from=vendor /app/vendor /var/www/vendor

COPY . /var/www

RUN mkdir -p var/cache var/log && \
    chown -R www-data:www-data /var/www/var

ENV APP_ENV=prod \
    APP_DEBUG=0

RUN php -v && \
    if [ -f bin/console ]; then \
      php bin/console cache:clear --no-warmup || true; \
      php bin/console cache:warmup || true; \
    fi

USER www-data

EXPOSE 8000

HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
  CMD php-fpm -t || exit 1
