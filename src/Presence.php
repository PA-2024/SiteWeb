<?php
//Auteur : Capdrake (Bastien LEUWERS)
namespace GeSign;

class Presence
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/api/Presence";
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function fetchPresences()
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
            throw new \Exception('Erreur lors de la récupération des présences.');
        }

        $presences = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $presences;
    }

    public function fetchPresenceById($presenceId)
    {
        $url = $this->apiUrl . '/' . $presenceId;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération de la présence.');
        }

        $presence = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $presence;
    }

    public function createPresence($studentId, $subjectsHourId, $scanDate, $scanInfo)
    {
        $postData = json_encode([
            'presence_Student_Id' => $studentId,
            'presence_SubjectsHour_Id' => $subjectsHourId,
            'presence_ScanDate' => $scanDate,
            'presence_ScanInfo' => $scanInfo
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
        curl_close($ch);

        if ($response === false) {
            throw new \Exception("Échec de la création de la présence.");
        }

        return json_decode($response, true);
    }

    public function updatePresence($presenceId, $studentId, $subjectsHourId, $scanDate, $scanInfo)
    {
        $url = $this->apiUrl . '/' . $presenceId;

        $putData = json_encode([
            'presence_Id' => $presenceId,
            'presence_Student_Id' => $studentId,
            'presence_SubjectsHour_Id' => $subjectsHourId,
            'presence_ScanDate' => $scanDate,
            'presence_ScanInfo' => $scanInfo
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
        curl_close($ch);

        if ($response === false) {
            throw new \Exception("Échec de la mise à jour de la présence.");
        }

        return json_decode($response, true);
    }

    public function deletePresence($presenceId)
    {
        $url = $this->apiUrl . '/' . $presenceId;

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

        if ($httpStatusCode != 204) {
            throw new \Exception("Échec de la suppression de la présence, statut HTTP: " . $httpStatusCode);
        }

        return true;
    }

    public function fetchUnconfirmedPresences()
    {
        $url = $this->apiUrl . '/unconfirmed';
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des présences non confirmées.');
        }

        $presences = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $presences;
    }

    public function fetchAttendanceSummary()
    {
        $url = $this->apiUrl . '/attendance-summary';
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération du résumé des présences.');
        }

        $summary = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $summary;
    }

    public function fetchPresencesBySubjectsHourId($subjectsHourId)
    {
        $url = $this->apiUrl . '/SubjectsHourWithPresences/' . $subjectsHourId;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des présences.');
        }

        $presences = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $presences;
    }
}
