#!/usr/bin/env sh

set -e

libs="`dirname "$0"`/../libs"

installLibrary() {
    (mkdir -p "$libs/$1" && cd "$libs/$1" && wget -O - "$2" | tar -xz --strip-components=1)
}

installLibrary simple-xlsx https://github.com/shuchkin/simplexlsx/archive/refs/tags/1.0.18.tar.gz
installLibrary flux-ilias-rest-api-client https://github.com/fluxfw/flux-ilias-rest-api-client/releases/download/v2022-12-12-1/flux-ilias-rest-api-client-v2022-12-12-1-build.tar.gz