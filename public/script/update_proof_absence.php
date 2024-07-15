<?php
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\ProofAbsence;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    $redirectUrl = '../lists/proof_absence_list.php?message=error&error=' . urlencode('Token non valide');
    header('Location: ' . $redirectUrl);
    exit;
}

$proofAbsenceId = $_POST['proofAbsence_Id'] ?? null;
$schoolComment = $_POST['proofAbsence_SchoolComment'] ?? '';
$proofAbsenceStatus = $_POST['proofAbsence_Status'] ?? null;
$presenceId = $_POST['presence_id'] ?? null;

if (!$proofAbsenceId || $proofAbsenceStatus === null || !$presenceId) {
    $redirectUrl = '../lists/proof_absence_list.php?message=error&error=' . urlencode('Données manquantes');
    header('Location: ' . $redirectUrl);
    exit;
}

try {
    $proofAbsenceManager = new ProofAbsence($token);

    $proofAbsenceData = [
        'proofAbsence_Id' => $proofAbsenceId,
        'proofAbsence_SchoolComment' => $schoolComment,
        'proofAbsence_Status' => $proofAbsenceStatus,
        'presence_id' => [$presenceId]
    ];

    $result = $proofAbsenceManager->updateProofAbsence($proofAbsenceId, $proofAbsenceData);

    $redirectUrl = '../lists/proof_absence_list.php?message=success';
    header('Location: ' . $redirectUrl);
    exit;
} catch (Exception $e) {
    $redirectUrl = '../lists/proof_absence_list.php?message=error&error=' . urlencode($e->getMessage());
    header('Location: ' . $redirectUrl);
    exit;
}
?>
