<?php
// Auteur : Capdrake (Bastien LEUWERS)
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Presence;

header('Content-Type: application/json');

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$presenceManager = new Presence($token);

if (!isset($_GET['subjectsHourId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Bad Request: subjectsHourId is required']);
    exit;
}

$subjectsHourId = $_GET['subjectsHourId'];

try {
    $presences = $presenceManager->fetchPresencesBySubjectsHourId($subjectsHourId);
    echo json_encode($presences['students']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error: ' . $e->getMessage()]);
}
?>
