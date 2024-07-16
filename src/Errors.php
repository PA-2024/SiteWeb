<?php
namespace GeSign;

class Errors
{
    private $apiUrl = "https://apipa2024-a0a3b2c9ce54.herokuapp.com/Errors";

    /**
     * Récupère toutes les erreurs depuis l'API.
     *
     * @return array La liste des erreurs.
     */
    public function fetchErrors()
    {
        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $errors = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $errors;
    }

    /**
     * Récupère une erreur par ID depuis l'API.
     *
     * @param int $errorId L'ID de l'erreur à récupérer.
     * @return array Les données de l'erreur.
     * @throws \Exception Si la requête échoue ou si la réponse JSON est mal formée.
     */
    public function fetchErrorById($errorId)
    {
        $url = $this->apiUrl . '/' . $errorId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des données.');
        }

        $error = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $error;
    }

    /**
     * Supprime une erreur par ID depuis l'API.
     *
     * @param int $errorId L'ID de l'erreur à supprimer.
     * @return bool True si la suppression a réussi, sinon false.
     */
    public function deleteError($errorId)
    {
        $url = $this->apiUrl . '/' . $errorId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode != 204) {
            throw new \Exception("Échec de la suppression de l'erreur, statut HTTP: " . $httpStatusCode);
        }

        return true;
    }

    /**
     * Marque une erreur comme résolue dans l'API.
     *
     * @param int $errorId L'ID de l'erreur à marquer comme résolue.
     * @return bool True si l'opération a réussi, sinon false.
     */
    public function resolveError($errorId)
    {
        $url = $this->apiUrl . '/' . $errorId . '/resolve';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode != 204) {
            throw new \Exception("Échec de la résolution de l'erreur, statut HTTP: " . $httpStatusCode);
        }

        return true;
    }
}
