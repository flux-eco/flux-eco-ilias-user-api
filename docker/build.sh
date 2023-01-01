#!/bin/bash
sh ../app/bin/install-libraries.sh
docker build  ../ -f Dockerfile --target flux-eco-ilias-user-orbital -t fluxms/flux-eco-ilias-user-orbital:v2022-12-30-1
docker build  ../ -f Dockerfile --target flux-eco-ilias-user-orbital -t fluxms/flux-eco-ilias-user-orbital:latest