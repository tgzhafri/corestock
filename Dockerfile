# syntax = docker/dockerfile:experimental

# Default to PHP 8.1, but we attempt to match
# the PHP version from the user (wherever `flyctl launch` is run)
# Valid version values are PHP 7.4+
ARG PHP_VERSION=8.1
ARG NODE_VERSION=14
FROM serversideup/php:${PHP_VERSION}-fpm-nginx as base

# PHP_VERSION needs to be repeated here
# See https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
ARG PHP_VERSION

LABEL fly_launch_runtime="laravel"

RUN apt-get update && apt-get install -y \
    git curl zip unzip rsync ca-certificates vim htop cron \
    php${PHP_VERSION}-pgsql php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-swoole php${PHP_VERSION}-xml php${PHP_VERSION}-mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
# copy application code, skipping files based on .dockerignore
COPY . /var/www/html

RUN composer install --optimize-autoloader --no-dev \
    && mkdir -p storage/logs \
    && php artisan optimize:clear \
    && chown -R webuser:webgroup /var/www/html \
    && sed -i 's/protected \$proxies/protected \$proxies = "*"/g' app/Http/Middleware/TrustProxies.php \
    && echo "MAILTO=\"\"\n* * * * * webuser /usr/bin/php /var/www/html/artisan schedule:run" > /etc/cron.d/laravel \
    && rm -rf /etc/cont-init.d/* \
    && cp .fly/nginx-websockets.conf /etc/nginx/conf.d/websockets.conf \
    && cp .fly/entrypoint.sh /entrypoint \
    && chmod +x /entrypoint

# If we're using Octane...
RUN if grep -Fq "laravel/octane" /var/www/html/composer.json; then \
    rm -rf /etc/services.d/php-fpm; \
    if grep -Fq "spiral/roadrunner" /var/www/html/composer.json; then \
    mv .fly/octane-rr /etc/services.d/octane; \
    if [ -f ./vendor/bin/rr ]; then ./vendor/bin/rr get-binary; fi; \
    rm -f .rr.yaml; \
    else \
    mv .fly/octane-swoole /etc/services.d/octane; \
    fi; \
    cp .fly/nginx-default-swoole /etc/nginx/sites-available/default; \
    else \
    cp .fly/nginx-default /etc/nginx/sites-available/default; \
    fi

# Multi-stage build: Build static assets
# This allows us to not include Node within the final container
FROM node:${NODE_VERSION} as node_modules_go_brrr

RUN mkdir /app

RUN mkdir -p  /app
WORKDIR /app
COPY . .
COPY --from=base /var/www/html/vendor /app/vendor

# Use yarn or npm depending on what type of
# lock file we might find. Defaults to
# NPM if no lock file is found.
# Note: We run "production" for Mix and "build" for Vite
RUN if [ -f "vite.config.js" ]; then \
    ASSET_CMD="build"; \
    else \
    ASSET_CMD="production"; \
    fi; \
    if [ -f "yarn.lock" ]; then \
    yarn install --frozen-lockfile; \
    yarn $ASSET_CMD; \
    elif [ -f "package-lock.json" ]; then \
    npm ci --no-audit; \
    npm run $ASSET_CMD; \
    else \
    npm install; \
    npm run $ASSET_CMD; \
    fi;

# install puppeteer
FROM node:14

# Install latest chrome dev package and fonts to support major charsets (Chinese, Japanese, Arabic, Hebrew, Thai and a few others)
# Note: this installs the necessary libs to make the bundled version of Chromium that Puppeteer
# installs, work.
RUN apt-get update \
    && apt-get install -y wget gnupg \
    && wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add - \
    && sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list' \
    && apt-get update \
    && apt-get install -y google-chrome-stable fonts-ipafont-gothic fonts-wqy-zenhei fonts-thai-tlwg fonts-khmeros fonts-kacst fonts-freefont-ttf libxss1 \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/* \
    && groupadd -r pptruser && useradd -rm -g pptruser -G audio,video pptruser

USER pptruser

WORKDIR /home/pptruser

COPY puppeteer-latest.tgz puppeteer-core-latest.tgz ./

# Install puppeteer and puppeteer-core into /home/pptruser/node_modules.
RUN npm i ./puppeteer-core-latest.tgz ./puppeteer-latest.tgz \
    && rm ./puppeteer-core-latest.tgz ./puppeteer-latest.tgz \
    && (node -e "require('child_process').execSync(require('puppeteer').executablePath() + ' --credits', {stdio: 'inherit'})" > THIRD_PARTY_NOTICES)

CMD ["google-chrome-stable", "docker build -t puppeteer-chrome-linux"]


# From our base container created above, we
# create our final image, adding in static
# assets that we generated above
FROM base

# Packages like Laravel Nova may have added assets to the public directory
# or maybe some custom assets were added manually! Either way, we merge
# in the assets we generated above rather than overwrite them
COPY --from=node_modules_go_brrr /app/public /var/www/html/public-npm
RUN rsync -ar /var/www/html/public-npm/ /var/www/html/public/ \
    && rm -rf /var/www/html/public-npm \
    && chown -R webuser:webgroup /var/www/html/public

ENTRYPOINT ["/entrypoint"]

