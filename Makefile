#!/usr/bin/make -f

cc_green="\033[0;32m" #Change text to green.
cc_end="\033[0m" #Change text back to normal.

PHPCS_STANDARD="vendor/drupal/coder/coder_sniffer/Drupal"
PHPCS_DIRS=src

# Display a list of the commands
.PHONY: list
list:
	@$(MAKE) -pRrq -f $(lastword $(MAKEFILE_LIST)) : 2>/dev/null | awk -v RS= -F: '/^# File/,/^# Finished Make data base/ {if ($$1 !~ "^[#.]") {print $$1n}}' | sort | egrep -v -e '^[^[:alnum:]]' -e '^$@$$'

build: init lint-php

init:
	@echo ${cc_green}">>> Installing dependencies..."${cc_end}
	composer install --prefer-dist --no-progress


lint-php:
	@echo ${cc_green}">>> Linting PHP code style..."${cc_end}
	bin/phpcs --standard=${PHPCS_STANDARD} ${PHPCS_DIRS}

fix-php:
	@echo ${cc_green}">>> Fixing PHP code style..."${cc_end}
	bin/phpcbf --standard=${PHPCS_STANDARD} ${PHPCS_DIRS}
