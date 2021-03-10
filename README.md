# Slot Command

A simple command on Lumen which plays a typical Slot game

### Requirements

-   PHP 8.0 (included in Docker)

### Docker

It has a docker-compose.yml file to spin up nginx and php containers and initialize the application.
Just copy or rename the docker-compose.example.yml as your needs and execute the following commands:

-   `docker-compose up`
-   `docker-compose exec php composer install`
-   `docker-compose exec php php artisan slot`

### Testing

If you run docker you can use the following command to perform a test over SlotCommand class

-   `docker-compose exec php php vendor/bin/phpunit --testdox`
