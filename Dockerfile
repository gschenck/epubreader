FROM nextcloud:27

ENV NEXTCLOUD_UPDATE 1
ENV NEXTCLOUD_ADMIN_USER epubreader
ENV NEXTCLOUD_ADMIN_PASSWORD epubreader
ENV NEXTCLOUD_INIT_HTACCESS 1
ENV SQLITE_DATABASE epubreader

RUN curl -sSLo /usr/local/bin/composer https://getcomposer.org/download/latest-stable/composer.phar && \
    chmod +x /usr/local/bin/composer && \
    rm -f /usr/local/etc/php/conf.d/opcache-recommended.ini && \
    /entrypoint.sh true

USER www-data

COPY --chown=www-data:www-data . apps/epubreader
RUN cd apps/epubreader && composer install && cd - && \
    php occ app:enable epubreader && \
    php occ config:system:set debug --value=true

USER root
