# SAÉ 5.Real.01 - Développement avancé

## Auteurs : 
- Dylan Bonnevie
- Mathis Claverie
- Antoine Le Gros
- Romain Daunat
- Louis Rattanavong
- Erwan Lecomte

## Présentation du projet

Notre projet consiste à mettre en place un site internet consacré à l’IUT informatique de Reims.
Il permet d’un côté à l’administration d’affecter les professeurs du département aux modules de ce dernier, et d’un autre côté aux professeurs de consulter les matières auxquelles ils ont été affectés.

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
docker compose up -d
```

## Base de données

La base de données est disponible à l'URL : http://localhost:7080

### Identifiants
Voici l'identifiant utilisé dans le projet pour se connecter à la base de données __PostgreSQL__ :
```
Serveur : database
Utilisateur : test
Mot de passe : test
Base de données : sae5
```

## Serveur web local

Lancez le serveur Web local avec cette commande :
```shell
composer start
```
### Accès au serveur Web symfony

Naviguez alors à partir de cette adresse : <http://127.0.0.1:8000>

### Accès à l'API

Naviguez alors à partir de cette adresse : <http://127.0.0.1:8000/api>

### React
Transpiler les ressources front avec Babel
```shell
npm run build
```

Transpiler les ressources front avec Babel en mode watch
```shell
npm run dev
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
Ce script lance une vérification des tests codeception en exécutant la commande :
```shell
php bin/console lint:yaml config
```

### test:codecept
Ce script lance une vérification des fichiers YAML en exécutant la commande :
```shell
php vendor/bin/codecept run
```

### test
Ce script lance l'ensemble des scripts de vérification :
```shell
@test:csfixer
@test:phpstan
@test:twig
@test:yaml
@test:codecept
```
### db
Ce script lance les commandes suivantes pour forcer la suppression de la base de donnée, créer la base de donnée, applique les migration et charge les données factices :
```shell
php bin/console doctrine:database:drop --force --if-exists
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```

### db:type
Ce script lance les commandes suivantes pour forcer la suppression de la base de donnée, créer la base de donnée, applique les migration et charge les données des types :
```shell
php bin/console doctrine:database:drop --force --if-exists
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --group=typeCourse --no-interaction
```

### db:user
Ce script lance la commande suivante. Elle charge les données des utilisateurs sans toucher aux autres données:
```shell
php bin/console doctrine:fixtures:load --group=user --append
```

### sass
Ce script lance la commande suivante.  elle lance la compilation dynamique des fichiers Sass
```shell
php bin/console sass:build --watch
```
