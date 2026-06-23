# 🏞️ EcoTrack - Plateforme de Suivi Écologique

Plateforme web moderne permettant à des utilisateurs (citoyens, ONGs et institutions) de suivre, partager et visualiser des données environnementales en temps réel.

---

##  Table des Matières

- [Caractéristiques Principales](#-caractéristiques-principales)
- [Technologies Utilisées](#️-technologies-utilisées)
- [Installation](#-installation)
- [Structure du Projet](#-structure-du-projet)
- [API Endpoints](#-api-endpoints)
- [Sécurité & Bonnes Pratiques](#-sécurité--bonnes-pratiques)

---

##  Caractéristiques Principales

### Pour les Citoyens et ONGs
- **Compte utilisateur** : Inscription et connexion avec gestion de sessions sécurisée
- **Dashboard personnalisé** : Vue adaptée selon le rôle (citoyen vs institution)
- **Suivi des données** : Ajout et visualisation de données environnementales en temps réel
- **Notifications** : Système d'alerte pour les anomalies détectées

### Pour les Administrations et ONGs
- **Gestion complète** : Création, modification et suppression de comptes utilisateurs
- **Surveillance proactive** : Visualisation des tendances avec alertes automatiques
- **Rapports exportables** : Export de données en CSV/PDF pour analyse approfondie

### Fonctionnalités Globales
-  **Visualisations interactives** : Graphiques en temps réel via Chart.js
-  **Authentification sécurisée** : JWT tokens avec expiration automatique
-  **Interface responsive** : Adaptée mobiles, tablettes et ordinateurs
-  **Performance optimisée** : Routage efficient et rendu asynchrone

---

##  Technologies Utilisées

### Backend
- **PHP 8+** — Framework MVC personnalisé (Router, Route, Controller)
- **JSON-RPC** — Interface d'API standardisée pour la communication serveur-client
- **JWT** — Gestion sécurisée des sessions avec expiration automatique

### Frontend
- **JavaScript ES6+** — Asynchrone et modulaire
- **Chart.js** — Visualisation de données environnementales
- **AJAX / Fetch API** — Communication asynchrone sans rechargement de page

### Infrastructure
- **Apache / Nginx** — Serveur web pour le déploiement
- **Redis** *(optionnel)* — Cache et gestion des sessions à haute charge

---

##  Installation

### Prérequis

```bash
php -v        # PHP 8.0 ou supérieur
composer -V   # Composer pour les dépendances PHP
node -v       # Node.js (si nécessaire)
npm -v        # NPM (si nécessaire)
```

### Clonage du Repository

```bash
git clone https://github.com/your-org/ecotrack.git
cd ecotrack
```

### Installation des Dépendances

```bash
composer install
```

### Configuration

1. Copiez le fichier de configuration :
   ```bash
   cp config/config.example.php config/config.php
   ```

2. Modifiez les paramètres dans `config/config.php` :
   - Base URL de l'application
   - Clé secrète JWT
   - Paramètres du serveur web

### Configuration du Serveur Web

**Apache** — `.htaccess` :
```apache
RewriteEngine On
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
```

**Nginx** — `nginx.conf` :
```nginx
location / {
    try_files $uri $uri/ /index.php?$args;
}
```

### Lancement en Développement

```bash
php -S localhost:8000 -t public/
```

---

##  Structure du Projet

```
EcoTrack/
├── Controllers/
│   ├── BaseController.php          # Classe de base pour tous les contrôleurs
│   └── UserController.php          # Gestion des utilisateurs
│
├── Models/
│   ├── User.php                    # Modèle utilisateur avec validation
│   └── EnvironmentData.php         # Données environnementales
│
├── Views/
│   ├── layout/
│   │   ├── header.php
│   │   └── footer.php
│   ├── errors/
│   │   └── 404.php
│   └── layout-user.php             # Layout utilisateur principal
│
├── public/
│   └── index.php                   # Point d'entrée de l'application
│
├── config/
│   ├── config.example.php
│   └── config.php
│
├── Router.php                      # Système de routage HTTP et JSON-RPC
├── composer.json
└── README.md
```

---

##  API Endpoints

L'API utilise le protocole **JSON-RPC** pour toutes les communications client-serveur.

### Authentification

| Méthode | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/auth/register` | Inscription d'un nouvel utilisateur |
| `POST` | `/api/auth/login` | Connexion et obtention du JWT |
| `POST` | `/api/auth/logout` | Déconnexion et invalidation de session |

### Utilisateurs

| Méthode | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/users` | Liste des utilisateurs *(admin)* |
| `GET` | `/api/users/{id}` | Détail d'un utilisateur |
| `PUT` | `/api/users/{id}` | Modification d'un utilisateur |
| `DELETE` | `/api/users/{id}` | Suppression d'un utilisateur *(admin)* |

### Données Environnementales

| Méthode | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/data` | Liste des données environnementales |
| `POST` | `/api/data` | Ajout d'une nouvelle entrée |
| `GET` | `/api/data/{id}` | Détail d'une entrée |
| `DELETE` | `/api/data/{id}` | Suppression d'une entrée |

### Rapports

| Méthode | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/reports/csv` | Export des données en CSV |
| `GET` | `/api/reports/pdf` | Export des données en PDF |

---

##  Sécurité & Bonnes Pratiques

### Authentification JWT
- Les tokens JWT expirent automatiquement après une durée configurable
- Chaque requête authentifiée doit inclure le header `Authorization: Bearer <token>`
- Les clés secrètes ne doivent jamais être versionnées (utiliser `.env` ou `config.php` ignoré par Git)

### Protection des Données
- Toutes les entrées utilisateur sont validées et échappées côté serveur
- Les mots de passe sont hashés via `password_hash()` (bcrypt)
- Les sessions sont régénérées à chaque connexion pour prévenir la fixation de session

### Bonnes Pratiques Git
```bash
# Ne jamais committer les fichiers sensibles
echo "config/config.php" >> .gitignore
echo ".env" >> .gitignore
```

---

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.