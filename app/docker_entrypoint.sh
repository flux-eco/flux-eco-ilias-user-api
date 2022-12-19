#!/bin/sh
set -e

function printBanner {
  echo "flux-ilias-user-import-api Copyright (C) 2021 https://medi.ch";
  echo "";
  echo "This program is free software: you can redistribute it and/or modify"
  echo "it under the terms of the GNU General Public License as published"
  echo "by the Free Software Foundation, either version 3 of the License,"
  echo "or (at your option) any later version.";
  echo "";
  echo "This program is distributed in the hope that it will be useful,";
  echo "but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY";
  echo "or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.";
}

function startServer {
  php /app/server.php
}

printBanner
startServer