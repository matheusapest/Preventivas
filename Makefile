.PHONY: help up down restart build rebuild logs bash db node \
        art migrate fresh seed rollback status optimize \
        composer npm test

APP=preventivas-php
DB=preventivas-db
NODE=preventivas-node

help:
	@echo ""
	@echo "=== Preventivas ==="
	@echo ""
	@echo "make up          -> Sobe os containers"
	@echo "make down        -> Para os containers"
	@echo "make restart     -> Reinicia os containers"
	@echo "make build       -> Build das imagens"
	@echo "make rebuild     -> Rebuild completo"
	@echo "make logs        -> Logs do PHP"
	@echo "make bash        -> Shell do PHP"
	@echo "make db          -> Shell do MariaDB"
	@echo "make node        -> Shell do Node"
	@echo ""
	@echo "Laravel:"
	@echo "make migrate"
	@echo "make fresh"
	@echo "make seed"
	@echo "make rollback"
	@echo "make status"
	@echo "make optimize"
	@echo ""
	@echo "Composer:"
	@echo "make composer CMD='install'"
	@echo "make composer CMD='require laravel/breeze'"
	@echo ""
	@echo "Artisan:"
	@echo "make art CMD='route:list'"
	@echo "make art CMD='make:model Produto -m'"
	@echo ""
	@echo "NPM:"
	@echo "make npm CMD='run dev'"
	@echo ""

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose restart

build:
	docker compose build

rebuild:
	docker compose down
	docker compose build --no-cache
	docker compose up -d

logs:
	docker logs -f $(APP)

bash:
	docker exec -it $(APP) bash

db:
	docker exec -it $(DB) mariadb -uroot -proot

node:
	docker exec -it $(NODE) sh

art:
	docker exec -it $(APP) php artisan $(CMD)

migrate:
	docker exec -it $(APP) php artisan migrate

fresh:
	docker exec -it $(APP) php artisan migrate:fresh --seed

seed:
	docker exec -it $(APP) php artisan db:seed

rollback:
	docker exec -it $(APP) php artisan migrate:rollback

status:
	docker exec -it $(APP) php artisan migrate:status

optimize:
	docker exec -it $(APP) php artisan optimize

composer:
	docker exec -it $(APP) composer $(CMD)

npm:
	docker exec -it $(NODE) npm $(CMD)

test:
	docker exec -it $(APP) php artisan test
