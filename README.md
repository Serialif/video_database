<p align="center">
    <a href="https://serialif.com"><img width="80" height="80" src="https://serialif.com/images/serialif-white.png" alt="Serialif"></a>
</p>

<hr>

<h1 align="center">Video database</h1>

Video database is a video database management site developed with Symfony

## Development environment

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

## Development Tools

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

## License

MIT

</body>