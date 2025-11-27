FROM alpine:3.16

# Add testing mirror for php8 mirror
# RUN echo "https://dl-cdn.alpinelinux.org/alpine/v3.16/community/" >> /etc/apk/repositories

# Install packages
RUN apk --update add --no-cache \
      tzdata \
      nginx \
      curl \
      supervisor \
      gd \
      freetype \
      libpng \
      libjpeg-turbo \
      freetype-dev \
      libpng-dev \
      nodejs \
      git \
      php84 \
      php84-dom \
      php84-fpm \
      php84-mbstring \
      php84-opcache \
      php84-pdo \
      php84-pdo_mysql \
      php84-pdo_pgsql \
      php84-pdo_sqlite \
      php84-xml \
      php84-phar \
      php84-openssl \
      php84-curl \
      php84-ctype \
      php84-session \
      php84-gd \
      php84-tokenizer \
      php84-bcmath \
      php84-pecl-redis \
      php84-fileinfo \
      nodejs \
      npm \
    && rm -rf /var/cache/apk/* && \
    addgroup -g 1000 -S app && \
    adduser -u 1000 -S app -G app && \
    mkdir /var/log/websockets && \
    touch /var/log/php8/stdout.log && \
    touch /var/log/php8/stderr.log && \
    touch /var/log/nginx/stdout.log && \
    touch /var/log/nginx/stderr.log && \
    touch /var/log/websockets/stdout.log && \
    touch /var/log/websockets/stderr.log && \
    chown -R 1000:0 /var/log/*

# Set timzone
RUN cp /usr/share/zoneinfo/Europe/Vienna /etc/localtime && \
    echo "Europe/Vienna" > /etc/timezone

# Configure nginx
COPY build/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY build/fpm-pool.conf /etc/php8/php-fpm.d/www.conf
COPY build/php.ini /etc/php8/conf.d/zzz_custom.ini

# Configure cron
COPY build/crontab /etc/cron/crontab

# Configure supervisord
COPY build/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R app.app /run && \
  chown -R app.app /var/lib/nginx && \
  chown -R app.app /var/log/nginx && \
  chown -R app.app /var/log/websockets && \
  chown -R app.app /var/www

# Setup document root
RUN mkdir -p /var/www/public
RUN crontab /etc/cron/crontab

# Add application
WORKDIR /var/www

#ADD . /var/www
COPY --chown=app:app . /var/www
#RUN chown -R app:app /var/www

# Switch to use a non-root user from here on
USER 1000

RUN php8 artisan storage:link && \
    php8 artisan cache:clear && \
    rm public/js/app.js.map

USER 0

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping