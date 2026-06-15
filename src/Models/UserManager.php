<?php

namespace MVC\Models;

use MVC\Models\User;

class UserManager
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

    public function register(User $user)
    {
        try {
            if ($this->getUserByEmail($user->getEmail())) {
                return ['success' => false, 'message' => 'Cet email est déjà utilisé'];
            }

            $user->hashPassword();

            $query = $this->bdd->prepare('
                INSERT INTO users (name, email, password, created_at)
                VALUES (:name, :email, :password, NOW())
            ');

            $query->execute([
                ':name' => $user->getName(),
                ':email' => $user->getEmail(),
                ':password' => $user->getPassword(),
            ]);

            return ['success' => true, 'message' => 'Inscription réussie ! Vous pouvez maintenant vous connecter.'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de l\'inscription : ' . $e->getMessage()];
        }
    }

    public function login($email, $password)
    {
        try {
            $user = $this->getUserByEmail($email);

            if (!$user) {
                return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
            }

            if (!$user->verifyPassword($password)) {
                return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
            }

            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_name'] = $user->getName();
            $_SESSION['user_email'] = $user->getEmail();

            return ['success' => true, 'message' => 'Connexion réussie !'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de la connexion : ' . $e->getMessage()];
        }
    }

    public function getUserByEmail($email)
    {
        try {
            $query = $this->bdd->prepare('SELECT * FROM users WHERE email = :email');
            $query->execute([':email' => $email]);

            $data = $query->fetch();

            if (!$data) {
                return null;
            }

            $user = new User();
            $user->setId($data['id']);
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setPassword($data['password']);
            $user->setCreatedAt($data['created_at']);
            $user->setUpdatedAt($data['updated_at']);

            return $user;
        } catch (\PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function getUserById($id)
    {
        try {
            $query = $this->bdd->prepare('SELECT * FROM users WHERE id = :id');
            $query->execute([':id' => $id]);

            $data = $query->fetch();

            if (!$data) {
                return null;
            }

            $user = new User();
            $user->setId($data['id']);
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setPassword($data['password']);
            $user->setCreatedAt($data['created_at']);
            $user->setUpdatedAt($data['updated_at']);

            return $user;
        } catch (\PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public static function logout()
    {
        session_destroy();
        return ['success' => true, 'message' => 'Déconnecté avec succès'];
    }
}
