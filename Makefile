all: static test

ok = "\033[92m✓\033[0m"

test:
	@vendor/bin/phpunit

static: lint cs md

lint:
	@printf "[...] Lint"
	@find src -name '*.php' -print0 | xargs -0 -n1 php -l 1>/dev/null
	@find test -name '*.php' -print0 | xargs -0 -n1 php -l 1>/dev/null
	@printf "\r"; echo [ $(ok) ] Lint

cs:
	@printf "[...] PHPCS"
	@vendor/bin/phpcs src --colors --standard=PSR2
	@vendor/bin/phpcs test --colors --standard=PSR2
	@printf "\r"; echo [ $(ok) ] PHPCS

md:
	@printf "[...] PHPMD"
	@vendor/bin/phpmd src text cleancode,codesize,design,naming,unusedcode
	@vendor/bin/phpmd test text cleancode,codesize,design,naming,unusedcode
	@printf "\r"; echo [ $(ok) ] PHPMD

.PHONY: all test