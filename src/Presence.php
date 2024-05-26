<?php
//Auteur : Capdrake (Bastien LEUWERS)
namespace GeSign;

class Presence
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/api/Presence";

    /**
     * Récupère toutes les présences
     *
     * @return array La liste des présences.
     */
    public function fetchPresences()
    {
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $presences = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $presences;
    }

    /**
     * Crée une nouvelle présence
     *
     * @param int $userId L'ID de l'utilisateur.
     * @param int $subjectsHourId L'ID de l'heure de cours.
     * @param string $guid Le GUID de la présence.
     * @return array Les données de la présence créée.
     */
    public function createPresence($userId, $subjectsHourId, $guid)
    {
        $postData = json_encode([
            'presence_User' => ['id' => $userId],
            'presence_SubjectsHour' => ['subjectsHour_Id' => $subjectsHourId],
            'prescence_Guid' => $guid
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
            throw new \Exception("Échec de la création de la présence.");
        }

        return json_decode($response, true);
    }

    /**
     * Récupère une présence par ID
     *
     * @param int $presenceId L'ID de la présence à récupérer.
     * @return array Les données de la présence.
     */
    public function fetchPresenceById($presenceId)
    {
        $url = $this->apiUrl . '/' . $presenceId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $presence = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $presence;
    }

    /**
     * Met à jour les informations d'une présence existante
     *
     * @param int $presenceId L'ID de la présence à mettre à jour.
     * @param int $userId Le nouvel ID de l'utilisateur.
     * @param int $subjectsHourId Le nouvel ID de l'heure de cours.
     * @param string $guid Le nouveau GUID de la présence.
     * @return bool True si la mise à jour a réussi, sinon false.
     */
    public function updatePresence($presenceId, $userId, $subjectsHourId, $guid)
    {
        $url = $this->apiUrl . '/' . $presenceId;

        $postData = json_encode([
            'presence_Id' => $presenceId,
            'presence_User' => ['id' => $userId],
            'presence_SubjectsHour' => ['subjectsHour_Id' => $subjectsHourId],
            'prescence_Guid' => $guid
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode != 204) {
            throw new \Exception("Échec de la mise à jour de la présence.");
        }

        return true;
    }

    /**
     * Supprime une présence par ID
     *
     * @param int $presenceId L'ID de la présence à supprimer.
     * @return bool True si la suppression a réussi, sinon false.
     */
    public function deletePresence($presenceId)
    {
        $url = $this->apiUrl . '/' . $presenceId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode != 204) {
            throw new \Exception("Échec de la suppression de la présence, statut HTTP: " . $httpStatusCode);
        }

        return true;
    }
}
