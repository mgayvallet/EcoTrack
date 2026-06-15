<?php

namespace MVC\Models;

class CarbonCalculator
{
    private const FACTEUR_VEHICULE = [
        'electrique'           => 0.02,
        'hybride'              => 0.11,
        'essence_diesel_moyen' => 0.19,
        'gros_suv'             => 0.28,
    ];

    private const FACTEUR_CHAUFFAGE = [
        'pompe_a_chaleur'     => 5,
        'fioul_ou_gaz'        => 35,
        'electrique_resistif' => 15,
        'bois'                => 8,
    ];

    private const BASE_REGIME = [
        'vegan'                    => 1000,
        'vegetarien'               => 1500,
        'peu_de_viande'            => 2000,
        'omnivore_moyen'           => 2500,
        'gros_consommateur_viande' => 3500,
    ];

    private const FACTEUR_PART_LOCALE = [
        'quasi_100'  => 0.85,
        'plutot_oui' => 0.92,
        'environ_50' => 1.0,
        'peu_ou_pas' => 1.08,
    ];

    public function calculate(array $answers): array
    {
        $num = fn($key) => (float) ($answers[$key] ?? 0);
        $choice = fn($key) => $answers[$key] ?? '';

        $facteurVehicule = self::FACTEUR_VEHICULE[$choice('type_vehicule')] ?? 0.19;
        $transport =
            $num('km_voiture_par_an') * $facteurVehicule +
            $num('vols_courts_par_an') * 250 +
            $num('vols_longs_par_an') * 1500 +
            $num('km_train_par_an') * 0.014;

        $facteurChauffage = self::FACTEUR_CHAUFFAGE[$choice('mode_chauffage')] ?? 15;
        $logement =
            $num('surface_m2') * $facteurChauffage +
            $num('conso_electrique_kwh') * 0.06;

        $baseRegime = self::BASE_REGIME[$choice('regime_alimentaire')] ?? 2500;
        $facteurPartLocale = self::FACTEUR_PART_LOCALE[$choice('part_locale_saison')] ?? 1.0;
        $alimentation = $baseRegime * $facteurPartLocale;

        $numerique =
            $num('vetements_neufs_par_an') * 25 +
            $num('appareils_electroniques_par_an') * 200 +
            $num('heures_streaming_par_jour') * 20;

        $breakdown = [
            'transport'        => (int) round($transport),
            'logement'         => (int) round($logement),
            'alimentation'     => (int) round($alimentation),
            'achats_numerique' => (int) round($numerique),
        ];

        return [
            'breakdown' => $breakdown,
            'total'     => array_sum($breakdown),
        ];
    }
}
