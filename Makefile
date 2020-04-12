install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 src tests bin

lint-fix:
	composer run-script phpcbf -- --standard=PSR12 src tests bin

test:
	composer run-script phpunit tests
