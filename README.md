# Slot Command

A simple command on Lumen which plays a typical Slot game

### Requirements

-   PHP 7.3 (included in Docker)

### Docker

It has a docker-compose.yml file to spin up nginx and php containers and initialize the application.
Just copy or rename the docker-compose.example.yml as your needs and execute the following commands:

-   `docker-compose up`
-   `docker-compose exec php composer install`
-   `docker-compose exec php php artisan slot`

### Expected output

As example:

```
{
    "board": [
        "Q",
        "Q",
        "bird",
        "monkey",
        "dog",
        "bird",
        "Q",
        "bird",
        "J",
        "Q",
        "cat",
        "cat",
        "J",
        "10",
        "bird"
    ],
    "paylines": [
        {
            "0 3 6 9 12": 3
        },
        {
            "2 5 8 11 14": 3
        }
    ],
    "bet_amount": 100,
    "total_win": 40
}
```

### Testing

If you run docker you can use the following command to perform a test over SlotCommand class

-   `docker-compose exec php php vendor/bin/phpunit --testdox`
