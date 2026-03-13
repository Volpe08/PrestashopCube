FROM prestashop/prestashop:8-apache

COPY docker-entrypoint-custom.sh /usr/local/bin/docker-entrypoint-custom.sh
RUN chmod +x /usr/local/bin/docker-entrypoint-custom.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint-custom.sh"]
CMD ["/tmp/docker_run.sh"]
