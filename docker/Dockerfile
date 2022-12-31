FROM fluxms/flux-eco-biotope:1.5.0 AS flux-eco-ilias-user-api
COPY --chown=www-data:www-data ./app /app
USER www-data
WORKDIR /app

ENTRYPOINT ["sh", "./docker_entrypoint.sh"]
