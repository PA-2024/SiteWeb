<?php
namespace GeSign;

class Auth
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/api/Auth";
    
    // Plus nécessaire
    public function register($userName, $email, $password, $phoneNumber)
    {
        $postData = json_encode([
            'userName' => $userName,
            'normalizedUserName' => strtoupper($userName),
            'email' => $email,
            'normalizedEmail' => strtoupper($email),
            'emailConfirmed' => true,
            'passwordHash' => $password,
            'securityStamp' => bin2hex(random_bytes(16)),
            'concurrencyStamp' => bin2hex(random_bytes(16)),
            'phoneNumber' => $phoneNumber,
            'phoneNumberConfirmed' => false,
            'twoFactorEnabled' => false,
            'lockoutEnd' => null,
            'lockoutEnabled' => true,
            'accessFailedCount' => 0,
            'user_Id' => 0,
            'user_email' => $email,
            'user_password' => $password,
            'user_Role' => [
                'roles_Id' => 0,
                'role_Name' => 'string'
            ]
        ]);

        $ch = curl_init($this->apiUrl . '/register');
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

        $decodedResponse = json_decode($response, true);

        return $decodedResponse;
    }
    
    // OK
    public function login($email, $password)
    {
        $postData = json_encode([
            'user_email' => $email,
            'user_password' => $password
        ]);

        $ch = curl_init($this->apiUrl . '/login');
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

        if ($httpStatusCode !== 200) {
            return json_decode($response, true)['error'] ?? 'Problème lors de la connexion';
        }

        $data = json_decode($response, true);

        // on décode le JWT pour extraire les informations utilisateur
        $tokenParts = explode('.', $data['token']);
        $payload = json_decode(base64_decode($tokenParts[1]), true);

        return [
            'token' => $data['token'],
            'user_Id' => $payload['nameid'],
            'userName' => $payload['unique_name'],
            'role' => $payload['role'],
            'school' => $payload['SchoolName'],
            'schoolId' => $payload['SchoolId']
        ];
    }

    // Réinitialisation du mot de passe
    public function resetPassword($email)
    {
        $url = $this->apiUrl . '/reset?email=' . urlencode($email);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatusCode !== 200) {
            throw new \Exception('Erreur HTTP ' . $httpStatusCode . ': ' . $response);
        }

        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON: ' . json_last_error_msg());
        }

        return $result;
    }

    // Définir un nouveau mot de passe
    public function setNewPassword($userId, $code, $password)
    {
        $url = $this->apiUrl . '/newpassword?' . http_build_query([
            'user_id' => $userId,
            'code' => $code,
            'password' => $password
        ]);
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: text/plain'
        ]);
    
        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($httpStatusCode !== 200) {
            throw new \Exception('Erreur HTTP ' . $httpStatusCode . ': ' . $response . ' URL : ' . $url);
        }
    
        // Vérifier si la réponse est vide
        if (empty($response)) {
            return [
                'status' => 'success',
                'message' => 'Le mot de passe a été réinitialisé avec succès.'
            ];
        }
    
        $result = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur dans le décodage des données JSON: ' . json_last_error_msg() . ' URL : ' . $url . ' Response : ' . $response);
        }
    
        return $result;
    }
}
