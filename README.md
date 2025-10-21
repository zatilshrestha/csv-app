<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Project

This is a **Laravel 12** web application called **CSV App** that allows users to upload CSV files containing company data.
The application processes the uploaded CSV files, validates the data, and stores valid entries in the database. Here are
the technologies and features used in this project:

### Backend:
- Laravel 12
- Docker
- PHP 8.2
- MySQL Database
- Eloquent ORM
- Laravel Sanctum for authentication
- Job Queues for processing CSV files asynchronously
- PhpUnit for testing

### Frontend:
- Blade Templating Engine
- Vue3 for SAP
- Bootstrap 5 for styling
- Axios for AJAX requests

### Features:
- User Authentication (Login, Logout)
- Company data listing with Pagination
- Filtering companies by All, Duplicates or Unique entries
- Companies data export to CSV
- Company data upload via CSV files
- Data Validation (Email format, required fields) when uploading CSV files
- Asynchronous Processing of CSV files using Job Queues
- Error logging for invalid entries

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

Generate app key

```
php artisan key:generate
```

Run migrations with seeders

```
php artisan migrate --seed
```

Run queue worker

```
php artisan queue:work
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
