<?php
namespace GeSign;

class Director
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/api/Admin";
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function createDirector($email, $lastname, $firstname, $num, $schoolId)
    {
        $postData = json_encode([
            "user_email" => $email,
            "user_lastname" => $lastname,
            "user_firstname" => $firstname,
            "user_num" => $num,
            "user_school_id" => $schoolId
        ]);

        $ch = curl_init($this->apiUrl . '/CreateAdmin');
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

        if ($httpStatusCode != 200) {
            throw new \Exception("Échec de la création du directeur, statut HTTP: " . $httpStatusCode);
        }

        return json_decode($response, true);
    }

    public function deleteDirector($id)
    {
        $url = $this->apiUrl . '/' . $id;

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

        if ($httpStatusCode != 200) {
            throw new \Exception("Échec de la suppression du directeur, statut HTTP: " . $httpStatusCode);
        }

        return json_decode($response, true);
    }

    public function updateDirector($id, $email, $lastname, $firstname, $num, $schoolId)
    {
        $url = $this->apiUrl . '/' . $id;

        $postData = json_encode([
            "user_email" => $email,
            "user_lastname" => $lastname,
            "user_firstname" => $firstname,
            "user_num" => $num,
            "user_school_id" => $schoolId
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode != 200) {
            throw new \Exception("Échec de la mise à jour du directeur, statut HTTP: " . $httpStatusCode);
        }

        return json_decode($response, true);
    }

    public function fetchDirectorById($id)
    {
        $url = $this->apiUrl . '/One/' . $id;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode != 200) {
            throw new \Exception("Échec de la récupération du directeur, statut HTTP: " . $httpStatusCode);
        }

        return json_decode($response, true);
    }

    public function fetchAllDirectors()
    {
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode != 200) {
            throw new \Exception("Échec de la récupération des directeurs, statut HTTP: " . $httpStatusCode);
        }

        return json_decode($response, true);
    }
}
