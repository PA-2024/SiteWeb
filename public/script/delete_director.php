<?php
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Director;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Admin');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

if (!isset($_POST['directorId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Bad Request: Missing directorId']);
    exit;
}

$directorId = $_POST['directorId'];

try {
    $directorManager = new Director($token);
    $directorManager->deleteDirector($directorId);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
