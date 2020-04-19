.PHONY:
test: static unit

.PHONY:
static:
	vendor/bin/psalm

.PHONY:
unit:
	vendor/bin/phpunit

.PHONY:
integration:
	vendor/bin/phpunit --testsuite integration
