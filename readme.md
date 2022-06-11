
# Listopaye Test

I choose to do this test with Symfony 6 (php 8).

I use un docker environment created by 
- [@yoanbernabeu](https://github.com/yoanbernabeu) : 
A very simple Docker-compose to discover Symfony 6 with PHP 8.0.13 in 5 minutes
## Run Locally

Clone the project

```bash
git@github.com:AriaIII/listopaye.git
```

Run the docker-compose

```bash
  docker-compose build
  docker-compose up -d
```

Log into the PHP container

```bash
  docker exec -it php8-sf6 bash
```

Create your Symfony application and launch the internal server

```bash
  composer install
  symfony serve -d
  bin/console doctrine:migrations:migrate
```

Create an account (identical to your local session)

```bash
  adduser username
  chown username:username -R .
```

*Your application is available at http://127.0.0.1:9000*

If you need a database, modify the .env file like this example:

```yaml
  DATABASE_URL="postgresql://symfony:ChangeMe@database:5432/app?serverVersion=13&charset=utf8"
```

## Ready to use with

This docker-compose provides you :

- PHP-8.0.13-cli (Debian)
    - Composer
    - Symfony CLI
    - and some other php extentions
    - nodejs, npm, yarn
- postgres:13-alpine


## Requirements

Out of the box, this docker-compose is designed for a Linux operating system, provide adaptations for a Mac or Windows environment.

- Linux (Ubuntu 20.04 or other)
- Docker
- Docker-compose

## Test the project

To test the project, you can use Postman or other application. 

The first API request is 127.0.0.1:9000/period/declare (method : POST)

You send a vacation period which must have json format like this :

```json
{
  "type": "vacation",
  "startdate": "05-01-2022",
  "enddate": "15-05-2022", 
  "employee": 1
}
```
The created vacation periods are saved in a database. You can see them with the API request : 127.0.0.1:9000/period (method: GET)

Some unit tests are provided with PHPUnit. You can launch them with this command in the PHP container : 
```bash
php bin/phpunit
```
