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

## Lancement Rapide

### Commandes

#### Installation des dépendances
```shell
composer install
```
```shell
npm install
```
#### Construction et lancement
```shell
composer start
```
```shell
npm run dev
```
```shell
docker compose up -d
```
```shell
composer sass
```
#### Initialisation de la bd
```shell
composer db:dev
```

#### Fichier Excel d'une année

[Téléchargement du fichier](excel/edt_file.xlsx)

Se trouve à l'emplacement `excel/edt_file.xlsx`

### Base de données

La base de données est disponible à l'URL : http://localhost:7080

Voici l'identifiant utilisé dans le projet pour se connecter à la base de données __PostgreSQL__ :
```
Serveur : database
Utilisateur : test
Mot de passe : test
Base de données : sae5
```

### Accès au serveur Web symfony

Naviguez alors à partir de cette adresse : <http://127.0.0.1:8000>

### Accès à l'API

Naviguez alors à partir de cette adresse : <http://127.0.0.1:8000/api>

### Identifiants de connexions

> #### Professeur
> Identifiant : ```agregate@example.com```  
> Mot de passe : ```test```

> #### Administration
> Identifiant : ```admin@example.com```  
> Mot de passe : ```test```

> #### SuperAdmin
> Identifiant : ```superadmin@example.com```  
> Mot de passe : ```pass```

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
Ce script lance une vérification du code par PHPStan en exécutant les commandes :
```shell
php vendor/bin/codecept build,
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
Ce script lance une vérification des tests YAML en exécutant la commande :
```shell
php bin/console lint:yaml config
```

### test:codecept
Ce script lance une vérification des fichiers codeception en exécutant la commande :
```shell
php vendor/bin/codecept clean,
APP_ENV=test php bin/console doctrine:database:drop --force,
APP_ENV=test php bin/console doctrine:database:create,
APP_ENV=test php bin/console doctrine:schema:create --quiet,
php vendor/bin/codecept run --no-artifacts
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

### db:dev
Ce script lance la commande suivante. Elle charge les données nécessaire a une utilisation en développement
```shell
composer db:type
composer db:user
```

### sass
Ce script lance la commande suivante.  elle lance la compilation dynamique des fichiers Sass
```shell
php bin/console sass:build --watch
```

## Rôles

### Anonyme
Ce rôle est attribué à un utilisateur qui n'est pas connecté à l'application. Il ne possède pas d'accès aux pages d'historique
et ne peut pas consulter l'historique d'un professeur. Il peut consulter la liste de toute les matières de l'année en cours.

### Professeur
Ce rôle est attribué à un utilisateur qui a la possibilité de consulter les matières auxquelles il a été affecté. Il ne
possède pas d'accès CRUD aux entités de l'application, et ne peut pas affecter de matières à des professeurs.

### Administrateur
Ce rôle est attribué à un utilisateur qui a la possibilité de gérer les années et les professeurs. Il peut affecter des
professurs a des matières en fonction de semestre et possède tous les accès nécessairepour faire contionner l'application.
Il ne possède pas d'accès CRUD aux entres entités que Années, les Users et le système d'affectation.

### Super administrateur
Ce rôle est réservé uniquement aux développeurs de l'application. Il permet d'accèder à toutes les pages de l'application
sans exception, en plus de pouvoir accéder à la page située à la route __/admin__. Cette route est uniquement accèssible
en passant par l'URL, et si vous ne possèdez pas ce rôle, l'accès a cette route vous sera refusé.

