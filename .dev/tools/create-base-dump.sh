#!/bin/bash

set -e

SCRIPT_DIR=$(dirname $0)
ENV_PATH="$SCRIPT_DIR/../../.env.test"

source "${ENV_PATH}"

mysql -h"${DATABASE_HOST}" -u"${DATABASE_ROOT_USER}" -p"${DATABASE_ROOT_PASSWORD}" -e "DROP DATABASE IF EXISTS \`${DATABASE_NAME}\`; CREATE DATABASE IF NOT EXISTS \`${DATABASE_NAME}\`;"
mysql -h"${DATABASE_HOST}" -u"${DATABASE_ROOT_USER}" -p"${DATABASE_ROOT_PASSWORD}" -e "ALTER DATABASE \`${DATABASE_NAME}\`  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
bin/console doctrine:migrations:migrate --no-interaction --env=test
bin/console messenger:setup-transports --no-interaction --env=test

mysqldump -h"${DATABASE_HOST}" -u"${DATABASE_ROOT_USER}" -p"${DATABASE_ROOT_PASSWORD}" --no-data --add-drop-table --single-transaction ${DATABASE_NAME} >tests/Resources/Sql/base.default.structure.sql
mysqldump -h"${DATABASE_HOST}" -u"${DATABASE_ROOT_USER}" -p"${DATABASE_ROOT_PASSWORD}" --no-create-info --compact ${DATABASE_NAME} > tests/Resources/Sql/base.default.data.sql
echo 'SET FOREIGN_KEY_CHECKS=0;START TRANSACTION;' | cat - tests/Resources/Sql/base.default.data.sql > temp && mv temp tests/Resources/Sql/base.default.data.sql
echo 'COMMIT;' >> tests/Resources/Sql/base.default.data.sql

mysqldump -h"${DATABASE_HOST}" -u"${DATABASE_ROOT_USER}" -p"${DATABASE_ROOT_PASSWORD}" --add-drop-table --no-data ${DATABASE_NAME} $(mysql -u${DATABASE_ROOT_USER} -p${DATABASE_ROOT_PASSWORD} -h${DATABASE_HOST} -Ne"SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='${DATABASE_NAME}' AND TABLE_TYPE='BASE TABLE'") \
  | grep ^DROP \
  | sed -e 's/DROP TABLE IF EXISTS/DELETE FROM/g' \
> tests/Resources/Sql/base.default.truncate.sql
echo 'SET FOREIGN_KEY_CHECKS=0;START TRANSACTION;' | cat - tests/Resources/Sql//base.default.truncate.sql > temp && mv temp tests/Resources/Sql/base.default.truncate.sql
echo 'COMMIT;' >> tests/Resources/Sql/base.default.truncate.sql