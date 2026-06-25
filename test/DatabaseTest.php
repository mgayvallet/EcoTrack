<?php

use PHPUnit\Framework\TestCase;

// Test de connexion à la base de données MySQL via PDO.
class DatabaseTest extends TestCase
{
    private static ?\PDO $pdo = null;

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

    private function skipIfNoConnection(): void
    {
        if (self::$pdo === null) {
            $this->markTestSkipped(
                'MySQL non disponible — démarrez WAMP pour exécuter les tests de base de données.'
            );
        }
    }

    public function testConnexionEstEtablie(): void
    {
        $this->skipIfNoConnection();

        $this->assertInstanceOf(\PDO::class, self::$pdo);
    }

    public function testRequeteSimpleFonctionne(): void
    {
        $this->skipIfNoConnection();

        $stmt = self::$pdo->query('SELECT 1 AS valeur');
        $row  = $stmt->fetch();

        $this->assertIsArray($row);
        $this->assertArrayHasKey('valeur', $row);
        $this->assertEquals(1, $row['valeur']);
    }

    public function testTableUsersExiste(): void
    {
        $this->skipIfNoConnection();

        $stmt   = self::$pdo->query("SHOW TABLES LIKE 'users'");
        $result = $stmt->fetchAll();

        $this->assertNotEmpty($result, "La table 'users' doit exister dans la base de données.");
    }

    public function testTableDefisExiste(): void
    {
        $this->skipIfNoConnection();

        $stmt   = self::$pdo->query("SHOW TABLES LIKE 'defis'");
        $result = $stmt->fetchAll();

        $this->assertNotEmpty($result, "La table 'defis' doit exister dans la base de données.");
    }
}
