# Real Nutrition
Figure out what you are eating and how you can get in all your important nutrients. 


## Developer Instructions
### Prerequisites

-   [VS Code](https://code.visualstudio.com/download)
-   [PHP ^8.0](#php-installation)
-   [Composer](#composer-installation)


### Clone repository:

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

### Installations: <br>

Download the MySQL local server MSI file from [here](https://dev.mysql.com/downloads/file/?id=516926).
Follow [this](https://www.prisma.io/dataguide/mysql/setting-up-a-local-mysql-database) guide.

Open VS Code and install the extension `cweijan.vscode-mysql-client2`.

### Setup Database structure
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

### Install dependencies:

```
composer install
```

### Run the server in developer mode
Run the following command in the terminal, then navigate to `localhost:8000` in the browser. 
```
php -S localhost:8000 -t .
```

### PHP Installation



#### On Windows:
Go to https://www.php.net/downloads and download php 8.0^.
If you are on windows, get the thread safe version. 

Extract all files into your systems root folder, so on windows this would be the `C:\` folder and rename the folder to `php`. Accept any permissions required to do so. 

Copy the path to the folder (`C:\php`).
    - Hit `Win + R` and enter `sysdm.cpl` and press ok
    - A window will pop up, there you will go to the `Advanced` tab, and at the bottom should be a button with `Environment Variables`. Click that.
    - In the lower window saying `System Variables`, double click the Variable named `Path`. 
    - Click on `New`, and enter the path to the `php` folder, and press `ok`, then press `ok` again, and then press `apply` or `ok`.

Now PHP should be installed on your mashine, test it out by opening a terminal window (`Win + R` and `cmd`), and typing out `php -v`. If no errors occur, php is installed. 

#### On linux:
Enter the following commands one at a time. 
```
sudo apt-get update
sudo apt-get upgrade
sudo apt-get install php
php --version
```
If no errors occur, php is installed correctly. 


### Composer Installation
Open a terminal window (`Win + R` and `cmd`)`and paste the following:
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```
Move the `composer.phar` file to a directory on your PATH so you can simply call composer from any directory (Global install). 
I will recommend the following:
##### On Windows: 
Enter the following into the terminal: 
```
move .\composer.phar C:\php\composer.phar
cd C:\
echo @php "%~dp0composer.phar" %*>composer.bat
```

##### On linux
Enter the following into the terminal: 
```
sudo mv composer.phar /usr/local/bin/composer
```
