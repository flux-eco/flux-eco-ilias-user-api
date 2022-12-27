
#!/bin/bash
docker build . --pull --target ilias -t flux-ilias-user-import-example/ilias:7.16-1
docker build . --pull --target nginx -t flux-ilias-user-import-example/nginx:7.16-1