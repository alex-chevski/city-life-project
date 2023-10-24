init: docker-down-clear \
	api-clear frontend-clear \
	docker-pull docker-memory docker-build docker-up \
	frontend-init api-init


up: docker-memory docker-up
down: docker-down
clear: docker-down-clear
restart: clear up
check: lint analyze test
lint: api-lint
analyze: api-analyze
test: api-test
test-unit: api-test-unit
test-unit-coverage: api-test-unit-coverage
test-functional: api-test-functional
test-functional-coverage: api-test-functional-coverage

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build

docker-memory:
	sudo sysctl -w vm.max_map_count=262144

api-clear:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf storage/debugbar/*'
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf storage/app/public/*'
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf public/build'

api-init: api-node-init api-composer-install api-permissions api-copy-to-env api-generate-app-key api-migrate-database api-search-init

api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod -R 755 .
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod -R 777 storage

api-copy-to-env:
	cat api/.env.example > api/.env

api-generate-app-key:
	docker compose run --rm api-php-cli php artisan key:generate


api-composer-install:
	docker compose run --rm api-php-cli composer install

api-lint:
	docker compose run --rm api-php-cli composer lint
	docker compose run --rm api-php-cli composer cs-check

api-test:
	docker compose run --rm api-php-cli composer test

api-migrate-database:
	docker compose run --rm api-php-cli php artisan migrate

api-migrate-database-refresh:
	docker compose run --rm api-php-cli php artisan migrate:refresh

api-migrate-database-refresh-seed:
	docker compose run --rm api-php-cli php artisan migrate:refresh --seed

api-test-unit:
	docker compose run --rm api-php-cli composer test -- --testsuite=unit

api-test-unit-coverage:
	docker compose run --rm api-php-cli composer test-coverage -- --testsuite=unit

api-test-functional:
	docker compose run --rm api-php-cli composer test -- --testsuite=functional

api-test-functional-coverage:
	docker compose run --rm api-php-cli composer test-coverage -- --testsuite=functional

api-analyze:
	docker compose run --rm api-php-cli composer psalm

api-cs-fix:
	docker compose run --rm api-php-cli composer php-cs-fixer fix

api-clear-cache-laravel:
	docker compose run --rm api-php-cli php artisan cache:clear
	docker compose run --rm api-php-cli php artisan config:clear

api-fake-ten-users-to-base:
	docker compose run --rm api-php-cli php artisan db:seed

api-horizon:
	docker compose run --rm api-php-cli php artisan horizon

api-horizon-pause:
	docker compose run --rm api-php-cli php artisan horizon:pause

api-horizon-continue:
	docker compose run --rm api-php-cli php artisan horizon:continue

api-horizon-terminate:
	docker compose run --rm api-php-cli php artisan horizon:terminate

api-search-init:
	docker compose run --rm api-php-cli php artisan search:init

api-start-cron:
	docker compose run --rm api-php-cli supercronic crontab

api-cron-task-show:
	docker logs city-life-project-api-php-cli-1

api-search-reindex:
	docker compose run --rm api-php-cli php artisan search:reindex

api-node-init: api-yarn-install api-ready api-vite-build

api-yarn-install:
	docker compose run --rm api-node-cli yarn install

api-ready:
	docker run --rm -v ${PWD}/frontend:/app -w /app alpine touch .ready

api-vite-build:
	docker compose run --rm api-node-cli yarn run build

api-vite-remove:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf public/build'

api-generate-token-passport:
	docker compose run --rm api-php-cli php artisan passport:install --force

frontend-clear:
	docker run --rm -v ${PWD}/frontend:/app -w /app alpine sh -c 'rm -rf .ready build'

frontend-init: frontend-yarn-install frontend-ready

frontend-yarn-install:
	docker compose run --rm frontend-node-cli yarn install

frontend-ready:
	docker run --rm -v ${PWD}/frontend:/app -w /app alpine touch .ready
