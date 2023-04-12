# News Wave

This project holds the source code of the News Wave application.

### Technical Stack

-   PHP 8.x
-   MySQL 8.x
-   Laravel Framework 9.x

### Project setup with Docker
- Install Docker Engine
- Clone the project
- Navigate to the project
- Build the Docker image `docker build -t <image-name> .`
- `docker run -p <host-port> -d <image-name>`
<image-name> name of your applciation (e.g: myapp:v1.0)
Replace <host-port> with the port number you want to use on your local machine (e.g., 8000).
- `docker run -d --name laravel-mysql -e MYSQL_ROOT_PASSWORD=your_mysql_password -p 3306:3306 mysql:8.0` ( setup your password by replacing the your_mysql_password keyword for the MYSQL "root" user ).
- Access the MySQl Docker Container's Shell for creating the database: `docker start laravel-mysql`(This will open a shell inside the Docker container named laravel-mysql.)
- `docker exec -it laravel-mysql mysql -u root -p` ( This command connects to the MySQL container and opens the MySQL command-line )
- Create a database `CREATE DATABASE my_database;` ( replace your database name by my_database ).
- After that Run exit to come out of the mysql container `exit`
- Access the Laravel Docker Container's Shell `docker exec -it <image-name> /bin/bash` (This will open a shell inside the Docker container named <image-name>.)
- Run `cp .env.example .env`
- Update your .env file ( like mysql credentials )
-   Run `composer install`
-   Run `php artisan key:generate`
-   Run `php artisan migrate`
-   Run `php artisan db:seed`
-   Run `php artisan news:feed`

