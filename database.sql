CREATE DATABASE IF NOT EXISTS EcoTrack;
USE EcoTrack;

CREATE TABLE IF NOT EXISTS users (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(255) NOT NULL,
    email       VARCHAR(255) UNIQUE NOT NULL,
    password    VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS responses (
    id                   INT PRIMARY KEY AUTO_INCREMENT,
    user_id              INT NOT NULL,
    submitted_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    score_total_kgeqco2  FLOAT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_responses_user_id (user_id),
    INDEX idx_responses_submitted_at (submitted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS transport (
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    response_id         INT NOT NULL,
    km_voiture_par_an   INT NOT NULL DEFAULT 12000
                            CHECK (km_voiture_par_an  BETWEEN 0 AND 40000),
    type_vehicule       ENUM(
                            'electrique',
                            'hybride',
                            'essence_diesel_moyen',
                            'gros_suv'
                        ) NOT NULL,
    vols_courts_par_an  INT NOT NULL DEFAULT 0
                            CHECK (vols_courts_par_an BETWEEN 0 AND 20),
    vols_longs_par_an   INT NOT NULL DEFAULT 0
                            CHECK (vols_longs_par_an  BETWEEN 0 AND 10),
    km_train_par_an     INT NOT NULL DEFAULT 0
                            CHECK (km_train_par_an    BETWEEN 0 AND 30000),
    FOREIGN KEY (response_id) REFERENCES responses(id) ON DELETE CASCADE,
    UNIQUE KEY uq_transport_response (response_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS logement (
    id                    INT PRIMARY KEY AUTO_INCREMENT,
    response_id           INT NOT NULL,
    surface_m2            INT NOT NULL
                              CHECK (surface_m2 BETWEEN 10 AND 250),
    mode_chauffage        ENUM(
                              'pompe_a_chaleur',
                              'fioul_ou_gaz',
                              'electrique_resistif',
                              'bois'
                          ) NOT NULL,
    conso_electrique_kwh  INT NOT NULL DEFAULT 4500
                              CHECK (conso_electrique_kwh BETWEEN 500 AND 20000),
    FOREIGN KEY (response_id) REFERENCES responses(id) ON DELETE CASCADE,
    UNIQUE KEY uq_logement_response (response_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS alimentation (
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    response_id         INT NOT NULL,
    regime_alimentaire  ENUM(
                            'vegan',
                            'vegetarien',
                            'peu_de_viande',
                            'omnivore_moyen',
                            'gros_consommateur_viande'
                        ) NOT NULL,
    part_locale_saison  ENUM(
                            'quasi_100',
                            'plutot_oui',
                            'environ_50',
                            'peu_ou_pas'
                        ) NOT NULL,
    FOREIGN KEY (response_id) REFERENCES responses(id) ON DELETE CASCADE,
    UNIQUE KEY uq_alimentation_response (response_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Achats & numérique ────────────────────────────────────────
CREATE TABLE IF NOT EXISTS achats_numerique (
    id                             INT PRIMARY KEY AUTO_INCREMENT,
    response_id                    INT NOT NULL,
    vetements_neufs_par_an         INT   NOT NULL DEFAULT 0
                                       CHECK (vetements_neufs_par_an         BETWEEN 0 AND 80),
    appareils_electroniques_par_an INT   NOT NULL DEFAULT 0
                                       CHECK (appareils_electroniques_par_an BETWEEN 0 AND 10),
    heures_streaming_par_jour      FLOAT NOT NULL DEFAULT 0
                                       CHECK (heures_streaming_par_jour      BETWEEN 0 AND 12),
    FOREIGN KEY (response_id) REFERENCES responses(id) ON DELETE CASCADE,
    UNIQUE KEY uq_achats_response (response_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS questions (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    categorie      ENUM('transport','logement','alimentation','achats_numerique') NOT NULL,
    code           VARCHAR(64)  NOT NULL,
    libelle        VARCHAR(255) NOT NULL,
    aide           VARCHAR(255) NULL,
    type           ENUM('slider','choix') NOT NULL,
    valeur_min     FLOAT NULL,
    valeur_max     FLOAT NULL,
    valeur_defaut  FLOAT NULL,
    pas            FLOAT NULL DEFAULT 1,
    unite          VARCHAR(16) NULL,
    ordre          INT NOT NULL,
    UNIQUE KEY uq_questions_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS question_options (
    id           INT PRIMARY KEY AUTO_INCREMENT,
    question_id  INT NOT NULL,
    valeur       VARCHAR(64)  NOT NULL,
    libelle      VARCHAR(128) NOT NULL,
    ordre        INT NOT NULL,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    UNIQUE KEY uq_option (question_id, valeur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO questions
    (id, categorie, code, libelle, aide, type, valeur_min, valeur_max, valeur_defaut, pas, unite, ordre) VALUES
    ( 1, 'transport',        'km_voiture_par_an',              'Combien de km faites-vous en voiture par an ?',                          NULL,                            'slider',    0, 40000, 12000, 100, 'km',       1),
    ( 2, 'transport',        'type_vehicule',                  'Quel type de vehicule conduisez-vous ?',                                 NULL,                            'choix',  NULL,  NULL,  NULL, NULL, NULL,       2),
    ( 3, 'transport',        'vols_courts_par_an',             'Combien de vols courts faites-vous par an ?',                            'Moins de 2h, ex. Paris-Lyon',   'slider',    0,    20,     0,   1, 'vols',     3),
    ( 4, 'transport',        'vols_longs_par_an',              'Combien de vols longs faites-vous par an ?',                             'Plus de 6h, intercontinental',  'slider',    0,    10,     0,   1, 'vols',     4),
    ( 5, 'transport',        'km_train_par_an',                'Combien de km faites-vous en train par an ?',                            NULL,                            'slider',    0, 30000,     0, 100, 'km',       5),
    ( 6, 'logement',         'surface_m2',                     'Quelle est la surface de votre logement ?',                              NULL,                            'slider',   10,   250,    70,   1, 'm2',       6),
    ( 7, 'logement',         'mode_chauffage',                 'Quel est votre mode de chauffage principal ?',                           NULL,                            'choix',  NULL,  NULL,  NULL, NULL, NULL,       7),
    ( 8, 'logement',         'conso_electrique_kwh',           'Quelle est votre consommation electrique annuelle ?',                    'Moyenne francaise : 4 500 kWh', 'slider',  500, 20000,  4500, 100, 'kWh',      8),
    ( 9, 'alimentation',     'regime_alimentaire',             'Quelle est votre alimentation principale ?',                             NULL,                            'choix',  NULL,  NULL,  NULL, NULL, NULL,       9),
    (10, 'alimentation',     'part_locale_saison',             'Quelle part de votre alimentation est locale et de saison ?',            NULL,                            'choix',  NULL,  NULL,  NULL, NULL, NULL,      10),
    (11, 'achats_numerique', 'vetements_neufs_par_an',         'Combien d''articles vestimentaires neufs achetez-vous par an ?',         'Vetements, chaussures',         'slider',    0,    80,     0,   1, 'articles', 11),
    (12, 'achats_numerique', 'appareils_electroniques_par_an', 'Combien d''appareils electroniques neufs achetez-vous par an ?',         'Telephone, ordi, TV...',        'slider',    0,    10,     0,   1, 'appareils',12),
    (13, 'achats_numerique', 'heures_streaming_par_jour',      'Combien d''heures par jour passez-vous sur du streaming ou internet ?',  NULL,                            'slider',    0,    12,     0, 0.5, 'h/jour',   13);

INSERT INTO question_options (question_id, valeur, libelle, ordre) VALUES
    (2, 'electrique',               'Electrique',                  1),
    (2, 'hybride',                  'Hybride',                     2),
    (2, 'essence_diesel_moyen',     'Essence-diesel moyen',        3),
    (2, 'gros_suv',                 'Gros SUV',                    4),
    (7, 'pompe_a_chaleur',          'Pompe a chaleur',             1),
    (7, 'fioul_ou_gaz',             'Fioul ou gaz',                2),
    (7, 'electrique_resistif',      'Electrique resistif',         3),
    (7, 'bois',                     'Bois',                        4),
    (9, 'vegan',                    'Vegan',                       1),
    (9, 'vegetarien',               'Vegetarien',                  2),
    (9, 'peu_de_viande',            'Peu de viande',               3),
    (9, 'omnivore_moyen',           'Omnivore moyen',              4),
    (9, 'gros_consommateur_viande', 'Gros consommateur de viande', 5),
    (10, 'quasi_100',               'Quasi 100%',                  1),
    (10, 'plutot_oui',              'Plutot oui',                  2),
    (10, 'environ_50',              'Environ 50%',                 3),
    (10, 'peu_ou_pas',              'Peu ou pas',                  4);
