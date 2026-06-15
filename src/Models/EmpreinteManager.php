<?php

namespace MVC\Models;

class EmpreinteManager
{
    private $bdd;

    public function __construct()
    {
        try {
            $this->bdd = new \PDO(
                'mysql:host=' . HOST . ';dbname=' . DATABASE . ';charset=utf8mb4',
                USER,
                PASSWORD,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (\PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    public function save(int $userId, array $result)
    {
        $query = $this->bdd->prepare('
            INSERT INTO empreintes
                (user_id, empreinte_carbone, empreinte_transport, empreinte_logement, empreinte_alimentation, empreinte_achat_numerique, created_at)
            VALUES
                (:user_id, :carbone, :transport, :logement, :alimentation, :achat_numerique, NOW())
        ');

        $query->execute([
            ':user_id'         => $userId,
            ':carbone'         => $result['total'],
            ':transport'       => $result['breakdown']['transport'],
            ':logement'        => $result['breakdown']['logement'],
            ':alimentation'    => $result['breakdown']['alimentation'],
            ':achat_numerique' => $result['breakdown']['achats_numerique'],
        ]);

        return (int) $this->bdd->lastInsertId();
    }
}
