<?php
//Auteur : Capdrake (Bastien LEUWERS)

namespace GeSign;

class ProofAbsence
{
    private $apiUrl = "https://apipa2024-a0a3b2c9ce54.herokuapp.com";
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function createProofAbsence($presenceId, $proofAbsenceData)
    {
        $url = $this->apiUrl . "/CreateProofAbsence/" . $presenceId;
    
        $postData = json_encode([
            'proofAbsence_StudentComment' => $proofAbsenceData['proofAbsence_StudentComment'],
            'proofAbsence_UrlFile' => $proofAbsenceData['proofAbsence_UrlFile']
        ]);
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: ' . $this->token
        ]);
    
        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($response === false) {
            throw new \Exception('Erreur lors de la création de la preuve d\'absence.');
        }
    
        // Vérification de la réponse en texte brut
        if ($httpStatusCode === 200) {
            return ['status' => 'success', 'message' => $response];
        }
    
        $result = json_decode($response, true);
    
        if ($httpStatusCode !== 200) {
            throw new \Exception('Erreur HTTP ' . $httpStatusCode . ': ' . $response);
        }
    
        return $result;
    }

    public function updateProofAbsence($id, $proofAbsenceData)
    {
        $url = $this->apiUrl . "/" . $id;
    
        $postData = json_encode([
            'proofAbsence_Id' => $proofAbsenceData['proofAbsence_Id'],
            'proofAbsence_SchoolComment' => $proofAbsenceData['proofAbsence_SchoolComment'],
            'proofAbsence_Status' => $proofAbsenceData['proofAbsence_Status'],
            'presence_id' => $proofAbsenceData['presence_id']
        ]);
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: ' . $this->token
        ]);
    
        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($response === false) {
            throw new \Exception('Erreur lors de la mise à jour de la preuve d\'absence.');
        }
    
        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $jsonError = json_last_error_msg();
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }
    
        if ($httpStatusCode !== 200) {
            throw new \Exception('Erreur HTTP ' . $httpStatusCode . ': ' . $response);
        }
    
        return $result;
    }

    public function fetchProofAbsenceAll($studentId = null)
    {
        $url = $this->apiUrl . "/GetProofAbsenceAll";

        if ($studentId !== null) {
            $url .= "?Student_id=" . intval($studentId);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des preuves d\'absence.');
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $result;
    }

    public function fetchProofAbsenceByStudent()
    {
        $url = $this->apiUrl . "/GetProofAbsenceAll/student";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des preuves d\'absence pour l\'étudiant.');
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $result;
    }
}
