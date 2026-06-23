<?php

use PHPUnit\Framework\TestCase;

/**
 * Test de connexion à la base de données MySQL via PDO.
 */
class DatabaseTest extends TestCase
{
    private static ?\PDO $pdo = null;

    /**
     * Tente d'établir la connexion avant les tests.
     * Si MySQL n'est pas disponible, tous les tests de cette classe sont ignorés.
     */
    public static function setUpBeforeClass(): void
    {
        try {
            self::$pdo = new \PDO(
                'mysql:host=' . HOST . ';dbname=' . DATABASE . ';charset=utf8mb4',
                USER,
                PASSWORD,
                [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (\PDOException $e) {
            self::$pdo = null;
        }
    }

    /**
     * Ignore le test si la base de données est inaccessible.
     */
    private function skipIfNoConnection(): void
    {
        if (self::$pdo === null) {
            $this->markTestSkipped(
                'MySQL non disponible — démarrez WAMP pour exécuter les tests de base de données.'
            );
        }
    }

    /**
     * Vérifie que l'objet PDO est bien instancié.
     */
    public function testConnexionEstEtablie(): void
    {
        $this->skipIfNoConnection();

        $this->assertInstanceOf(\PDO::class, self::$pdo);
    }

    /**
     * Vérifie qu'une requête simple s'exécute sans erreur.
     */
    public function testRequeteSimpleFonctionne(): void
    {
        $this->skipIfNoConnection();

        $stmt = self::$pdo->query('SELECT 1 AS valeur');
        $row  = $stmt->fetch();

        $this->assertIsArray($row);
        $this->assertArrayHasKey('valeur', $row);
        $this->assertEquals(1, $row['valeur']);
    }

    /**
     * Vérifie que la table `users` existe dans la base de données.
     */
    public function testTableUsersExiste(): void
    {
        $this->skipIfNoConnection();

        $stmt   = self::$pdo->query("SHOW TABLES LIKE 'users'");
        $result = $stmt->fetchAll();

        $this->assertNotEmpty($result, "La table 'users' doit exister dans la base de données.");
    }

    /**
     * Vérifie que la table `defis` existe dans la base de données.
     */
    public function testTableDefisExiste(): void
    {
        $this->skipIfNoConnection();

        $stmt   = self::$pdo->query("SHOW TABLES LIKE 'defis'");
        $result = $stmt->fetchAll();

        $this->assertNotEmpty($result, "La table 'defis' doit exister dans la base de données.");
    }
}
