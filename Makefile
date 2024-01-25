SHELL = /bin/bash

uid = $$(id -u)
gid = $$(id -g)
pwd = $$(pwd)

include .env

## update		Rebuild Docker images and start stack.
.PHONY: update
update: build up

## reset		Teardown stack, install and start.
.PHONY: reset
reset: .reset

.PHONY: .reset
.reset: .down .install .up

##
## Docker
## ------
##

## build		Build the Docker images.
.PHONY: build
build:
	docker-compose build

## install		Install API dependencies and setup the database
.PHONY: install
install: .install

.PHONY: .install
install:
	docker-compose run --rm api composer install
	docker-compose run --rm api php bin/console doctrine:database:create --if-not-exists

## up		Start the Docker stack.
.PHONY: up
up: .up

.up:
	docker-compose up -d

## down		Stop the Docker stack.
.PHONY: down
down: .down

.down:
	docker-compose down

## composer [c=]	Create and run container to run composer commands.
.PHONY: composer
composer:
	docker-compose run --rm api composer ${c}

## api-cli	Enter a shell for the API.
.PHONY: api-cli
api-cli:
	docker-compose run --rm api sh

## api-cache	Clear cache for dev environment.
.PHONY: api-cache
api-cache:
	docker-compose run --rm api php bin/console cache:clear
	docker-compose run --rm api php bin/console cache:warmup

## console [c=]	Enter the app container to run console commands.
.PHONY: console
console:
	docker-compose run --rm api php bin/console ${c}

##
## Tests
## -----
##

## api-setup-tests	Create and setup the database for API related tests.
.PHONY: api-setup-tests
api-setup-tests:
	docker-compose run --rm api php bin/console doctrine:database:drop --if-exists --force -e test
	docker-compose run --rm api php bin/console doctrine:database:create -n -e test
	docker-compose run --rm api php bin/console doctrine:schema:update --complete --force -e test

## api-tests		Run the API tests.
.PHONY: api-tests
api-tests:
	docker-compose run --rm api ./vendor/bin/phpunit

## api-tests-coverage	Run the tests including coverage report as HTML.
.PHONY: api-tests-coverage
api-tests-coverage:
	docker-compose run --rm api php -d xdebug.mode=coverage ./vendor/bin/phpunit --coverage-html ./coverage

## api-tests-debug	Run the API tests in debug mode.
.PHONY: api-tests-debug
api-tests-debug:
	docker-compose run --rm api php -d xdebug.start_with_request=true ./vendor/bin/phpunit

##
## Helpers
## -------
##

.PHONY: database-reset
database-reset:
	docker-compose run --rm api php bin/console app:reset-postgres --including-grant --no-interaction
	(. ./.env ; docker-compose run -T --rm db psql --quiet --dbname=postgresql://$$DB_USER:$$DB_PASSWORD@$$DB_HOST/$$DB_DATABASE < .docker/db/dev.pg-dump)
	docker-compose run --rm api php bin/console doctrine:migrations:migrate --no-interaction
	docker-compose run --rm api php bin/console app:validate-collation

.PHONY: database-dump
database-dump:
	(. ./.env ; docker-compose run --rm api pg_dump --dbname=postgresql://$$DB_USER:$$DB_PASSWORD@$$DB_HOST/$$DB_DATABASE > .docker/db/dev.pg-dump)

.PHONY: doctrine-migrations-migrate
doctrine-migrations-migrate:
	docker-compose run --rm api php bin/console doctrine:migrations:migrate -n

.PHONY: doctrine-migrations-rollback
doctrine-migrations-rollback:
	docker-compose run --rm api php bin/console doctrine:migrations:migrate prev

.PHONY: doctrine-migrations-diff
doctrine-migrations-diff:
	docker-compose run --rm api php bin/console doctrine:migrations:diff

.PHONY: database-schema-validate
database-schema-validate:
	docker-compose run --rm api ./bin/console doctrine:schema:validate

.PHONY: doctrine-collation-validate
doctrine-collation-validate:
	docker-compose run --rm api ./bin/console app:validate-collation

## xdebug-disable		Disable Xdebug.
.PHONY: xdebug-disable
xdebug-disable:
	docker-compose exec -T -u 0:0 api sh -c "sed -i -e \"s/xdebug.mode=.*/xdebug.mode=off/\" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && kill -USR2 1"
	docker-compose exec -T -u 0:0 job-queue sh -c "sed -i -e \"s/xdebug.mode=.*/xdebug.mode=off/\" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"
	docker-compose exec -T -u 0:0 scheduler sh -c "sed -i -e \"s/xdebug.mode=.*/xdebug.mode=off/\" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"
	# Symfony workers don't respect USR2, so they must be restarted to enable xdebug
	docker-compose restart job-queue scheduler

## xdebug-enable		Enable Xdebug for remote debugging.
.PHONY: xdebug-enable
xdebug-enable:
	docker-compose exec -T api mkdir -p var/profiler
	docker-compose exec -T -u 0:0 api sh -c "sed -i -e \"s/xdebug.mode=.*/xdebug.mode=profile,debug,develop/\" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && kill -USR2 1"
	docker-compose exec -T -u 0:0 job-queue sh -c "sed -i -e \"s/xdebug.mode=.*/xdebug.mode=profile,debug,develop/\" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"
	docker-compose exec -T -u 0:0 scheduler sh -c "sed -i -e \"s/xdebug.mode=.*/xdebug.mode=profile,debug,develop/\" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"
	# Symfony workers don't respect USR2, so they must be restarted to enable xdebug
	docker-compose restart job-queue scheduler

## api-code-validation		Run code fixers and linters for the API.
.PHONY: api-code-validation
api-code-validation:
	docker-compose run --rm api ./vendor/bin/php-cs-fixer fix
	docker-compose run --rm api ./vendor/bin/psalm --threads=8 --show-info=false
