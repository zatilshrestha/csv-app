<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Project
This is a **Laravel 12** application called **CSV App** that allows users to upload CSV files containing company data. The application
processes the uploaded CSV files, validates the data, and stores valid entries in a **MySql** database. It also provides
a user interface to view, and download the data with filter. Application is containerized using **Docker** for easy
setup and deployment. App uses server-side pagination for efficient large data handling. It uses **Sanctum** for user
authentication and authorization.

## Project Setup Instructions

Copy .env.example to .env

```
cp .env.example .env
```

Docker Setup - build Docker containers:

```
docker-compose up --build -d
```

### SSH into the container

```
docker exec -it csv_app bash
```

Install Laravel Dependencies

```
composer install
``` 

Update .env file:

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=csv_app
DB_USERNAME=csv_app
DB_PASSWORD=csv_app
```

Generate app key

```
php artisan key:generate
```

Run migrations with seeders

```
php artisan migrate --seed
```

Access the application at http://localhost:9000

Run tests

```
php artisan test
```

Run specific test file

```
php artisan test --filter=LoginTest
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
