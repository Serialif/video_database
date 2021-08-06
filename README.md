<div align="center">
    <p align="center"><a href="https://serialif.com"><img width="80" height="80" src="https://serialif.com/images/serialif-white.png" alt="Serialif"></a>
<hr>
</div>

<h1 align="center">Video database</h1>
<br>

Video database is a video database management site developed with Symfony


## Environnement de développement

### Prérequis

* PHP 7.4
* Composer
* Symfony CLI
* Docker
* Docker-composer

Vous pouvez vérifier les prérequis (sauf Docker et Docker-compose) avec la commande suivante:

```bash
symfony check:requirements
```

### Lancer l'environnement de développement

```bash
docker-composer up -d
symfony serve -d
```

## Lancer des tests

```bash
php bin/phpunit --testdox
```

## Lancer des tests

```bash
php bin/phpunit --testdox
```


## License
MIT