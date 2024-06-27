<?php
// Auteur : Capdrake (Bastien LEUWERS)

namespace GeSign;

class Teacher
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/api/Teacher";
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function fetchTeachers()
    {
        $ch = curl_init($this->apiUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des enseignants.');
        }

        $teachers = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $teachers;
    }
}
