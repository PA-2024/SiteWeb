<?php
// Auteur : Capdrake

require_once '../../vendor/autoload.php';
use GeSign\Buildings;

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['buildingId'])) {
    $buildingManager = new Buildings($token);
    try {
        $result = $buildingManager->deleteBuilding($_POST['buildingId']);
        if ($result === true) {
            echo 'Success';
        } else {
            http_response_code(500);
            echo 'Error: ' . $result;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo 'Error: ' . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo 'Invalid request';
}
?>
