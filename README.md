# EcoTrack

EcoTrack est une application web qui aide les particuliers à mesurer et à réduire leur empreinte carbone grâce à un système de défis quotidiens personnalisés. Les utilisateurs complètent un questionnaire multi-catégories pour obtenir une estimation annuelle en équivalent CO2, puis reçoivent des défis ciblés en fonction de leur secteur le plus émetteur.

---

## Table des matières

- [Fonctionnalités](#fonctionnalités)
- [Stack technique](#stack-technique)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Base de données](#base-de-données)
- [Lancer l'application](#lancer-lapplication)
- [Lancer les tests](#lancer-les-tests)
- [Structure du projet](#structure-du-projet)

---

## Fonctionnalités

- **Calculateur d'empreinte carbone** — Questionnaire multi-catégories couvrant le transport, le logement, l'alimentation et le numérique, avec un résultat détaillé par catégorie et un total en CO2 annuel.
- **Défis quotidiens personnalisés** — Trois défis sont proposés chaque jour en fonction de la catégorie la plus émettrice de l'utilisateur. Chaque défi est associé à un niveau de difficulté, un nombre de points et une estimation de CO2 économisé.
- **Suivi des défis** — Les utilisateurs peuvent marquer un défi comme complété une seule fois par jour ; les doublons sont automatiquement bloqués.
- **Authentification** — Inscription, connexion et réinitialisation du mot de passe par email (SMTP/Gmail).
- **Tableau de bord** — Vue d'ensemble des défis complétés et des points cumulés.

---

## Stack technique

| Couche | Technologie |
|---|---|
| Langage | PHP 8.3+ |
| Architecture | MVC personnalisé (sans framework externe) |
| Base de données | MySQL 8.4+ via PDO |
| Frontend | HTML5, CSS3, JavaScript Vanilla |
| Email | PHPMailer 7.1 (Gmail SMTP) |
| Tests | PHPUnit 9, php-invoker 3.0 |
| Gestion des dépendances | Composer |
| Serveur web | Serveur intégré PHP |

---

## Prérequis

- PHP 8.3 ou supérieur
- MySQL 8.4 ou supérieur
- Composer

---

## Installation

Cloner le dépôt et installer les dépendances PHP :

```bash
git clone https://github.com/your-org/EcoTrack.git
cd EcoTrack
composer install
```

---

## Configuration

Toute la configuration de l'application se trouve dans `src/config/config.php`. Ouvrir le fichier et renseigner les valeurs correspondant à l'environnement cible :

```php
define('DB_HOST',     'localhost');
define('DB_DATABASE', 'ecotrack');
define('DB_USER',     'votre_utilisateur');
define('DB_PASSWORD', 'votre_mot_de_passe');

define('SMTP_HOST',     'smtp.gmail.com');
define('SMTP_PORT',     587);
define('SMTP_USER',     'votre_email@gmail.com');
define('SMTP_PASSWORD', 'votre_mot_de_passe_application');
```

> Pour Gmail, générer un mot de passe d'application depuis les paramètres de sécurité du compte Google et l'utiliser comme valeur de `SMTP_PASSWORD`.

---

## Base de données

Importer le dump SQL fourni pour créer et initialiser le schéma :

```bash
mysql -u votre_utilisateur -p votre_mot_de_passe < database.sql
```

Le dump inclut le schéma complet ainsi que 24 défis pré-chargés (4 catégories x 3 niveaux de difficulté).

---

## Lancer l'application

Utiliser le serveur web intégré de PHP. Depuis la racine du projet, en pointant la racine web sur le répertoire `public/` :

```bash
php -S localhost:8000 -t public
```

L'application est ensuite accessible sur `http://localhost:8000`.

---

## Lancer les tests

Les tests unitaires et d'intégration sont écrits avec PHPUnit. Lancer la suite complète depuis la racine du projet :

```bash
./vendor/bin/phpunit
```

Les fichiers de test se trouvent dans le répertoire `test/`. Le fichier d'amorçage `test/bootstrap.php` gère l'autoloading et la configuration de l'environnement de test.

---

## Structure du projet

```
EcoTrack/
├── public/                  
│   ├── index.php            
│   ├── .htaccess            
│   ├── style/               
│   └── assets/              
├── src/
│   ├── config/
│   │   └── config.php       
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   └── UserController.php
│   ├── Models/
│   │   ├── CarbonCalculator.php
│   │   ├── DefiManager.php
│   │   ├── EmpreinteManager.php
│   │   ├── QuestionManager.php
│   │   ├── User.php
│   │   └── UserManager.php
│   ├── Views/
│   │   ├── App/             
│   │   ├── Auth/            
│   │   └── Emails/          
│   ├── Router.php
│   ├── Route.php
│   ├── Validator.php
│   └── Helper.php
├── test/                    
│   ├── bootstrap.php
│   ├── CarbonCalculatorTest.php
│   └── DatabaseTest.php
├── database.sql                 
├── composer.json
└── phpunit.xml
```