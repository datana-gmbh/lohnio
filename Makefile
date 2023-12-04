# vim: set tabstop=8 softtabstop=8 noexpandtab:
NOW=`TZ='Europe/Berlin' date +'%Y.%m.%d.%H%M'`
APP_ENV=test
CONNECTION_NAME_DEFAULT=default
ENTITY_MANAGER_NAME_DEFAULT=default
YARN=`which yarn`
YARN ?= @docker run --rm --user $UID:$GID -v ${PWD}:/usr/src/app -w /usr/src/app node:18.12-alpine yarn

.PHONY: release
release:
	echo "Creating Release: ${NOW}"
	git checkout develop
	git pull
	git checkout master
	git pull
	git merge develop
	echo "${NOW}" > VERSION
	git add VERSION
	git commit -m "Fix: Update VERSION file with current release version"
	git push
	gh release create ${NOW} --generate-notes --target=master

.PHONY: it
it: cs static-code-analysis tests ## Runs the cs, static-code-analysis, and tests targets

.PHONY: clear-logs
clear-logs: ## Empties log files on a production system
	echo "" > var/log/prod.deprecations.log
	echo "" > var/log/prod.log

.PHONY: code-coverage
code-coverage: vendor ## Collects code coverage from running unit tests with phpunit/phpunit
	symfony php vendor/bin/phpunit --coverage-xml=.build/phpunit/ --log-junit .build/phpunit/log.junit.xml

.PHONY: cs
cs: vendor ## Normalizes composer.json with ergebnis/composer-normalize and fixes code style issues with friendsofphp/php-cs-fixer
	symfony composer normalize
	mkdir -p .build/php-cs-fixer
	symfony php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --diff --verbose

.PHONY: dependency-analysis
dependency-analysis: vendor ## Runs a dependency analysis with maglnet/composer-require-checker
	symfony php tools/composer-require-checker check --config-file=$(shell pwd)/composer-require-checker.json
	symfony php vendor/bin/composer-unused

.PHONY: doctrine
doctrine: vendor ## Runs doctrine commands to ensure we have a local test database set up
	symfony php bin/console doctrine:database:drop --connection=${CONNECTION_NAME_DEFAULT} --env=${APP_ENV} --force --if-exists
	symfony php bin/console doctrine:database:create --connection=${CONNECTION_NAME_DEFAULT} --env=${APP_ENV}
	symfony php bin/console doctrine:migrations:status --env=${APP_ENV}
	symfony php bin/console doctrine:migrations:migrate --env=${APP_ENV} --no-interaction
	symfony php bin/console doctrine:mapping:info --em=${ENTITY_MANAGER_NAME_DEFAULT} --env=${APP_ENV}
	symfony php bin/console doctrine:schema:validate --em=${ENTITY_MANAGER_NAME_DEFAULT} --env=${APP_ENV} || symfony php bin/console doctrine:migrations:diff --env=${APP_ENV} --no-interaction

.PHONY: help
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: static-code-analysis
static-code-analysis: vendor ## Runs a static code analysis with phpstan/phpstan
	symfony php bin/console cache:warmup
	mkdir -p .build/phpstan
	symfony php vendor/bin/phpstan analyse --memory-limit=-1

.PHONY: static-code-analysis-baseline
static-code-analysis-baseline: vendor ## Generates a baseline for static code analysis with phpstan/phpstan
	symfony php bin/console cache:warmup
	mkdir -p .build/phpstan
	symfony php vendor/bin/phpstan analyze --generate-baseline=phpstan-baseline.neon --memory-limit=-1

.PHONY: refactoring
refactoring: vendor ## Refactor the code using rector/rector
	symfony php bin/console cache:warmup
	symfony php vendor/bin/rector process --config rector.php

.PHONY: tests-changed
tests-changed: export APP_ENV=test
tests-changed: vendor doctrine
	symfony php vendor/bin/phpunit --configuration=phpunit.xml.dist $(shell git diff HEAD --name-only | grep Test.php | xargs )

.PHONY: tests
tests: export APP_ENV=test
tests: vendor doctrine tests-auto-review tests-unit tests-integration tests-functional tests-acceptance ## Runs auto-review, unit, functional, integration, and acceptance tests with phpunit/phpunit (and symfony/panther)

.PHONY: tests-acceptance
tests-acceptance: doctrine vendor ## Runs acceptance tests with phpunit/phpunit
	mkdir -p .build/phpunit
	symfony php vendor/bin/phpunit --configuration=phpunit.xml.dist --testsuite=acceptance

.PHONY: tests-auto-review
tests-auto-review: export APP_ENV=test
tests-auto-review: vendor ## Runs auto-review tests with phpunit/phpunit
	mkdir -p .build/phpunit
	symfony php vendor/bin/phpunit --configuration=phpunit.xml.dist --testsuite=auto-review

.PHONY: tests-functional
tests-functional: export APP_ENV=test
tests-functional: doctrine vendor ## Runs functional tests with phpunit/phpunit
	mkdir -p .build/phpunit
	symfony php vendor/bin/phpunit --configuration=phpunit.xml.dist --testsuite=functional --testdox

.PHONY: tests-integration
tests-integration: export APP_ENV=test
tests-integration: doctrine vendor ## Runs integration tests with phpunit/phpunit
	mkdir -p .build/phpunit
	symfony php vendor/bin/phpunit --configuration=phpunit.xml.dist --testsuite=integration --testdox

.PHONY: tests-unit
tests-unit: export APP_ENV=test
tests-unit: vendor ## Runs unit tests with phpunit/phpunit
	mkdir -p .build/phpunit
	symfony php vendor/bin/phpunit --configuration=phpunit.xml.dist --testsuite=unit

.PHONY: tests-temp
tests-temp: export APP_ENV=test
tests-temp: doctrine vendor ## Runs unit tests with phpunit/phpunit and "@group temp"
	mkdir -p .build/phpunit
	symfony php vendor/bin/phpunit --configuration=phpunit.xml.dist --testdox --group=temp

vendor: composer.json composer.lock tools/composer.json tools/composer.lock
	symfony composer validate
	symfony composer install --no-interaction --no-progress --no-scripts
	symfony composer install --no-interaction --no-progress --no-scripts --working-dir=tools
	@touch -mr $(shell ls -Atd $? | head -1) $@

# Automatically updates node_modules if yarn.lock has changed
# @see https://stackoverflow.com/a/44226605/6849366
node_modules: yarn.lock
	@$(YARN) install --production=false
	@touch -mr $(shell ls -Atd $? | head -1) $@

# Automatically run encore if node_modules || webpack configuration changed
public/build/manifest.json: webpack.config.js node_modules
	$(YARN) run encore production

.PHONY: dev
dev: doctrine

.PHONY: infection
infection: vendor doctrine code-coverage
	symfony php vendor/bin/infection --skip-initial-tests --show-mutations --only-covering-test-cases --coverage=.build/phpunit/
