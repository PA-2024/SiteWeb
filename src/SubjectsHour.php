<?php
//Auteur : Capdrake (Bastien LEUWERS)
namespace GeSign;

class SubjectsHour
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/api/SubjectsHour";

    /**
     * Récupère toutes les heures de cours
     *
     * @return array La liste des heures de cours.
     */
    public function fetchSubjectsHours()
    {
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $subjectsHours = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $subjectsHours;
    }

    /**
     * Crée une nouvelle heure de cours
     *
     * @param int $sectorId L'ID du secteur associé.
     * @param string $room Le nom de la salle.
     * @param string $date La date et l'heure du cours.
     * @return array Les données de l'heure de cours créée.
     */
    public function createSubjectsHour($sectorId, $room, $date)
    {
        $postData = json_encode([
            'subjectsHour_Sectors' => ['sectors_Id' => $sectorId],
            'subjectsHour_Rooom' => $room,
            'subjectsHour_Date' => $date
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
            throw new \Exception("Échec de la création de l'heure de cours.");
        }

        return json_decode($response, true);
    }

    /**
     * Récupère une heure de cours par ID
     *
     * @param int $subjectsHourId L'ID de l'heure de cours à récupérer.
     * @return array Les données de l'heure de cours.
     */
    public function fetchSubjectsHourById($subjectsHourId)
    {
        $url = $this->apiUrl . '/' . $subjectsHourId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $subjectsHour = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $subjectsHour;
    }

    /**
     * Met à jour les informations d'une heure de cours existante
     *
     * @param int $subjectsHourId L'ID de l'heure de cours à mettre à jour.
     * @param int $sectorId Le nouvel ID du secteur associé.
     * @param string $room Le nouveau nom de la salle.
     * @param string $date La nouvelle date et heure du cours.
     * @return bool True si la mise à jour a réussi, sinon false.
     */
    public function updateSubjectsHour($subjectsHourId, $sectorId, $room, $date)
    {
        $url = $this->apiUrl . '/' . $subjectsHourId;

        $postData = json_encode([
            'subjectsHour_Id' => $subjectsHourId,
            'subjectsHour_Sectors' => ['sectors_Id' => $sectorId],
            'subjectsHour_Rooom' => $room,
            'subjectsHour_Date' => $date
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
            throw new \Exception("Échec de la mise à jour de l'heure de cours.");
        }

        return true;
    }

    /**
     * Supprime une heure de cours par ID
     *
     * @param int $subjectsHourId L'ID de l'heure de cours à supprimer.
     * @return bool True si la suppression a réussi, sinon false.
     */
    public function deleteSubjectsHour($subjectsHourId)
    {
        $url = $this->apiUrl . '/' . $subjectsHourId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode != 204) {
            throw new \Exception("Échec de la suppression de l'heure de cours, statut HTTP: " . $httpStatusCode);
        }

        return true;
    }
}
