#!/bin/sh
set -e

function printBanner {
  echo "flux-ilias-user-import-api Copyright (C) 2021 https://medi.ch";
  echo "This program comes with ABSOLUTELY NO WARRANTY;";
  echo "This is free software, and you are welcome to redistribute it under certain conditions;";
}

function startServer {
  php /app/bin/server.php
}

printBanner
startServer