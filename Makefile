CONSOLE := $(PHP) bin/console

docker-up:
	docker-compose up -d --build

docker-down:
	docker-compose down

docker-exec-php:
	docker exec -it todo-list-php-1 bash

migrate:
	$(CONSOLE) doctrine:migrations:migrate latest --no-interaction

migrations-generate:
	$(CONSOLE) doctrine:migrations:generate

migrations-status:
	$(CONSOLE) doctrine:migrations:list  --no-interaction

set-permissions-wo-sudo:
	chmod -R ug+rw .
	chmod -R a+rws var/cache var/log public/uploads

cache-prod:
	rm -rf .env.*php
	$(CONSOLE) cache:clear --env=prod

keypair:
	php bin/console lexik:jwt:generate-keypair

phpstan:
	vendor/bin/phpstan analyse src

cs:
	vendor/bin/phpcs src

cbf:
	vendor/bin/phpcbf src

install-cs-fixer:
	mkdir --parents tools/php-cs-fixer
	composer require --working-dir=tools/php-cs-fixer friendsofphp/php-cs-fixer

cs-fixer:
	tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src

install-phpmd:
	mkdir --parents tools/phpmd
	composer require --working-dir=tools/phpmd phpmd/phpmd

phpmd:
	tools/phpmd/vendor/bin/phpmd src ansi phpmd.xml

ecs:
	vendor/bin/ecs check src

ecs-fix:
	vendor/bin/ecs check src --fix