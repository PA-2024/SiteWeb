<?php
// Auteur : Capdrake (Bastien LEUWERS)

namespace GeSign;

class Teacher
{
    private $apiUrl = "https://apipa2024-a0a3b2c9ce54.herokuapp.com/api/Teacher";
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

    public function registerTeacher($email, $password, $lastname, $firstname, $num, $schoolId)
    {
        $data = [
            'user_email' => $email,
            'user_password' => $password,
            'user_lastname' => $lastname,
            'user_firstname' => $firstname,
            'user_num' => $num,
            'user_School_Id' => $schoolId
        ];

        $ch = curl_init($this->apiUrl . '/registerTeacher');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpcode !== 200) {
            throw new \Exception('Erreur lors de l\'enregistrement du professeur.');
        }

        return json_decode($response, true);
    }

    public function deleteTeacher($teacherId)
    {
        $ch = curl_init($this->apiUrl . '/DeleteTeacher/' . $teacherId);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la suppression de l\'enseignant.');
        }

        return json_decode($response, true);
    }
}
