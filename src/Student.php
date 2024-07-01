<?php
//Auteur : Capdrake (Bastien LEUWERS)
namespace GeSign;

use Exception;

class Student
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/api/Student";
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Récupère tous les étudiants depuis l'API.
     *
     * @return array La liste des étudiants.
     */
    public function fetchStudents()
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
            throw new Exception('Erreur lors de la récupération des données.');
        }

        $students = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erreur dans le décodage des données JSON.');
        }

        return $students;
    }

    /**
     * Crée un nouvel étudiant dans l'API.
     *
     * @param int $userId L'ID de l'utilisateur associé à l'étudiant.
     * @param int $classId L'ID de la classe associée à l'étudiant.
     * @return array Les données de l'étudiant créé.
     */
    public function createStudent($userId, $classId)
    {
        $postData = json_encode([
            'student_User_id' => $userId,
            'student_Class_id' => $classId
        ]);

        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new Exception("Échec de la création de l'étudiant.");
        }

        return json_decode($response, true);
    }

    /**
     * Enregistre un nouvel étudiant dans l'API.
     *
     * @param string $email L'email de l'étudiant.
     * @param string $password Le mot de passe de l'étudiant.
     * @param string $lastname Le nom de famille de l'étudiant.
     * @param string $firstname Le prénom de l'étudiant.
     * @param string $num Le numéro de l'étudiant.
     * @param int $schoolId L'ID de l'école de l'étudiant.
     * @param int $sectorId L'ID du secteur de l'étudiant.
     * @return array Les données de l'étudiant créé.
     */
    public function registerStudent($email, $password, $lastname, $firstname, $num, $schoolId, $sectorId)
    {
        $ch = curl_init("{$this->apiUrl}/registerStudent");

        $data = [
            "user_email" => $email,
            "user_password" => $password,
            "user_lastname" => $lastname,
            "user_firstname" => $firstname,
            "user_num" => $num,
            "user_School_Id" => $schoolId,
            "student_Sector_Id" => $sectorId
        ];

        $payload = json_encode($data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new Exception('Erreur lors de la création de l\'étudiant.');
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erreur dans le décodage des données JSON.');
        }

        if (isset($result['error'])) {
            throw new Exception($result['error']);
        }

        return $result;
    }

    /**
     * Récupère un étudiant avec son ID.
     *
     * @param int $studentId L'ID de l'étudiant à récupérer.
     * @return array Les données de l'étudiant.
     */
    public function fetchStudentById($studentId)
    {
        $url = $this->apiUrl . '/' . $studentId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new Exception('Erreur lors de la récupération des données.');
        }

        $student = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erreur dans le décodage des données JSON.');
        }

        return $student;
    }

    /**
     * Met à jour les informations d'un étudiant existant dans l'API.
     *
     * @param int $studentId L'ID de l'étudiant à mettre à jour.
     * @param int $userId Le nouvel ID de l'utilisateur associé à l'étudiant.
     * @param int $classId Le nouvel ID de la classe associée à l'étudiant.
     * @return array Les données de l'étudiant mis à jour.
     */
    public function updateStudent($studentId, $userId, $classId)
    {
        $url = $this->apiUrl . '/' . $studentId;

        $putData = json_encode([
            'student_Id' => $studentId,
            'student_User_Id' => $userId,
            'student_Sector_Id' => $classId
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $putData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new Exception("Échec de la mise à jour de l'étudiant.");
        }

        return json_decode($response, true);
    }

    /**
     * Supprime un étudiant par ID depuis l'API.
     *
     * @param int $studentId L'ID de l'étudiant à supprimer.
     * @return bool True si la suppression a réussi, sinon false.
     */
    public function deleteStudent($studentId)
    {
        $url = $this->apiUrl . '/' . $studentId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode != 204) {
            throw new Exception("Échec de la suppression de l'étudiant, statut HTTP: " . $httpStatusCode);
        }

        return true;
    }
}
