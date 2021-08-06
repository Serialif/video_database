<body style="background-color: #16273b; padding: 20px">
<div style="border-radius: .25rem; background-color: #5c90cb; padding: 20px; text-align: center">
    <p><a href="https://serialif.com"><img width="80" height="80" src="https://serialif.com/images/serialif-white.png" alt="Serialif"></a>

<hr style="background: #16273b;">

<h1 style="color: #16273b;">Video database</h1>

</div>
<br>
Video database is a video database management site developed with Symfony

<h2 style="color: #16273b; border-radius: .25rem; background-color: #5c90cb; padding: 10px; margin: 60px 0 0 0; text-align: center">Development environment</h2>

### Requires

* PHP 7.4
* Composer
* Symfony CLI
* Docker
* Docker-composer

You can check the requirements (except Docker and Docker-compose) with the following command:

```bash
symfony check:requirements
```

### Launch the development environment

```bash
docker-composer up -d
symfony serve -d
```

### Configure the database

#### Create tables

```bash
symfony console doctrine:migrations:migrate
```

#### Add fake data

```bash
symfony console doctrine:fixtures:load
```

<h2 style="color: #16273b; border-radius: .25rem; background-color: #5c90cb; padding: 10px; margin: 60px 0 0 0; text-align: center">Development Tools</h2>

### PHPUnit

Run tests :

```bash
php bin/phpunit --testdox
```

### PHPStan

Run analyse

```bash
php bin/phpstan analyse src/ tests/ --level=3
```

### PHP_CodeSniffer

Tokenizes PHP, JavaScript and CSS files to detect violations of coding standard:

```bash
php bin/phpcs
```

Automatically correct coding standard violations:

```bash
php bin/phpcbf
```

<h2 style="color: #16273b; border-radius: .25rem; background-color: #5c90cb; padding: 10px; margin: 60px 0 0 0; text-align: center">License</h2>

MIT

</body>