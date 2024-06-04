<?php
// Auteur : Capdrake

require_once '../../vendor/autoload.php';
use GeSign\Buildings;
$token = $_SESSION['token'] ?? $_COOKIE['token'];

$buildingManager = new Buildings($token);

// Récupération de l'ID de l'école à partir de la session ou du cookie
$schoolId = $_SESSION['schoolId'] ?? $_COOKIE['schoolId'];

if (!$schoolId) {
    // Renvoie une réponse d'erreur si l'ID de l'école n'est pas disponible
    http_response_code(400);
    echo json_encode(['error' => 'ID de l\'école non disponible']);
    exit;
}

$buildings = $buildingManager->fetchBuildingsBySchoolId($schoolId);

header('Content-Type: application/json');
echo json_encode($buildings);
exit;
?>
