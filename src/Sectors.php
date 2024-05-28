<?php
namespace GeSign;

class Sectors
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/Sectors";

    public function fetchSectors()
    {
        // Initialisation de cURL
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);

        curl_close($ch);
        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $sectors = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $sectors;
    }

    public function addSector($sectorsName, $schoolId)
    {
        $postData = json_encode([
            "sectors_Id" => 0,
            "sectors_Name" => $sectorsName,
            "sectors_School" => $schoolId
        ]);

        // Initialiser cURL
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        // Exécuter la requête
        $response = curl_exec($ch);

        // Fermer la session cURL
        curl_close($ch);

        // Gérer les erreurs de la requête
        if ($response === false) {
            throw new \Exception('Erreur lors de l\'ajout du secteur.');
        }

        // Décoder la réponse JSON
        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $result;
    }
}
