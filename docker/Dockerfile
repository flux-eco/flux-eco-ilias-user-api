FROM fluxms/flux-eco-biotope:1.4.0 AS flux-ilias-user-import-api
COPY --chown=www-data:www-data ./app /app
USER www-data
WORKDIR /app

ENV FLUX_ILIAS_REST_API_CLIENT_URL=http://nginx
ENV FLUX_ILIAS_REST_API_CLIENT_USER=api

ENTRYPOINT ["sh", "./docker_entrypoint.sh"]
