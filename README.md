<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Project Setup Instructions
1. Copy .env.example to .env 
```
cp .env.example .env
```

2. Docker Setup - build Docker containers:
```
docker-compose up --build -d
```

### SSH into the container
```
docker exec -it csv_app bash
```

3. Install Laravel Dependencies
```
composer install
``` 

4. Update .env file:
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=csv_app
DB_USERNAME=csv_app
DB_PASSWORD=csv_app
```

5. Generate app key
```
php artisan key:generate
```

6. Run migrations with seeders
```
php artisan migrate --seed
```

7. Access the application at http://localhost:9000

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
