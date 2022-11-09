run:
	@php artisan serve

setup-dev:
	@docker-compose up

clean-dev:
	@docker-compose down

migrate:
	@php artisan migrate

clean-cache:
	@php artisan optimize && php artisan config:cache

gen-migrate:
	@$(eval NAME := $(shell read -p "Enter your file name: " v && echo $$v))
	php artisan make:migration ${NAME} --create=${NAME}

create-middleware:
	@$(eval NAME := $(shell read -p "Enter your file name: " v && echo $$v))
	php artisan make:middleware ${NAME}
