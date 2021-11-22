start:
	docker-compose up -d --build
	docker exec backend composer install
	docker exec backend php artisan migrate
	docker exec backend php artisan queue:work
test:
	docker exec backend vendor/bin/phpunit
stop:
	docker-compose stop
