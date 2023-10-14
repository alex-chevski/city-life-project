## About Project

Is a web portfolio application for rent an apartment.

### Using web tools

- [Laravel](https://laravel.com).
- [Docker and Docker compose](https://www.docker.com/).
- [Redis](https://redis.io/).
- [Mailhog](https://github.com/mailhog/MailHog).
- [Elasticsearch](https://www.elastic.co/).
- [PostgreSQL](https://www.postgresql.org/).
- [Swagger](https://swagger.io/).
- [Bootstrap](https://getbootstrap.com/).
- [Summernote](https://summernote.org/).
- [Font Awesome](https://fontawesome.com/).
- [Xdebug](https://xdebug.org/).
- [Cron] (https://github.com/aptible/supercronic).
- [NGINX](https://www.nginx.com/).

### Using components Laravel

- [Horizon](https://github.com/laravel/horizon).
- [Passport](https://github.com/laravel/passport).
- [Sanctum](https://github.com/laravel/sanctum).
- [Socialite](https://github.com/laravel/socialite).

---

# How to run project

Run in Linux, MacOS or Windows WSL terminal the docker development server and build project

    cd city-life-project && make init

Run Horizon(queue)

    cd city-life-project && make api-horizon

Cron runs by default to view the task

    make api-cron-task-show

After all stop the project

    cd city-life-project && make down

## Address

The application can now be accessed at

    https://localhost:8081

The mailer so confirm email, notify, sms and reset password can be accessed at

    http://localhost:8082

The rest api swagger can be accessed at

    https://localhost:8081/docs/

The queue Horizon can be accessed at (only for admin or moderator)

    https://localhost:8081/horizon/dashboard

## Additional

After auth to get access to admin panel you must add role admin or moderator

    cd city-life-project && docker compose run --rm api-php-cli php artisan user:role your_email_auth admin
    cd city-life-project && docker compose run --rm api-php-cli php artisan user:role your_email_auth moderator

To try build Elasticsearch

    cd city-life-project && make api-search-init
    cd city-life-project && make api-search-reindex

To seed database with data using seed classes

    cd city-life-project && make api-migrate-database-refresh-seed

## Testing

Run unit and functional tests

    cd city-life-project && make test

Run only unit tests

    cd city-life-project && make api-test-unit

Run only functional tests

    cd city-life-project && make api-test-functional

Run development code style fixer

    cd city-life-project && make api-cs-fixer

Run development analyzeer psalm

    cd city-life-project && make api-analyze

### Author

- **[frostep](https://github.com/frostep)**
