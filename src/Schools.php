<?php
namespace GeSign;

class Schools {
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/School";

    public function fetchSchools() {
        // Initialisation de cURL
        $ch = curl_init();

        // Configuration des options de cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

        // Exécution de la requête cURL
        $response = curl_exec($ch);

        // Fermeture de la session cURL
        curl_close($ch);

        // Gérer les erreurs de la requête
        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        // Décodage de la réponse JSON
        $schools = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $schools;
    }
	
	// Méthode pour créer une école
    public function createSchool($name, $token, $allowSite) {
        $postData = json_encode([
            'school_Name' => $name,
            'school_token' => $token,
            'school_allowSite' => $allowSite
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
            throw new \Exception("Echec de la création d'une école.");
        }

        return json_decode($response, true);
    }
}