init: init-ci frontend-ready
init-ci: docker-down-clear \
	api-clear frontend-clear \
	docker-pull docker-build docker-up \
	api-init frontend-init

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-build:
	docker-compose build --pull

docker-rebuild: docker-build docker-down docker-up

docker-restart: docker-down docker-up

docker-pull:
	docker-compose pull

docker-down-clear:
	docker-compose down -v --remove-orphans

api-init: api-permission api-composer-install api-wait-db api-migrate api-fixtures

api-clear:
	docker run --rm -v ${PWD}/api://var/www -w /var/www alpine sh -c 'rm -rf var/log/cli/* var/log/fpm-fcgi/* var/cache/* var/upload/* var/thumbs/*'

api-composer-install:
	docker-compose run --rm php-cli composer install

api-permission:
	docker run --rm -v ${PWD}/api://var/www -w /var/www alpine chmod 777 var/cache/

api-wait-db:
	docker-compose run --rm php-cli wait-for-it postgres:5432 -t 30

api-migrate:
	docker-compose run --rm php-cli php ./bin/console doctrine:migrations:migrate --quiet

api-test:
	docker-compose run --rm php-cli composer test

api-cs-fix:
	docker-compose run --rm php-cli composer php-cs-fixer fix

api-fixtures:
	docker-compose run --rm php-cli php ./bin/console doctrine:fixtures:load --no-interaction

frontend-clear:
	docker run --rm -v ${PWD}/frontend://var/www -w /var/www alpine sh -c 'rm -rf .ready build'

frontend-init: frontend-yarn-install

frontend-yarn-install:
	docker-compose run --rm frontend-node-cli yarn install

frontend-ready:
	docker run --rm -v ${PWD}/frontend://var/www -w //var/www alpine touch .ready

build: build-gateway build-frontend build-api

build-gateway:
	docker --log-level=debug build --pull --file=gateway/docker/production/nginx/Dockerfile --tag=${REGISTRY}/ts-gateway:${IMAGE_TAG} gateway/docker

build-frontend:
	docker --log-level=debug build --pull --file=frontend/docker/production/nginx/Dockerfile --tag=${REGISTRY}/ts-frontend:${IMAGE_TAG} frontend

build-api:
	docker --log-level=debug build --pull --file=api/docker/production/nginx/Dockerfile --tag=${REGISTRY}/ts-api:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=api/docker/production/php-fpm/Dockerfile --tag=${REGISTRY}/ts-api-php-fpm:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=api/docker/production/php-cli/Dockerfile --tag=${REGISTRY}/ts-api-php-cli:${IMAGE_TAG} api

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make build

push: push-gateway push-frontend push-api

push-gateway:
	docker push ${REGISTRY}/ts-gateway:${IMAGE_TAG}

push-frontend:
	docker push ${REGISTRY}/ts-frontend:${IMAGE_TAG}

push-api:
	docker push ${REGISTRY}/ts-api:${IMAGE_TAG}
	docker push ${REGISTRY}/ts-api-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY}/ts-api-php-cli:${IMAGE_TAG}

deploy:
	ssh ${HOST} -p ${PORT} 'rm -rf site_${BUILD_NUMBER}'
	ssh ${HOST} -p ${PORT} 'mkdir site_${BUILD_NUMBER}'
	scp -P ${PORT} docker-compose-production.yml ${HOST}:site_${BUILD_NUMBER}/docker-compose.yml
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "COMPOSE_PROJECT_NAME=ts" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "REGISTRY=${REGISTRY}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "API_DB_PASSWORD=${API_DB_PASSWORD}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose pull'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose up --build -d api-postgres api-php-cli'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose run api-php-cli wait-for-it api-postgres:5432 -t 60'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose run api-php-cli php bin/app.php migrations:migrate --no-interaction'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose up --build --remove-orphans -d'
	ssh ${HOST} -p ${PORT} 'rm -f site'
	ssh ${HOST} -p ${PORT} 'ln -sr site_${BUILD_NUMBER} site'

rollback:
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose pull'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose up --build --remove-orphans -d'
	ssh ${HOST} -p ${PORT} 'rm -f site'
	ssh ${HOST} -p ${PORT} 'ln -sr site_${BUILD_NUMBER} site'