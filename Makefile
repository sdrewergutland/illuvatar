include .docker.env

.PHONY: init start docker-up docker-build docker-config docker-down docker-stop attach init_directories composer-install php-cs-fixer php-cs-fixer-check phpstan phpmd psalm phpunit phpunit-coverage phpunit-unit phpunit-functional phpunit-application debug-on debug-off profiler-on profiler-off debug

init: docker-build start init_directories composer-install

start: docker-stop docker-up

docker-up:
	docker-compose --env-file .docker.env up -d

docker-build:
	docker-compose --env-file .docker.env build

docker-config:
	docker-compose --env-file .docker.env config

docker-down:
	docker-compose --env-file .docker.env down

docker-stop:
	docker-compose --env-file .docker.env stop

attach:
	docker exec -it --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} bash

init_directories:
	mkdir -p ./var/cache ./var/log ./var/sessions ./var/xdebug
	sudo chmod -R 777 ./var .dev/tools/phpstan/.phpstan-cache

composer-install:
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} composer install
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} composer install --working-dir=./.dev/tools/php-cs-fixer

php-cs-fixer:
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} ./.dev/tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --path-mode=override --config=php-cs-fixer.php

php-cs-fixer-check:
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} ./.dev/tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --path-mode=override --config=php-cs-fixer.php --dry-run

phpstan:
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} ./vendor/bin/phpstan analyse -c phpstan.neon src tests

phpmd:
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} ./vendor/bin/phpmd src github phpmd-ruleset.xml

psalm:
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} ./vendor/bin/psalm

phpunit: phpunit-unit phpunit-functional phpunit-application

phpunit-coverage:
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} ./vendor/bin/phpunit --coverage-html ./var/coverage

phpunit-unit:
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} ./vendor/bin/phpunit --testsuite Unit

phpunit-functional:
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} ./vendor/bin/phpunit --testsuite Functional

phpunit-application:
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} ./vendor/bin/phpunit --testsuite Application

debug-on:
	bash ./.dev/docker/scripts/xdebug.sh debug

debug-off:
	bash ./.dev/docker/scripts/xdebug.sh off

profiler-on:
	bash ./.dev/docker/scripts/xdebug.sh profile

profiler-off: debug-off

trace-on:
	bash ./.dev/docker/scripts/xdebug.sh trace

trace-off: debug-off

cache-clear:
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} ./bin/console cache:clear

cache-warmup:
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} ./bin/console cache:warmup

restore-filemode:
	git status --porcelain | grep -E "^(M| M)" | awk '{print $$2}' | xargs sudo chmod a+w

console-migrations:
	docker exec --env-file .docker.env ${DOCKER_PHP_CONTAINER_NAME} ./bin/console doctrine:migrations:diff --no-interaction

pre-commit: php-cs-fixer phpstan psalm phpunit