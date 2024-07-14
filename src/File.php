<?php
namespace GeSign;

class File
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/import";
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Envoie un lien de fichier à l'API pour l'importation.
     *
     * @param string $fileLink Le lien du fichier à importer.
     * @return array La réponse de l'API.
     * @throws \Exception Si une erreur se produit lors de l'importation.
     */
    public function importFile($fileLink)
    {
        $url = $this->apiUrl . '?file=' . urlencode($fileLink);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: */*',
            'Authorization: ' . $this->token
        ]);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Erreur lors de l\'envoi du fichier.');
        }

        if ($httpStatusCode !== 200) {
            throw new \Exception('Erreur HTTP ' . $httpStatusCode . ': ' . $response);
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $jsonError = json_last_error_msg();
            $errorDetails = [
                'json_error' => $jsonError,
                'json_error_code' => json_last_error(),
                'response' => $response
            ];
            throw new \Exception('Erreur dans le décodage des données JSON: ' . json_encode($errorDetails));
        }

        return $result;
    }
}
