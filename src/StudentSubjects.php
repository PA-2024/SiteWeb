<?php
namespace GeSign;

class StudentSubjects
{
    private $apiUrl = "https://apipa2024-a0a3b2c9ce54.herokuapp.com/api/StudentSubjects";
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function addStudentSubject($studentId, $subjectId)
    {
        $postData = json_encode([
            "student_Id" => $studentId,
            "subject_Id" => $subjectId
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
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de l\'ajout de la matière à l\'étudiant.');
        }

        if ($httpStatusCode !== 201) {
            throw new \Exception('Erreur lors de la requête HTTP. Code de statut : ' . $httpStatusCode);
        }

        if ($response === '') {
            return ['message' => 'Ajout réussi !'];
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON : ' . json_last_error_msg());
        }

        return $result;
    }

    public function deleteStudentSubject($studentId, $subjectId)
    {
        $postData = json_encode([
            "student_Id" => $studentId,
            "subject_Id" => $subjectId
        ]);

        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la suppression de la matière de l\'étudiant.');
        }

        if ($httpCode !== 200 && $httpCode !== 204) {
            throw new \Exception('Erreur HTTP : ' . $httpCode . '. Réponse : ' . $response);
        }

        if (empty($response) && $httpCode === 204) {
            return ['success' => true];
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON : ' . json_last_error_msg());
        }

        return $result;
    }

    public function addStudentsToSubject($subjectId, $studentIds)
    {
        $postData = json_encode([
            "subject_Id" => $subjectId,
            "studentIds" => $studentIds
        ]);
    
        $url = $this->apiUrl . '/List';
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);
    
        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($response === false) {
            throw new \Exception('Erreur lors de l\'ajout de la liste des étudiants.');
        }

        if ($httpStatusCode === 201) {
            return ['result' => null, 'status' => $httpStatusCode];
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return ['result' => $result, 'status' => $httpStatusCode];
    }
}
