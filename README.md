# 🌍 EcoTrack - Plateforme de Suivi et Réduction de l'Empreinte Carbone

EcoTrack est une application web éducative et engageante conçue pour aider les citoyens à calculer, comprendre et réduire leur empreinte carbone. À travers un système de quiz interactif et de défis quotidiens, l'application transforme la démarche écologique en une expérience ludique et personnalisée.

---

## 📋 Table des Matières

- [Présentation](#-présentation)
- [Fonctionnalités](#-fonctionnalités)
- [Technologies](#-technologies)
- [Architecture](#-architecture)
- [Installation](#-installation)
- [Structure du Projet](#-structure-du-projet)
- [Base de Données](#-base-de-données)
- [API](#-api)
- [Utilisation](#-utilisation)
- [Contribuer](#-contribuer)
- [Licence](#-licence)

---

## 🎯 Présentation

EcoTrack permet aux utilisateurs de :

1. **Calculer leur empreinte carbone** via un questionnaire détaillé couvrant 4 catégories :
   - Transport (véhicule, vols, train)
   - Logement (chauffage, consommation électrique, surface)
   - Alimentation (régime, produits locaux)
   - Achats numériques (vêtements, appareils, streaming)

2. **Recevoir des défis personnalisés** basés sur leur empreinte :
   - Défis quotidiens adaptés aux postes les plus émetteurs
   - Système de rotation intelligente pour varier les défis chaque jour
   - Différents niveaux de difficulté (facile, intermédiaire, difficile)

3. **Suivre leur progression** :
   - Points gagnés pour chaque défi complété
   - Statistiques de CO2 économisé
   - Série de jours consécutifs (streak)
   - Historique des défis validés

---

## ✨ Fonctionnalités

### 🔢 Calculateur d'Empreinte Carbone

Le calculateur utilise des facteurs scientifiques pour estimer l'empreinte carbone totale de l'utilisateur :

```
Empreinte Totale = Transport + Logement + Alimentation + Achats Numériques
```

**Facteurs utilisés :**
- **Transport** : Coefficients selon type de véhicule (0.02-0.28 kg CO2/km) + vols courts (250 kg) et longs (1500 kg)
- **Logement** : Facteurs de chauffage (5-35 kWh) + consommation électrique (0.06 kg CO2/kWh)
- **Alimentation** : Bases selon régime (1000-3500 kg CO2/an) × facteur localisation
- **Achats numériques** : Vêtements (25 kg/article), appareils (200 kg/appareil), streaming (20 kg/h)

### 🎮 Système de Défis

**24 défis disponibles** répartis par catégorie et difficulté :

| Difficulté | Points | CO2 Économisé | Exemple |
|------------|--------|---------------|---------|
| Facile | 10-25 | 0.01-1.5 kg | Un repas végétarien |
| Intermédiaire | 40-50 | 0.8-3.5 kg | Baisser chauffage de 1°C |
| Difficile | 80-100 | 2.5-50 kg | Zéro avion aujourd'hui |

**Algorithmes avancés :**
- **Ciblage intelligent** : Les défis du jour ciblent automatiquement les postes les plus émetteurs de l'empreinte
- **Rotation quotidienne** : Les défis changent chaque jour selon le jour de l'année pour une variété continue
- **Priorisation** : Les catégories avec le plus haut impact sont proposées en priorité

### 📊 Suivi et Statistiques

**Tableau de bord utilisateur :**
- Nombre total de défis complétés
- CO2 total économisé
- Points accumulés
- Série de jours consécutifs (streak)
- Défis complétés aujourd'hui
- Historique personnel des défis

### 🎨 Interface Utilisateur

- **Page d'accueil** : Présentation de l'application
- **Calculateur** : Questionnaire interactif avec sliders et choix multiples
- **Résultats** : Visualisation de l'empreinte par catégorie
- **Défis** : Affichage des défis du jour avec filtres par difficulté
- **Contact** : Formulaire de contact
- **Responsive** : Interface adaptée mobile, tablette et desktop

---

## 🛠️ Technologies

### Backend

| Technologie | Version | Description |
|------------|---------|-------------|
| PHP | 8.0+ | Langage de programmation côté serveur |
| MVC Custom | - | Architecture MVC personnalisée (Router, Controller, Model) |
| PDO | - | Extension PHP Data Objects pour MySQL |
| PHPMailer | 6.x | Envoi d'emails (futur usage pour notifications) |

### Frontend

| Technologie | Description |
|------------|-------------|
| HTML5 | Structure des pages |
| CSS3 | Style et mise en page |
| JavaScript (ES6+) | Interactivité et logique client |
| AJAX | Communication asynchrone |

### Base de Données

| Technologie | Description |
|------------|-------------|
| MySQL | Système de gestion de base de données |
| UTF8MB4 | Encodage supportant toutes les caractères Unicode |

### Dépendances (Composer)

```json
{
    "require": {
        "phpmailer/phpmailer": "^6.9"
    }
}
```

---

## 🏗️ Architecture

L'application suit une **architecture MVC personnalisée** :

```
┌─────────────────────────────────────────────────────────────┐
│                     Presentation Layer (Views)               │
│  - homepage.php, calculator.php, challenge.php,              │
│    result.php, contact.php, layout/*.php                     │
├─────────────────────────────────────────────────────────────┤
│                    Application Layer (Controllers)           │
│  - HomeController     : Gère les pages principales           │
│  - UserController    : Gère l'authentification utilisateur   │
├─────────────────────────────────────────────────────────────┤
│                     Domain Layer (Models)                    │
│  - CarbonCalculator  : Calcul scientifique de l'empreinte    │
│  - DefiManager       : Gestion intelligente des défis        │
│  - EmpreinteManager  : Sauvegarde des empreintes              │
│  - QuestionManager   : Gestion du questionnaire              │
│  - UserManager       : Authentification et sessions          │
├─────────────────────────────────────────────────────────────┤
│                     Infrastructure Layer                     │
│  - Router            : Routage HTTP et redirection           │
│  - Validator         : Validation des données utilisateur    │
│  - Helper            : Fonctions utilitaires                 │
└─────────────────────────────────────────────────────────────┘
```

### Flux Typique

1. **Utilisateur visite le site** → Router détermine la route
2. **Controller appelle le Model approprié** → Récupération des données
3. **Model exécute les requêtes SQL** → Interrogation de la BDD
4. **Controller prépare les données** → Traitement et formatage
5. **View rend la réponse** → Affichage HTML avec les données

### Routage

```php
// Exemple de définition de route
$router->get('/calculator', [HomeController::class, 'showCalculatorPage']);
$router->post('/calculate', [HomeController::class, 'calculate']);
```

---

## 📦 Installation

### Prérequis

```bash
# Vérification des dépendances
php -v        # PHP 8.0 ou supérieur
composer -v   # Composer pour les dépendances PHP
mysql --version # MySQL 5.7+ ou MariaDB 10.3+
```

### 1. Clonage du Repository

```bash
git clone https://github.com/votre-repo/ecotrack.git
cd ecotrack
```

### 2. Installation des Dépendances PHP

```bash
composer install
```

### 3. Configuration de la Base de Données

**Option A : Importer le fichier SQL**
```bash
mysql -u root -p ecotrack < database.sql
```

**Option B : Création manuelle**
```sql
CREATE DATABASE ecotrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecotrack;
-- Exécuter les CREATE TABLE et INSERT du fichier database.sql
```

### 4. Configuration

Créer le fichier de configuration :

```bash
cp src/config/config.example.php src/config/config.php
```

Modifier `src/config/config.php` :

```php
<?php
// Configuration de la base de données
define('HOST', 'localhost');
define('DATABASE', 'ecotrack');
define('USER', 'root');
define('PASSWORD', 'mot_de_passe');

// Configuration SMTP (optionnel, pour PHPMailer)
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'votre@email.com');
define('SMTP_PASSWORD', 'votre_mot_de_passe');
define('SMTP_FROM', 'noreply@example.com');
define('SMTP_FROM_NAME', 'EcoTrack');

// Configuration de l'application
define('BASE_URL', 'http://localhost/ecotrack/');
define('VIEWS', __DIR__ . '/../Views/');
```

### 5. Configuration du Serveur Web

#### Apache (.htaccess)

Placer dans le dossier `public/` :

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
```

#### Nginx (ex: /etc/nginx/sites-available/ecotrack)

```nginx
server {
    listen 80;
    server_name ecotrack.local;
    root /var/www/ecotrack/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 6. Lancement en Développement

**Option A : Serveur PHP intégré (recommandé pour dev)**
```bash
php -S localhost:8000 -t public/
```
Accéder via : http://localhost:8000

**Option B : Apache avec PHP-FPM**
```bash
# Démarrer les services
sudo systemctl start apache2
sudo systemctl start php8.0-fpm
```

---

## 📁 Structure du Projet

```
ecotrack/
├── public/                    # Dossier racine accessible (web root)
│   ├── index.php             # Point d'entrée unique
│   └── .htaccess             # Configuration Apache
├── src/                      # Code source de l'application
│   ├── Controllers/          # Contrôleurs MVC
│   │   ├── BaseController.php
│   │   ├── HomeController.php
│   │   └── UserController.php
│   ├── Models/               # Modèles et gestionnaires
│   │   ├── CarbonCalculator.php
│   │   ├── DefiManager.php
│   │   ├── EmpreinteManager.php
│   │   ├── QuestionManager.php
│   │   ├── UserManager.php
│   │   └── User.php
│   ├── Views/                # Templates HTML
│   │   ├── App/              # Pages principales
│   │   ├── Auth/             # Pages d'authentification
│   │   ├── Emails/           # Templates d'emails
│   │   └── layout/           # Layouts communs
│   ├── Router.php            # Système de routage
│   ├── Validator.php         # Validation des données
│   └── Helper.php            # Fonctions utilitaires
│
├── config/                   # Configuration
│   ├── config.example.php
│   └── config.php
│
├── database.sql              # Script SQL complet (structure + données)
├── composer.json             # Dépendances PHP
├── composer.lock             # Version des dépendances
├── .gitignore                # Fichiers à ignorer par Git
└── README.md                 # Ce fichier
```

---

## 🗄️ Base de Données

### Schéma des Tableaux

La base de données `ecotrack` contient 7 tables principales :

#### 1. `users` - Utilisateurs

| Colonne | Type | Description |
|---------|------|-------------|
| id | INT | Identifiant unique |
| name | VARCHAR(255) | Nom complet |
| email | VARCHAR(255) | Email unique |
| password | VARCHAR(255) | Hash bcrypt |
| created_at | TIMESTAMP | Date d'inscription |
| updated_at | TIMESTAMP | Dernière mise à jour |

#### 2. `empreintes` - Empreintes Carbone

| Colonne | Type | Description |
|---------|------|-------------|
| id | INT | Identifiant unique |
| user_id | INT | Référence utilisateur |
| empreinte_carbone | INT | Total CO2 (kg) |
| empreinte_transport | INT | CO2 du transport |
| empreinte_logement | INT | CO2 du logement |
| empreinte_alimentation | INT | CO2 de l'alimentation |
| empreinte_achat_numerique | INT | CO2 des achats |
| created_at | TIMESTAMP | Date du calcul |

#### 3. `defis` - Défis

| Colonne | Type | Description |
|---------|------|-------------|
| id | INT | Identifiant unique |
| titre | VARCHAR(150) | Titre du défi |
| description | TEXT | Description détaillée |
| difficulte_id | INT | Référence niveau de difficulté |
| type_defi_id | INT | Référence catégorie |
| points | INT | Points récompense |
| co2_economise | DECIMAL(8,2) | CO2 économisé |
| actif | TINYINT | État du défi |

#### 4. `defis_completes` - Historique des Défis

| Colonne | Type | Description |
|---------|------|-------------|
| id | INT | Identifiant unique |
| user_id | INT | Référence utilisateur |
| defi_id | INT | Référence défi |
| completed_on | DATE | Date de complétion |
| completed_at | TIMESTAMP | Heure de complétion |

#### 5. `questions` - Questions du Questionnaire

| Colonne | Type | Description |
|---------|------|-------------|
| id | INT | Identifiant unique |
| categorie | ENUM | transport, logement, alimentation, achats_numerique |
| code | VARCHAR(64) | Code unique |
| libelle | VARCHAR(255) | Texte de la question |
| aide | VARCHAR(255) | Aide/contexte |
| type | ENUM | slider, choix |
| valeur_min/max | FLOAT | Plage de valeurs |
| unite | VARCHAR(16) | Unité de mesure |
| ordre | INT | Ordre d'affichage |

#### 6. `question_options` - Options des Questions

| Colonne | Type | Description |
|---------|------|-------------|
| id | INT | Identifiant unique |
| question_id | INT | Référence question |
| valeur | VARCHAR(64) | Valeur sélectionnée |
| libelle | VARCHAR(128) | Texte de l'option |
| ordre | INT | Ordre d'affichage |

#### 7. `difficultes` et `types_defi`

- **difficultes** : facile, intermédiaire, difficile
- **types_defi** : logement, nourriture, transport, numerique

---

## 🔌 API

L'application utilise une architecture MVC classique plutôt qu'une API RESTful, mais voici les endpoints internes :

### Routes Principales

| Méthode | Route | Controller | Description |
|---------|-------|------------|-------------|
| GET | `/` | HomeController::index | Page d'accueil |
| GET | `/calculator` | HomeController::showCalculatorPage | Page du calculateur |
| POST | `/calculate` | HomeController::calculate | Calcul de l'empreinte |
| GET | `/challenge` | HomeController::showChallengePage | Page des défis |
| POST | `/validate/{id}` | HomeController::validateChallenge | Validation défi |
| GET | `/contact` | HomeController::showContactPage | Page contact |

### Communication Intérieure

Les contrôleurs appellent les modèles qui exécutent des requêtes SQL via PDO :

```php
// Exemple : Obtention des défis du jour
$defis = $defiManager->getDailyDefis($userId);

// Exemple : Calcul de l'empreinte
$result = $calculator->calculate($_POST);
```

---

## 🚀 Utilisation

### Pour les Utilisateurs

1. **Calculer son empreinte** :
   - Accéder à la page du calculateur
   - Répondre aux 13 questions sur 4 catégories
   - Recevoir une estimation détaillée du CO2

2. **Recevoir des défis** :
   - Accéder à la page des défis
   - Voir les défis du jour adaptés à ses points forts
   - Valider les défis pour gagner des points

3. **Suivre sa progression** :
   - Visualiser le tableau de bord
   - Voir le CO2 total économisé
   - Suivre la série de jours consécutifs

### Pour les Développeurs

#### Ajout d'un nouveau défi

```php
// Dans database.sql ou via phpMyAdmin
INSERT INTO defis (titre, description, difficulte_id, type_defi_id, points, co2_economise, actif)
VALUES ('Nouveau défi', 'Description détaillée', 2, 3, 50, 2.50, 1);
```

#### Ajout d'une nouvelle question

```php
// Question de type slider
INSERT INTO questions (categorie, code, libelle, type, valeur_min, valeur_max, valeur_defaut, unite, ordre)
VALUES ('transport', 'nouvelle_question', 'Texte question', 'slider', 0, 100, 50, 'km', 14);

// Options si besoin
INSERT INTO question_options (question_id, valeur, libelle, ordre)
VALUES (14, 'valeur1', 'Option 1', 1);
```

---

## 🤝 Contribuer

1. **Fork** le projet
2. **Créer** une branche (`git checkout -b feature/nouvelle-fonction`)
3. **Commit** (`git commit -m 'Ajout de nouvelle fonctionnalité'`)
4. **Push** (`git push origin feature/nouvelle-fonction`)
5. **Ouverture** d'une Pull Request

### Bonnes Pratiques

- Suivre le style de code existant (PSR-12 recommandé)
- Ajouter des tests pour les nouvelles fonctionnalités
- Documenter les changements dans le README
- Mettre à jour le fichier CHANGELOG si applicable

---

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour les détails.

---

## 📞 Contact

Pour toute question ou suggestion :

- **GitHub Issues** : [Ouvrir une issue](https://github.com/votre-repo/ecotrack/issues)
- **Email** : [contact@example.com](mailto:contact@example.com)

---

## 🙏 Remerciements

- **PHPMailer** : Pour la gestion des emails
- **Chart.js** : Pour la visualisation des données (utilisé dans les futures versions)
- **Toute l'équipe de développement** : Merci pour votre contribution !

---

## 📊 Stats du Projet

- **Lignes de code** : ~1500+
- **Défis** : 24 (par catégorie et difficulté)
- **Questions** : 13 dans le questionnaire principal
- **Base de données** : UTF8MB4, normalisée en 3NF

---

<div align="center">

**🌱 Ensemble pour une planète plus verte !**

*Développé avec ❤️ pour l'environnement*

</div>
