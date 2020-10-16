# Projet Web

Dépôt Git du Projet Web du CESI, qui consiste à créer une solution Web de gestion de locations de véhicules.

## Dépendances

Avant de pouvoir manipuler ce projet, il est nécessaire d'installer [Symfony](https://symfony.com/download), [Node.js](https://nodejs.org/fr/) et [Yarn](https://classic.yarnpkg.com/en/docs/install/#windows-stable) sur votre ordinateur, qui seront requis pour pouvoir utiliser le serveur local du site.

## Installation

```bash
git clone https://github.com/InFinity54/CESI_ProjetWeb.git cesi_projetweb
cd cesi_projetweb
composer install
yarn install
```

Voir la section "Mise en production" pour une installation sur un serveur de production. En cas d'erreur après l'installation, voir la section "Erreurs connues".

## Démarrage

```bash
cd cesi_projetweb
symfony server:start --no-tls
yarn encore dev --watch
```

En cas d'erreur après l'installation, voir la section "Erreurs connues".

## Mise en production

```bash
git clone https://github.com/InFinity54/CESI_ProjetWeb.git cesi_projetweb
cd cesi_projetweb
composer install --no-dev --optimize-autoloader
yarn install
yarn encore production
```

Il faudra aussi modifier le fichier _.env_ pour mettre la valeur de la variable _APP_ENV_ à _prod_. Le serveur de Symfony n'est plus nécessaire en environnement de production. En cas d'erreur lors de l'installation ou de l'utilisation, voir la section "Erreurs connues".

## Erreurs connues
### Unrecognized options "dir_name, namespace" under "doctrine_migrations"

```bash
cd cesi_projetweb
composer recipes:install --force -v
composer install
```

### The local web server is already running

```bash
symfony local:server:stop
```

Cette commande doit être executée dans un invite de commande lancé en mode administrateur pour fonctionner.
