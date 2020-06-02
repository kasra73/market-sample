# Snapp Market Sample Project

## About

This is a sample market developed using laravel with following requirements:

- Should have JWT authentication
- Admin Shall be able to upload csv file for products
- search among products with categories filter capability and pagination
- Have appropriate seeds and migration to initiate database

An angular spa app is also included with login & search capabilities. (pagination and category filter is not included in ui)

## How to

### Install & Run

- `composer install`
- `php artisan migrate --seed`
- `php artisan serve`

### Run Tests

- `php artisan test`

### Run using Docker

- `docker-compose up -d --build`
- `chown 33:33 -R docker-data/storage`
- `docker-compose exec app php artisan migrate --seed`

## License

This project is open-sourced software licensed under the [BSD 3-Clause License](https://opensource.org/licenses/BSD-3-Clause).
