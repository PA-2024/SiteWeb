<?php
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\QCM;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Professeur');

$token = $_SESSION['token'] ?? $_COOKIE['token'];
$qcmId = $_POST['qcmId'] ?? null;

if (!$token || !$qcmId) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Token non valide ou QCM ID manquant']);
    exit;
}

try {
    $qcmManager = new QCM($token);
    $response = $qcmManager->deleteQcm($qcmId);
    echo json_encode(['status' => 'success', 'message' => 'QCM supprimé avec succès']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
