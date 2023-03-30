setup-certs:
	#openssl genrsa -des3 -out ./.dev/nginx/certs/root-ca.key 2048
	#openssl req -x509 -new -nodes -key ./.dev/nginx/certs/root-ca.key -sha256 -days 1024 -out ./.dev/nginx/certs/root-ca.pem
	#sudo openssl req -new -sha256 -nodes -out app.csr -newkey rsa:2048 -keyout ./.dev/nginx/certs/app.key
	#sudo openssl x509 -req -in ./.dev/nginx/certs/app.csr -CA ./.dev/nginx/certs/root-ca.pem -CAkey ./.dev/nginx/certs/root-ca.key -CAcreateserial -out ./.dev/nginx/certs/app.crt -days 730 -sha256 -extfile ./.dev/nginx/certs/app.ext

start: dstop dup s_directories

dup:
	docker-compose --env-file .docker.env up -d

dconfig:
	docker-compose --env-file .docker.env config

ddown:
	docker-compose --env-file .docker.env down

dstop:
	docker-compose --env-file .docker.env stop

#add a make command that will make sure all directories for symfony exist and are writable

s_directories:
	mkdir -p ./var/cache ./var/log ./var/sessions
	sudo chmod -R 777 ./var

php-cs-fixer:
		docker exec --env-file .docker.env illuvatar-php-1 ./tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --path-mode=override --config=php-cs-fixer.php

php-cs-fixer-check:
		docker exec --env-file .docker.env illuvatar-php-1 ./tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --path-mode=override --config=php-cs-fixer.php --dry-run

phpstan:
		docker exec --env-file .docker.env illuvatar-php-1 ./vendor/bin/phpstan analyse -c phpstan.neon src tests

phpmd:
		docker exec --env-file .docker.env illuvatar-php-1 ./vendor/bin/phpmd src github phpmd-ruleset.xml