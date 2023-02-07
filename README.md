# Real Nutrition

## Prerequisites

-   VS Code
-   PHP ^8.0
-   Composer

## Clone repository:

```
git clone https://github.com/EricStautmeister/RealNutrition.git RealNutrition
```

Create a `.env` file with credentials in the root folder in the following format:

```
HOST=...
USERNAME=...
PASSWORD=...
DATABASE=...
MYSQL_ATTR_SSL_CA=...
```

## Installations: <br>

Download the MySQL local server MSI file from [here](https://dev.mysql.com/downloads/file/?id=516926).
Follow [this](https://www.prisma.io/dataguide/mysql/setting-up-a-local-mysql-database) guide.

Open VS Code and install the extension `cweijan.vscode-mysql-client2`.

## Setup Database structure
Open the `cweijan.vscode-mysql-client2` database extension, and add a connection, by adding all credentials necessary. Press save. 

Create a file called something like `setup.sql` with the following contents:

```
CREATE DATABASE RealNutrition;

CREATE TABLE `users` (
    `id` integer NOT NULL,
    `email` varchar(64) NOT NULL,
    `password` varchar(64) NOT NULL,
    PRIMARY KEY (`id`)
);
```
Right click somewhere in the file and click `Run Selected SQL`.

## Install dependencies:

```
composer install
```

## Run the server in developer mode

```
php -S localhost:8000 -t .
```
