<?php

use PHPUnit\Framework\TestCase;
use MVC\Models\CarbonCalculator;

/**
 * Tests unitaires de la classe CarbonCalculator.
 * Aucune base de données n'est requise : c'est de la logique pure.
 */
class CarbonCalculatorTest extends TestCase
{
    private CarbonCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new CarbonCalculator();
    }

    /**
     * Vérifie que le résultat contient bien les clés attendues.
     */
    public function testStructureDuResultat(): void
    {
        $result = $this->calculator->calculate([]);

        $this->assertArrayHasKey('breakdown', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('transport', $result['breakdown']);
        $this->assertArrayHasKey('logement', $result['breakdown']);
        $this->assertArrayHasKey('alimentation', $result['breakdown']);
        $this->assertArrayHasKey('achats_numerique', $result['breakdown']);
    }

    /**
     * Vérifie que le total est bien la somme des postes du breakdown.
     */
    public function testTotalEgaleSommeBreakdown(): void
    {
        $answers = [
            'type_vehicule'             => 'essence_diesel_moyen',
            'km_voiture_par_an'         => 10000,
            'vols_courts_par_an'        => 2,
            'vols_longs_par_an'         => 1,
            'km_train_par_an'           => 500,
            'mode_chauffage'            => 'fioul_ou_gaz',
            'surface_m2'                => 80,
            'conso_electrique_kwh'      => 3000,
            'regime_alimentaire'        => 'omnivore_moyen',
            'part_locale_saison'        => '50',
            'vetements_neufs_par_an'    => 5,
            'appareils_electroniques_par_an' => 1,
            'heures_streaming_par_jour' => 2,
        ];

        $result = $this->calculator->calculate($answers);
        $sommBreakdown = array_sum($result['breakdown']);

        $this->assertEquals($sommBreakdown, $result['total']);
    }

    /**
     * Vérifie qu'un profil sans aucune activité donne un total > 0
     * (l'alimentation de base contribue toujours).
     */
    public function testProfilVideAUnTotalPositif(): void
    {
        $result = $this->calculator->calculate([]);

        $this->assertGreaterThan(0, $result['total']);
    }

    /**
     * Vérifie que le véhicule électrique produit moins de CO2 que le SUV.
     */
    public function testVehiculeElectriqueProduisMoinsQueSuv(): void
    {
        $base = [
            'km_voiture_par_an'  => 15000,
            'vols_courts_par_an' => 0,
            'vols_longs_par_an'  => 0,
            'km_train_par_an'    => 0,
            'mode_chauffage'     => 'pompe_a_chaleur',
            'surface_m2'         => 0,
            'conso_electrique_kwh' => 0,
            'regime_alimentaire' => 'vegan',
            'part_locale_saison' => '100',
            'vetements_neufs_par_an'         => 0,
            'appareils_electroniques_par_an' => 0,
            'heures_streaming_par_jour'      => 0,
        ];

        $electrique = $this->calculator->calculate(array_merge($base, ['type_vehicule' => 'electrique']));
        $suv        = $this->calculator->calculate(array_merge($base, ['type_vehicule' => 'gros_suv']));

        $this->assertLessThan(
            $suv['breakdown']['transport'],
            $electrique['breakdown']['transport'],
            "Le transport électrique doit émettre moins de CO2 que le SUV."
        );
    }

    /**
     * Vérifie que les valeurs de breakdown sont des entiers (arrondis).
     */
    public function testBreakdownContientDesEntiers(): void
    {
        $result = $this->calculator->calculate([
            'km_voiture_par_an' => 10000,
            'type_vehicule'     => 'hybride',
        ]);

        foreach ($result['breakdown'] as $poste => $valeur) {
            $this->assertIsInt($valeur, "Le poste '$poste' doit être un entier.");
        }
    }
}
