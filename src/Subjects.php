<?php
// Auteur : Capdrake (Bastien LEUWERS)
namespace GeSign;

class Subjects
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/api/Subjects";
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Récupère tous les sujets depuis l'API.
     *
     * @return array La liste des sujets.
     */
    public function fetchSubjects()
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
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $subjects = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $subjects;
    }

    /**
     * Crée un nouveau sujet dans l'API.
     *
     * @param string $name Le nom du sujet.
     * @param int $userId L'ID de l'utilisateur enseignant.
     * @return array Les données du sujet créé.
     */
    public function createSubject($name, $userId)
    {
        $postData = json_encode([
            'subjects_Name' => $name,
            'subjects_User_Id' => $userId
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

        if ($httpStatusCode !== 201) {
            return json_decode($response, true)['error'] ?? 'Création échouée';
        }

        return json_decode($response, true);
    }

    /**
     * Récupère un sujet par son ID.
     *
     * @param int $subjectId L'ID du sujet à récupérer.
     * @return array Les données du sujet.
     */
    public function fetchSubjectById($subjectId)
    {
        $url = $this->apiUrl . '/' . $subjectId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $subject = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $subject;
    }

    /**
     * Met à jour les informations d'un sujet existant dans l'API.
     *
     * @param int $subjectId L'ID du sujet à mettre à jour.
     * @param string $name Le nouveau nom du sujet.
     * @param int $userId L'ID de l'utilisateur enseignant.
     * @return bool True si la mise à jour a réussi, sinon false.
     */
    public function updateSubject($subjectId, $name, $userId)
    {
        $url = $this->apiUrl . '/' . $subjectId;

        $putData = json_encode([
            'subjects_Id' => $subjectId,
            'subjects_Name' => $name,
            'subjects_User_Id' => $userId
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
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode !== 204) {
            return json_decode($response, true)['error'] ?? 'Mise à jour échouée';
        }

        return true;
    }

    /**
     * Supprime un sujet par ID depuis l'API.
     *
     * @param int $subjectId L'ID du sujet à supprimer.
     * @return bool True si la suppression a réussi, sinon false.
     */
    public function deleteSubject($subjectId)
    {
        $url = $this->apiUrl . '/' . $subjectId;

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

        if ($httpStatusCode !== 204) {
            return json_decode($response, true)['error'] ?? 'Suppression échouée';
        }

        return true;
    }
}
