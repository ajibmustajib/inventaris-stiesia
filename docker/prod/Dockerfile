# Stage 1: Build assets using Node.js
FROM node:23.9.0-alpine AS node-base

WORKDIR /app

COPY . .

RUN yarn install \
    && yarn build \
    && rm -rf node_modules

# Stage 2: PHP Application Setup
FROM webdevops/php-nginx:8.3-alpine

ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_DISMOD=exif,ffi,gettext,ldap,mysqli,imap,soap,sockets,sysvmsg,sysvsem,sysvshm,shmop,xsl,apcu,vips,yaml,mongodb,amqp
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

# Copy built assets from node-base stage
COPY --from=node-base /app .

# Configure Git to treat /app as a safe directory
RUN git config --global --add safe.directory /app

# Install PHP dependencies
RUN composer install -o --no-dev

# Set correct permissions for specific directories
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 755 /app/storage /app/bootstrap/cache

# Copy Supervisor configuration
COPY /docker/prod/supervisor.d/artisan.conf /opt/docker/etc/supervisor.d/artisan.conf


EXPOSE 80