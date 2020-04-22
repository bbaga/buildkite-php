.PHONY:
test: static unit

.PHONY:
static:
    vendor/bin/ecs check src tests
	vendor/bin/psalm

.PHONY:
static-fix:
    vendor/bin/ecs check src tests --fix

.PHONY:
unit:
	vendor/bin/phpunit

.PHONY:
integration:
	vendor/bin/phpunit --testsuite integration
