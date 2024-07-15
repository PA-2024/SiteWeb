<?php
namespace GeSign;

class QcmResult
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/api/QcmResult";
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function fetchAllResultsForQcm($qcmId)
    {
        $url = $this->apiUrl . "/AllResultsQcmForOneQcm/" . urlencode($qcmId);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des résultats du QCM.');
        }

        $results = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $results;
    }

    public function fetchAllResultsDetailsForQcm($qcmId)
    {
        $url = $this->apiUrl . "/AllResultsQcmForOneQcmDetails/" . urlencode($qcmId);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des détails des résultats du QCM.');
        }

        $resultDetails = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $resultDetails;
    }

    public function fetchStudentResultsForQcm($qcmId)
    {
        $url = $this->apiUrl . "/StudentResultsQcmForOneQcm/" . urlencode($qcmId);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des résultats des étudiants pour le QCM.');
        }

        $studentResults = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $studentResults;
    }
}
