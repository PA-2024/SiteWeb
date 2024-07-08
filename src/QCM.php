<?php
namespace GeSign;

class QCM
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/api/QCM";
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function fetchAllQCMs($pageNumber = 1, $pageSize = 10)
    {
        $url = $this->apiUrl . "/qcm?pageNumber=" . urlencode($pageNumber) . "&pageSize=" . urlencode($pageSize);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des QCM.');
        }

        $qcms = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $qcms;
    }

    public function fetchAllQCMsTeacher($pageNumber = 1, $pageSize = 10)
    {
        $url = sprintf("%s/qcmforTeacher?pageNumber=%d&pageSize=%d", $this->apiUrl, $pageNumber, $pageSize);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des QCM.');
        }

        $qcms = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $qcms;
    }

    public function fetchQCMByRange($startDate, $endDate)
    {
        $url = $this->apiUrl . "/qcmByRange?StartDate=" . urlencode($startDate) . "&EndDate=" . urlencode($endDate);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération des QCM par intervalle de dates.');
        }

        $qcms = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $qcms;
    }

    public function duplicateQCMById($id, $subjectsHourId)
    {
        $url = $this->apiUrl . "/DuplicateQcmByIdQcm/" . urlencode($id) . "/" . urlencode($subjectsHourId);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpStatusCode != 200) {
            throw new \Exception('Erreur lors de la duplication du QCM.');
        }

        return json_decode($response, true);
    }

    public function addQuestion($QCM_id, $questionText, $options)
    {
        $url = $this->apiUrl . "/AddQuestion/" . urlencode($QCM_id);
        $postData = json_encode([
            'text' => $questionText,
            'options' => $options
        ]);

        $ch = curl_init($url);
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

        if ($response === false || $httpStatusCode != 200) {
            throw new \Exception('Erreur lors de l\'ajout de la question.');
        }

        return json_decode($response, true);
    }

    public function createQCM($title, $subjectHourId, $questions)
    {
        $url = $this->apiUrl . "/QCM";
        $postData = json_encode([
            'title' => $title,
            'subjectHour_id' => $subjectHourId,
            'questions' => $questions
        ]);

        $ch = curl_init($url);
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

        if ($response === false || $httpStatusCode != 200) {
            throw new \Exception('Erreur lors de la création du QCM.');
        }

        return json_decode($response, true);
    }

    public function fetchQCMById($id)
    {
        $url = $this->apiUrl . "/byId/" . urlencode($id);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de la récupération du QCM.');
        }

        $qcm = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON.');
        }

        return $qcm;
    }
}
