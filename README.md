# Album API

## Getting Started
1. Run `docker compose build --pull --no-cache` to build fresh images
2. Run `docker compose up` (the logs will be displayed in the current shell)
3. If running for the first time, follow steps below to setup database:
    1. Execute `docker ps` and get container ID for album-rest-php
    2. SSH into container: `docker exec -it <container_id> /bin/sh`
    3. `bin/console doctrine:database:create`
        - Can ignore if database already exists
    4. `bin/console doctrine:migrations:migrate`
4. Copy YAML file in docs/openapi to https://editor.swagger.io/ to explore API.
5. Import POSTMAN collection at docs/API.postman_collection.json for full 
   examples.
