#FROM alpine:3.11
# FROM php:7.3-fpm-alpine
FROM php:8.0.5-fpm-alpine
LABEL Description="Lightweight container with Nginx 1.16 & PHP-FPM based on Alpine Linux."

ARG NGINX_PORT=8080
ARG PHP_FPM_PORT=9090
ARG APP_NAME=buildcon
ARG OB_WEB_TOOLS_S3=https://${APP_NAME}-web-tools.s3-eu-west-1.amazonaws.com/
# ARG NEWRELIC_FILE=newrelic-php5-9.10.0.262-linux-musl
# ARG NEWRELIC_FILE_EXT=.tar.gz

# Install packages
RUN apk --no-cache add php8 php8-fpm php8-mysqli php8-json php8-openssl php8-curl php8-iconv php8-tokenizer \
    php8-zlib php8-xml php8-phar php8-intl php8-dom php8-xmlreader php8-ctype php8-session php8-fileinfo \
    php8-mbstring php8-gd php-zip php-simplexml php8-pdo php8-pdo_mysql php8-pecl-redis nginx nginx-mod-http-geoip supervisor curl vim && \
    docker-php-ext-install gd && \
    docker-php-ext-install zip &&\
    docker-php-ext-install xml &&\
    apk del build-dependencies #&&\
    #rm -rf /var/cache/apk/*

#RUN pecl install xmlwriter
#RUN docker-php-ext-install gd gd2 sockets pdo_mysql opcache php-zip php-gd2
#
#RUN docker-php-ext-enable opcache && \
#    pecl install event-2.5.3 && \
#    docker-php-ext-enable --ini-name zz-event.ini event

# NewRelic Install and Config
# RUN curl -L -o /tmp/${NEWRELIC_FILE}${NEWRELIC_FILE_EXT} ${OB_WEB_TOOLS_S3}${NEWRELIC_FILE}${NEWRELIC_FILE_EXT}
# RUN tar -xvzpf /tmp/${NEWRELIC_FILE}${NEWRELIC_FILE_EXT} -C /tmp
# RUN rm -f /tmp/${NEWRELIC_FILE}${NEWRELIC_FILE_EXT}
# RUN	NR_INSTALL_USE_CP_NOT_LN=1 NR_INSTALL_SILENT=1 /tmp/newrelic-php5-*/newrelic-install install

# Setup document root
RUN mkdir -p /var/www/${APP_NAME} /run/php-fpm /run/nginx /var/lib/php/sessions /var/lib/php/wsdlcache /var/lib/php/opcache

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nginx.nginx /var/www/${APP_NAME} && \
  chown -R nginx.nginx /run/php-fpm && \
  chown -R nginx.nginx /var/lib/php && \
  chown -R nginx.nginx /run/nginx && \
  chown -R nginx.nginx /var/lib/nginx && \
  chown -R nginx.nginx /var/log/nginx

# Make the document root a volume
VOLUME /var/www/${APP_NAME}

# Configure PHP-FPM
COPY docker/config/fpm-pool.conf /etc/php8/php-fpm.d/www.conf
COPY docker/config/php.ini /etc/php8/conf.d/custom.ini
COPY docker/config/newrelic.ini /etc/php8/conf.d/newrelic.ini

# Configure supervisord
COPY docker/config/supervisord.conf /etc/supervisord.conf

# Configure nginx
COPY docker/config/nginx.conf /etc/nginx/nginx.conf
COPY docker/config/${APP_NAME}.conf /etc/nginx/conf.d/${APP_NAME}.conf
COPY docker/config/php-fpm.conf /etc/nginx/conf.d/php-fpm.conf
# COPY docker/config/nginx-default.conf /etc/nginx/conf.d/default.conf

# Add application
WORKDIR /var/www/${APP_NAME}
COPY --chown=nginx . /var/www/${APP_NAME}/
COPY --chown=nginx docker/config/laravel.env /var/www/${APP_NAME}/.env

# Expose the port nginx is reachable on
EXPOSE ${NGINX_PORT}

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

# # Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:${NGINX_PORT}/check.txt
