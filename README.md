# Projet Web

Dépôt Git du Projet Web du CESI.

## Installation

```bash
git clone https://github.com/InFinity54/CESI_ProjetWeb.git cesi_projetweb
cd cesi_projetweb
composer install
```

Voir la section "Mise en production" pour une installation sur un serveur de production. En cas d'erreur après l'installation, voir la section "Erreurs connues".

## Démarrage

```bash
cd cesi_projetweb
symfony server:start --no-tls
```

En cas d'erreur après l'installation, voir la section "Erreurs connues".

## Mise en production

```bash
git clone https://github.com/InFinity54/CESI_ProjetWeb.git cesi_projetweb
cd cesi_projetweb
composer install --no-dev --optimize-autoloader
```
Il faudra aussi modifier le fichier _.env_ pour mettre la valeur de la variable _APP_ENV_ à _prod_. Il n'est pas nécessaire de démarrer le serveur de Symfony lorsque l'on est en environnement de production.
En cas d'erreur lors de l'installation ou de l'utilisation, voir la section "Erreurs connues".

## Erreurs connues
### Unrecognized options "dir_name, namespace" under "doctrine_migrations"

Erreur complète :
```html
Unrecognized options "dir_name, namespace" under "doctrine_migrations". Available options are "all_or_nothing", "check_database_platform", "connection", "custom_template", "em", "migrations", "migrations_paths", "name", "organize_migrations", "services", "storage"
```

Solution pour corriger l'erreur :
```bash
composer recipes:install --force -v
composer install
```

### The local web server is already running

Solution pour corriger l'erreur :
```bash
symfony local:server:stop
```
