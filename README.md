# Real Nutrition
Figure out what you are eating and how you can get in all your important nutrients. 

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
PORT=...
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

CREATE TABLE IF NOT EXISTS `auth` (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
);
```
Right click somewhere in the file and click `Run Selected SQL`.

## Install dependencies:

```
composer install
```

## Run the server in developer mode
Run the following command in the terminal, then navigate to `localhost:8000` in the browser. 
```
php -S localhost:8000 -t .
```
