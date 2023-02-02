# Real Nutrition

Install dependencies: 
```
composer install
```
Create a .env file with credentials in the following format:
```
HOST=...
USERNAME=...
PASSWORD=...
DATABASE=...
MYSQL_ATTR_SSL_CA=...
```
Start the dev server: 
```
php -S localhost:8000 -t .
```

Database Setup:

```
CREATE DATABASE RealNutrition;

CREATE TABLE `users` (
    `id` integer NOT NULL,
    `email` varchar(64) NOT NULL,
    `password` varchar(64) NOT NULL,
    PRIMARY KEY (`id`)
);
```
