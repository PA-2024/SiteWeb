<?php
//Auteur : Capdrake (Bastien LEUWERS)

namespace GeSign;

class Buildings
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/Buildings";
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function fetchBuildings()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $buildings = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $buildings;
    }

    public function createBuilding($city, $name, $address, $school)
    {
        $postData = json_encode([
            'bulding_City' => $city,
            'bulding_Name' => $name,
            'bulding_Adress' => $address,
            'school' => $school
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
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode !== 201) {
            return json_decode($response, true)['error'] ?? 'Création échouée';
        }

        return json_decode($response, true);
    }

    public function fetchBuildingById($id)
    {
        $url = $this->apiUrl . '/' . $id;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $building = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $building;
    }

    public function updateBuilding($id, $city, $name, $address, $school)
    {
        $putData = json_encode([
            'bulding_Id' => $id,
            'bulding_City' => $city,
            'bulding_Name' => $name,
            'bulding_Adress' => $address,
            'school' => $school
        ]);

        $url = $this->apiUrl . '/' . $id;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $putData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode !== 204) {
            return json_decode($response, true)['error'] ?? 'Mise à jour échouée';
        }

        return true;
    }

    public function deleteBuilding($id)
    {
        $url = $this->apiUrl . '/' . $id;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode !== 204) {
            return json_decode($response, true)['error'] ?? 'Suppression échouée';
        }

        return true;
    }

    public function fetchBuildingsBySchoolId($schoolId)
    {
        $url = $this->apiUrl . '/GetBySchool/' . $schoolId;
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

        $buildings = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $buildings;
    }
}
