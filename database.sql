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