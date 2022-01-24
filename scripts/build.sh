#!/bin/bash

docker-compose down
git pull
docker-compose up -d --build

docker exec -it storage-server-php-1 composer install
docker exec -it storage-server-php-1 bin/console cache:warmup