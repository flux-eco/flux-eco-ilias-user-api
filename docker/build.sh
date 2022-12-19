#!/bin/bash
docker build  ../ -f Dockerfile --target flux-ilias-user-import-api -t fluxms/flux-ilias-user-import-api:1.0.0
docker build  ../ -f Dockerfile --target flux-ilias-user-import-api -t fluxms/flux-ilias-user-import-api:latest