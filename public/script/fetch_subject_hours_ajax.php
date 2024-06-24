<?php
// Auteur : Capdrake (Bastien LEUWERS)
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\SubjectsHour;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('HTTP/1.1 401 Unauthorized');
    exit;
}

$subjectHourManager = new SubjectsHour($token);

try {
    $subjectHours = $subjectHourManager->fetchAll();
    echo json_encode($subjectHours);
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
