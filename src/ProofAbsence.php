<?php
//Auteur : Capdrake (Bastien LEUWERS)

namespace GeSign;

class ProofAbsence
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com";
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function createProofAbsence($presenceId, $proofAbsenceData)
    {
        $url = $this->apiUrl . "/CreateProofAbsence/" . $presenceId;

        $queryParams = http_build_query([
            'ProofAbsence_Id' => $proofAbsenceData['ProofAbsence_Id'],
            'ProofAbsence_UrlFile' => $proofAbsenceData['ProofAbsence_UrlFile'],
            'ProofAbsence_Status' => $proofAbsenceData['ProofAbsence_Status'],
            'ProofAbsence_SchoolCommentaire' => $proofAbsenceData['ProofAbsence_SchoolCommentaire'],
            'ProofAbsence_ReasonAbscence' => $proofAbsenceData['ProofAbsence_ReasonAbscence']
        ]);

        $ch = curl_init($url . '?' . $queryParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la création de la preuve d\'absence.');
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $result;
    }

    public function updateProofAbsence($id, $proofAbsenceData)
    {
        $url = $this->apiUrl . "/" . $id;

        $queryParams = http_build_query([
            'ProofAbsence_Id' => $proofAbsenceData['ProofAbsence_Id'],
            'ProofAbsence_SchoolComment' => $proofAbsenceData['ProofAbsence_SchoolComment'],
            'ProofAbsence_Status' => $proofAbsenceData['ProofAbsence_Status'],
            'Presence_id' => $proofAbsenceData['Presence_id']
        ]);

        $ch = curl_init($url . '?' . $queryParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la mise à jour de la preuve d\'absence.');
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
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
