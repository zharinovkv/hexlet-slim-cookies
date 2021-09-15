start:
	php -S localhost:8080 -t public public/index.php

test:
	composer run-script phpunit tests

du:
	composer dump-autoload

up:
	composer update

lint:
	composer run-script phpcs -- --standard=PSR12 public tests

lint-fix:
	composer run-script phpcbf -- --standard=PSR12  public tests

install:
	composer install