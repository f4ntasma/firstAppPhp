FROM php:8.2-fpm

# Install Caddy
RUN apt-get update && apt-get install -y \
    curl \
    debian-keyring \
    debian-archive-keyring \
    apt-transport-https \
    && curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' \
    | gpg --dearmor -o /usr/share/keyrings/caddy-stable-archive-keyring.gpg \
    && curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' \
    | tee /etc/apt/sources.list.d/caddy-stable.list \
    && apt-get update && apt-get install -y caddy \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by the app
RUN docker-php-ext-install curl

# Copy application files
COPY Caddyfile /etc/caddy/Caddyfile
COPY public/ /app/public/

EXPOSE 80

# Start PHP-FPM and Caddy together
CMD php-fpm -D && caddy run --config /etc/caddy/Caddyfile --adapter caddyfile
