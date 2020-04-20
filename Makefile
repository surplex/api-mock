AM_IMAGE_TAG=api-mock
AM_GIT_HASH=abc1234

.PHONY: build
build:
	docker build . -t ${AM_IMAGE_TAG}

.PHONY: dev
dev:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d --build

.PHONY: test
test:
	docker-compose -p ${AM_GIT_HASH} -f docker-compose.yml -f docker-compose.test.yml up -d --build
	sleep 10
	docker exec -t ${AM_GIT_HASH}_php_1 //bin/bash -c 'bin/codecept run'
	docker-compose -p ${AM_GIT_HASH} -f docker-compose.yml -f docker-compose.test.yml down

.PHONY: prod
prod:
	docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build

.PHONY: clean
clean:
	docker-compose down --rmi local