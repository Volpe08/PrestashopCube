FROM prestashop/prestashop:8-apache

COPY prestashop-app/docker-entrypoint-custom.sh /usr/local/bin/docker-entrypoint-custom.sh
COPY prestashop/custom-modules /opt/prestashop-custom-modules

RUN a2enmod rewrite \
    && chmod +x /usr/local/bin/docker-entrypoint-custom.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint-custom.sh"]
CMD ["/tmp/docker_run.sh"]