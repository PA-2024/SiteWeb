<?php
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\QCM;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Professeur');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Token non valide']);
    exit;
}

try {
    $qcmManager = new QCM($token);
    $qcms = $qcmManager->fetchAllQCMsTeacher(1, 30);
    echo json_encode($qcms['items']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
