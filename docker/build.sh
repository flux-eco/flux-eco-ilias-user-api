#!/bin/bash
sh ../app/bin/install-libraries.sh
docker build  ../ -f Dockerfile --target flux-eco-ilias-user-api -t fluxms/flux-eco-ilias-user-api:v2022-12-30-1
docker build  ../ -f Dockerfile --target flux-eco-ilias-user-api -t fluxms/flux-eco-ilias-user-api:latest