#!make
PROJECT:= $(shell node -p "require('./package.json').name")
NVM=v0.38.0
NODE=v14.16.1
isDocker := $(shell docker info > /dev/null 2>&1 && echo 1)
user := $(shell id -u)
group := $(shell id -g)

ifeq ($(isDocker), 1)
	dc := USER_ID=$(user) GROUP_ID=$(group) docker-compose
	de := docker-compose exec
	dr := $(dc) run --rm
	sy := $(de) server bin/console
	php := $(dr) --no-deps php
else
	sy := php bin/console
	php :=
endif

## Install workflow for dev
install: 
	@echo "Installing node project ${PROJECT}..."
	. ${NVM_DIR}/nvm.sh && nvm install ${NODE} && nvm use ${NODE}
	npm install

    
.PHONY: dev
dev: 
	$(dc) up -d --build

.PHONY: clean
clean:
	rm -rf ./node_modules
	$(dc) down -v

.PHONY: destroy
destroy:
	$(dc) down -v --rmi all --remove-orphans

## Production: Pull image annd build environment
production:
	@echo "Pull & build containers for $(PROJECT)..."
	$(dc) up

## start	:	Start containers without updating.
start:
	@echo "Starting containers for $(PROJECT) from where you left off..."
	@docker-compose start

## stop	:	Stop containers.
stop:
	@echo "Stopping containers for $(PROJECT)..."
	@docker-compose stop


## prune	:	Remove containers and their volumes.
##		You can optionally pass an argument with the service name to prune single container
##		prune mariadb	: Prune `mariadb` container and remove its volumes.
##		prune mariadb solr	: Prune `mariadb` and `solr` containers and remove their volumes.
prune:
	@echo "Removing containers for $(PROJECT)..."
	@docker-compose down -v $(filter-out $@,$(MAKECMDGOALS))

## ps	:	List running containers.
ps:
	@docker ps --filter name='$(PROJECT)*'


## logs	:	View containers logs.
##		You can optinally pass an argument with the service name to limit logs
##		logs php	: View `php` container logs.
##		logs nginx php	: View `nginx` and `php` containers logs.
logs:
	@docker-compose logs -f $(filter-out $@,$(MAKECMDGOALS))

## Bash	:	shell container.
##		ex: bash server	: shell server container
bash:
	$(de) $(filter-out $@,$(MAKECMDGOALS)) sh

nvm:
	curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/${NVM}/install.sh | bash

svn:
	bash scripts/release.sh

help: 
	@echo "install: Install ${PROJECT}"
	@echo "dev: Start in development ${PROJECT}"
	@echo "clean: Clean ${PROJECT}"
	@echo "svn: Release app${PROJECT}"
