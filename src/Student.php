<?php
//Auteur : Capdrake (Bastien LEUWERS)
namespace GeSign;

class Student
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/api/Student";

    /**
     * Récupère tous les étudiants depuis l'API.
     *
     * @return array La liste des étudiants.
     */
    public function fetchStudents()
    {
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $students = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $students;
    }

    /**
     * Crée un nouvel étudiant dans l'API.
     *
     * @param string $firstName Le prénom de l'étudiant.
     * @param string $lastName Le nom de famille de l'étudiant.
     * @param int $userId L'ID de l'utilisateur associé à l'étudiant.
     * @param int $sectorId L'ID du secteur associé à l'étudiant.
     * @return array Les données de l'étudiant créé.
     */
    public function createStudent($firstName, $lastName, $userId, $sectorId)
    {
        $postData = json_encode([
            'student_FirstName' => $firstName,
            'student_LastName' => $lastName,
            'student_User_Id' => $userId,
            'student_Sector_Id' => $sectorId
        ]);

        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception("Échec de la création de l'étudiant.");
        }

        return json_decode($response, true);
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
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $student = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $student;
    }

    /**
     * Met à jour les informations d'un étudiant existant dans l'API.
     *
     * @param int $studentId L'ID de l'étudiant à mettre à jour.
     * @param string $firstName Le nouveau prénom de l'étudiant.
     * @param string $lastName Le nouveau nom de famille de l'étudiant.
     * @param int $userId Le nouvel ID de l'utilisateur associé à l'étudiant.
     * @param int $sectorId Le nouvel ID du secteur associé à l'étudiant.
     * @return array Les données de l'étudiant mis à jour.
     */
    public function updateStudent($studentId, $firstName, $lastName, $userId, $sectorId)
    {
        $url = $this->apiUrl . '/' . $studentId;

        $putData = json_encode([
            'student_Id' => $studentId,
            'student_FirstName' => $firstName,
            'student_LastName' => $lastName,
            'student_User_Id' => $userId,
            'student_Sector_Id' => $sectorId
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $putData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception("Échec de la mise à jour de l'étudiant.");
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
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode != 204) {
            throw new \Exception("Échec de la suppression de l'étudiant, statut HTTP: " . $httpStatusCode);
        }

        return true;
    }
}
