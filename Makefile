run:
	@php artisan serve

seed:
	@php artisan db:seed

ngrok:
	@ngrok http 8000

setup-dev:
	@docker-compose up

clean-dev:
	@docker-compose down

migrate:
	@php artisan migrate

clean-cache:
	@php artisan optimize && php artisan config:cache && php artisan config:clear

gen-migrate:
	@$(eval NAME := $(shell read -p "Enter your file name: " v && echo $$v))
	php artisan make:migration ${NAME} --create=${NAME}

create-middleware:
	@$(eval NAME := $(shell read -p "Enter your file name: " v && echo $$v))
	php artisan make:middleware ${NAME}
