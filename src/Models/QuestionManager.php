<?php

namespace MVC\Models;

class QuestionManager
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

    public function getQuestionsByCategory()
    {
        $questions = $this->bdd->query('SELECT * FROM questions ORDER BY ordre')->fetchAll();
        $options = $this->bdd->query('SELECT * FROM question_options ORDER BY ordre')->fetchAll();

        $optionsByQuestion = [];
        foreach ($options as $option) {
            $optionsByQuestion[$option['question_id']][] = $option;
        }

        $grouped = [];
        foreach ($questions as $question) {
            $question['options'] = $optionsByQuestion[$question['id']] ?? [];
            $grouped[$question['categorie']][] = $question;
        }

        return $grouped;
    }
}
