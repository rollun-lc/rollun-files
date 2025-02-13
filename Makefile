init: docker-down-clear docker-pull docker-build docker-up composer-install wait-db run-migrations
up: docker-up
down: docker-down
restart: docker-down docker-up
test: composer-test

docker-up:
	docker compose up -d --build

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build

composer-install:
	docker compose exec php-fpm composer install -vv

composer-da:
	docker compose exec php-fpm composer dumpautoload

composer-test:
	docker compose exec php-fpm composer test

openapi-generate-server:
	docker compose run --rm php-openapi-generator php vendor/bin/openapi-generator generate:server

openapi-generate-client:
	docker compose run --rm php-openapi-generator php vendor/bin/openapi-generator generate:client

wait-db:
	docker-compose exec php-fpm wait-for-it mysql:3306 -t 30

run-migrations:
	docker-compose exec php-fpm php bin/migrations.php