#!/usr/bin/make -f

PHPCS_STANDARD="vendor/drupal/coder/coder_sniffer/Drupal"
PHPCS_DIRS=src

# Display a list of the commands
list:
	@$(MAKE) -pRrq -f $(lastword $(MAKEFILE_LIST)) : 2>/dev/null | awk -v RS= -F: '/^# File/,/^# Finished Make data base/ {if ($$1 !~ "^[#.]") {print $$1n}}' | sort | egrep -v -e '^[^[:alnum:]]' -e '^$@$$'

build: init lint-php

init:
	composer install --prefer-dist --no-progress

lint-php:
	bin/phpcs --standard=${PHPCS_STANDARD} ${PHPCS_DIRS}

fix-php:
	bin/phpcbf --standard=${PHPCS_STANDARD} ${PHPCS_DIRS}

phar:
	composer install --no-dev
	php -d phar.readonly=off bin/phar-composer build .

.PHONY: list build init lint-php fix-php phar
