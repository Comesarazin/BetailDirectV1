```markdown
# BétailDirect

BétailDirect est une plateforme en ligne pour l'achat et la vente de bétail. Ce projet utilise Symfony pour le backend et TailwindCSS pour le frontend.

## Table des matières

- [Introduction](#introduction)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Commandes Utiles](#commandes-utiles)

## Introduction

BétailDirect est conçu pour faciliter les transactions de bétail en ligne. Il offre une interface utilisateur intuitive et des fonctionnalités robustes pour gérer les annonces de bétail.

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- PHP >= 7.4
- Composer
- Node.js et npm
- Symfony

## Installation

Suivez ces étapes pour installer et configurer le projet :

1. Clonez le dépôt :

	git clone https://github.com/Comesarazin/BetailDirectV1.git
	cd BetailDirectV1

2. Installez les dépendances PHP :

	composer install

3. Installez les dépendances JavaScript :

	npm install

## Configuration

1. Copiez le fichier `.env` pour créer un fichier `.env.local` :

	cp .env .env.local

2. Modifiez le fichier `.env.local` pour configurer vos variables d'environnement locales, telles que les informations de connexion à la base de données.

3. Créez la base de données et exécutez les migrations :

	php bin/console doctrine:database:create
	php bin/console doctrine:migrations:migrate

4. Chargez les fixtures (données de test) :

    php bin/console doctrine:fixtures:load

5. Compilez les assets front-end avec Webpack Encore :

	npm run dev

## Commandes Utiles

- Lancer le serveur de développement Symfony :

	symfony server:start

- Compiler les assets pour la production :

	npm run build

- Créer un nouvel utilisateur avec le rôle `ROLE_ADMIN` :

    php bin/console app:create-user <email> <password>
