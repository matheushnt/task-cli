FROM php:8.4.16-cli-bookworm

RUN groupadd -g 1000 php && useradd -u 1000 -g php -ms /bin/bash php

WORKDIR /home/php/app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libpq-dev \
    libonig-dev \
    && docker-php-ext-install \
    intl \
    zip \
    mbstring \
    pdo \
    pdo_pgsql \
    opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.2.30 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_HOME=/home/php/.composer PATH=/home/php/.composer/vendor/bin:$PATH

RUN mkdir -p /home/php/.composer /home/php/app && chown -R php:php /home/php

COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

USER php

CMD ["start.sh"]
