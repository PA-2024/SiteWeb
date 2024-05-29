<?php
namespace GeSign;

class Auth
{
    private $apiUrl = "https://apigessignrecette-c5e974013fbd.herokuapp.com/api/Auth";
    
    // A tester
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
            'school' => $payload['SchoolName']
        ];
    }
}
