#!/bin/bash
# see https://carstenwindler.de/php/enable-xdebug-on-demand-in-your-local-docker-environment/

if [ "$#" -ne 1 ]; then
    SCRIPT_PATH=`basename "$0"`
    echo "Usage: $SCRIPT_PATH off|debug,profile,trace"
    exit 1;
fi

MODES=$1;

SCRIPT_DIR=$(dirname $0)
ENV_PATH="$SCRIPT_DIR/../../../.docker.env"

# shellcheck disable=SC2046
export $(grep -v '^#' "$ENV_PATH" | xargs)

SERVICE_ID=${PROJECT_NAME}${DOCKER_PHP_CONTAINER_NAME_SUFFIX}

echo "Copying default XDEBUG ini"
docker exec -i $SERVICE_ID bash -c \
	'cp /usr/local/etc/xdebug/xdebug-default.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini'

if [[ $MODES == *"profile"* ]]; then
    echo "Appending profile ini"
    docker exec -i $SERVICE_ID bash -c \
    	'cat /usr/local/etc/xdebug/xdebug-profile.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini'
fi

if [[ $MODES == *"debug"* ]]; then
    echo "Appending debug ini"
    docker exec -i $SERVICE_ID bash -c \
    	'cat /usr/local/etc/xdebug/xdebug-debug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini'

	echo "Setting Client Host to: $DOCKER_XDEBUG_HOST"
	docker exec -e DOCKER_XDEBUG_HOST=$DOCKER_XDEBUG_HOST -i $SERVICE_ID bash -c \
    	'sed -i -e "s/xdebug.client_host = localhost/xdebug.client_host = "${DOCKER_XDEBUG_HOST}"/g" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini'

    echo "Setting Client Port to: $DOCKER_XDEBUG_PORT"
    docker exec -e DOCKER_XDEBUG_PORT=$DOCKER_XDEBUG_PORT -i $SERVICE_ID bash -c \
    	'sed -i -e "s/xdebug.client_port = 9003/xdebug.client_port = "${DOCKER_XDEBUG_PORT}"/g" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini'

    echo "Setting IDE Key to: $DOCKER_XDEBUG_IDE_KEY"
    docker exec -e DOCKER_XDEBUG_IDE_KEY=$DOCKER_XDEBUG_IDE_KEY -i $SERVICE_ID bash -c \
    	'sed -i -e "s/xdebug.idekey = docker/xdebug.idekey = "${DOCKER_XDEBUG_IDE_KEY}"/g" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini'
fi

if [[ $MODES == *"trace"* ]]; then
    echo "Appending trace ini"
    docker exec -i $SERVICE_ID bash -c \
    	'cat /usr/local/etc/xdebug/xdebug-trace.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini'
fi

if [[ "off" == $MODES || -z $MODES ]]; then
    echo "Disabling XDEBUG";
    docker exec -i $SERVICE_ID bash -c \
    	'cp /usr/local/etc/xdebug/xdebug-off.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini'
else
    echo "Setting XDEBUG mode: $MODES"
    docker exec -e MODES=$MODES -i $SERVICE_ID bash -c \
    	'echo "xdebug.mode = "$MODES"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini'
fi;

docker restart $SERVICE_ID

docker exec -i $SERVICE_ID bash -c '$(php -m | grep -q Xdebug) && echo "Status: Xdebug ENABLED" || echo "Status: Xdebug DISABLED"'