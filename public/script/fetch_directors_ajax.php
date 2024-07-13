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

try {
    $directorManager = new Director($token);
    $directors = $directorManager->fetchAllDirectors();
    echo json_encode($directors);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
