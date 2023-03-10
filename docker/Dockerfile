FROM fluxms/flux-eco-biotope:v2022-01-08-5 AS flux-eco-ilias-user-orbital

# |-------------------------------------------------------------------------- \
# | author martin@fluxlabs.ch
# |-------------------------------------------------------------------------- \

# |-------------------------------------------------------------------------- \
# | General Environment Variables
# |-------------------------------------------------------------------------- \
ENV FLUX_ECO_HTTP_SYNAPSE_AUTOLOAD_FILE_PATH=/opt/flux-eco-ilias-user-orbital/app/autoload.php
ENV FLUX_ECO_HTTP_SYNAPSE_HTTP_API_FQCN=FluxEco\\IliasUserOrbital\\Adapters\\Api\\HttpApi

ENV FLUX_ECO_DISPATCHER_SYNAPSE_FROM_HOST=flux-eco-ilias-user-orbital

ENV FLUX_ECO_ILIAS_USER_ORBITAL_DISPATCHER_CONFIG_ARCHIVE_PATH=NULL

COPY --chown=www-data:www-data ./app /opt/flux-eco-ilias-user-orbital/app
RUN chmod a+rx /opt/flux-eco-ilias-user-orbital/app/bin/flux-eco-ilias-user-orbital.install-libraries

USER root
WORKDIR /
RUN /opt/flux-eco-ilias-user-orbital/app/bin/flux-eco-ilias-user-orbital.install-libraries

USER www-data
WORKDIR /opt/flux-eco-ilias-user-orbital/app

ENTRYPOINT ["sh", "./docker_entrypoint.sh"]
