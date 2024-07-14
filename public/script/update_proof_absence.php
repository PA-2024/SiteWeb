<?php
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\ProofAbsence;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Token non valide']);
    exit;
}

$proofAbsenceId = $_POST['proofAbsence_Id'] ?? null;
$schoolComment = $_POST['proofAbsence_SchoolComment'] ?? '';
$proofAbsenceStatus = $_POST['proofAbsence_Status'] ?? null;

if (!$proofAbsenceId || $proofAbsenceStatus === null) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Données manquantes']);
    exit;
}

try {
    $proofAbsenceManager = new ProofAbsence($token);

    $proofAbsenceData = [
        'proofAbsence_Id' => $proofAbsenceId,
        'proofAbsence_SchoolComment' => $schoolComment,
        'proofAbsence_Status' => $proofAbsenceStatus,
        'presence_id' => [$proofAbsenceId]
    ];

    $result = $proofAbsenceManager->updateProofAbsence($proofAbsenceId, $proofAbsenceData);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Justification d\'absence mise à jour avec succès']);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
