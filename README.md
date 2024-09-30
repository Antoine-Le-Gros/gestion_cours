# SAÉ 5.Real.01 - Développement avanc

## Auteurs : 
### Dylan Bonnevie, Mathis Claverie, Antoine Le-Gros, Romain Daunat, Louis Rattanavong, Erwan Lecomte

## Installation du projet

Une fois que le projet est cloné sur votre machine local, lancé le dans un IDE comme __PHPStorm__.
Ouvrez un terminal et lancez la commande :
```shell
composer install
```
Installer __composer__ est obligatoire si l'on veut installer les différentes bibliothèques et dépendances du projet, avec les bonnes versions.

## Lancement du projet
### Docker
Pour lancer le conteneur docker, lancez la commande :
```shell
docker-compose up -d
```

## Scripts
### start
Ce script lance le serveur symfony sans limite de temps en exécutant les commandes :
```shell
symfony serve
Composer\\Config::disableProcessTimeout
```
### test:csfixer
Ce script lance une vérification du code par PHP CS Fixer en exécutant la commande :
```shell
php-cs-fixer fix --dry-run
```
### fix:csfixer
Ce script lance une correction du code par PHP CS Fixer en exécutant la commande :
```shell
php-cs-fixer fix
```
### test:phpstan
Ce script lance une vérification du code par PHPStan en exécutant la commande :
```shell
phpstan analyse src --level=max
```
### test:twig
Ce script lance une vérification du code par Twig CS Fixer en exécutant la commande :
```shell
vendor/bin/twig-cs-fixer lint
```
### fix:twig
Ce script lance une correction du code par Twig CS Fixer en exécutant la commande :
```shell
vendor/bin/twig-cs-fixer lint --fix
```
### test:yaml
Ce script lance une vérification des fichiers YAML en exécutant la commande :
```shell
php bin/console lint:yaml config
```
### test
Ce script lance l'ensemble des scripts de vérification :
```shell
@test:csfixer
@test:phpstan
@test:twig
@test:yaml
```
### db
Ce script lance les commandes suivantes pour forcer la suppression de la base de donnée, créer la base de donnée, applique les migration et charge les données factices :
```shell
php bin/console doctrine:database:drop --force --if-exists
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```

## Identifiants
### Base de données
Voici l'identifiants utilisé dans le projet pour se connecter à la base de données __PostgreSQL__ :
```
Serveur : database
Utilisateur : test
Mot de passe : test
Base de données : sae5
```
La base de données est disponible à l'URL : http://localhost:7080

