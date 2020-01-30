all: static test

test:
	vendor/bin/phpunit

static: lint cs md

lint:
	@find src -name '*.php' -print0 | xargs -0 -n1 php -l 1>/dev/null
	@find test -name '*.php' -print0 | xargs -0 -n1 php -l 1>/dev/null
	@echo [OK] Lint

cs:
	@vendor/bin/phpcs src --colors --standard=PSR2
	@vendor/bin/phpcs test --colors --standard=PSR2
	@echo [OK] PHPCS

md:
	@vendor/bin/phpmd src text cleancode,codesize,design,naming,unusedcode
	@vendor/bin/phpmd test text cleancode,codesize,design,unusedcode
	@echo [OK] PHPMD

.PHONY: all test static lint cs md
