<?php
namespace GeSign;

class Schools
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/School";

    public function fetchSchools()
    {
        // Initialisation de cURL
        $ch = curl_init();

        // Configuration des options de cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

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

        // Filtrer et formater les dates des écoles
        foreach ($schools as &$school) {
            if (isset($school['school_Date']) && $school['school_Date'] != "0001-01-01T00:00:00") {
                $school['school_Date'] = date('Y-m-d\TH:i:s', strtotime($school['school_Date']));
            }
        }

        return $schools;
    }

    public function createSchool($name, $token, $allowSite)
    {
        // On utilise date() pour obtenir la date/heure actuelle au format ISO 8601
        $currentDate = date('Y-m-d') . 'T' . date('H:i:s'); // Format "2024-05-12T00:00:00"

        $postData = json_encode([
            'school_Name' => $name,
            'school_token' => $token,
            'school_allowSite' => $allowSite,
            'school_Date' => $currentDate
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
            throw new \Exception("Échec de la création d'une école.");
        }

        return json_decode($response, true);
    }

    public function deleteSchool($schoolId)
    {
        $url = $this->apiUrl . '/' . $schoolId; // On construit la requête de suppression ici

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // On récupère le code de statut HTTP de la réponse
        curl_close($ch);

        if ($httpStatusCode != 204) { // 204 No Content est le statut attendu pour une suppression réussie
            throw new \Exception("Échec de la suppression de l'école, statut HTTP: " . $httpStatusCode);
        }

        return true; // Retourne vrai si la suppression est réussie
    }

    /**
     * Récupère le nombre d'écoles créées pour un mois donné.
     *
     * @param int $year Année concernée.
     * @param int $month Mois concerné.
     * @return int Nombre d'écoles créées durant le mois spécifié.
     * @throws \Exception Si la requête échoue ou si les données JSON sont mal formées.
     */
    public function countSchoolsByMonth($year, $month)
    {
        $startDate = "$year-$month-01T00:00:00";
        $endDate = date("Y-m-t\T23:59:59", strtotime($startDate));

        $url = $this->apiUrl . "?startDate=$startDate&endDate=$endDate";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception("Impossible de récupérer le nombre d'écoles.");
        }

        $data = json_decode($response, true);

        // Compter les écoles dans le mois donné
        $count = 0;
        foreach ($data as $school) {
            $schoolDate = $school['school_Date'] ?? '';
            $isInDateRange = $schoolDate >= $startDate && $schoolDate <= $endDate;

            if ($isInDateRange) {
                $count++;
            }
        }

        return $count;
    }

    public function fetchSchoolById($schoolId)
    {
        $url = $this->apiUrl . '/' . $schoolId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $school = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $school;
    }

    /**
     * Met à jour les informations d'une école existante.
     *
     * @param int $schoolId L'ID de l'école à mettre à jour.
     * @param string $name Le nouveau nom de l'école.
     * @param string $token Le nouveau token de l'école.
     * @param bool $allowSite La nouvelle autorisation du site de l'école.
     * @return array Les données de l'école mise à jour.
     * @throws \Exception Si la requête échoue.
     */
    public function updateSchool($schoolId, $name, $token, $allowSite)
    {
        $url = $this->apiUrl . '/' . $schoolId;

        // Obtenir les données actuelles de l'école pour conserver la date de création
        $currentSchool = $this->fetchSchoolById($schoolId);
        if ($currentSchool === null) {
            throw new \Exception("École non trouvée.");
        }

        $postData = json_encode([
            'school_Id' => $schoolId,
            'school_Name' => $name,
            'school_token' => $token,
            'school_allowSite' => $allowSite,
            'school_Date' => $currentSchool['school_Date'] 
            // Conserver la date de création actuelle (l'API casse la date sans cela)
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpStatusCode != 200) {
            throw new \Exception("Échec de la mise à jour de l'école.");
        }

        return json_decode($response, true);
    }
}
