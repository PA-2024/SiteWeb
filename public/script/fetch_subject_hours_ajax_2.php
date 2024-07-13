<?php
// Auteur : Capdrake (Bastien LEUWERS)
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\SubjectsHour;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('HTTP/1.1 401 Unauthorized');
    exit;
}

$subjectHourManager = new SubjectsHour($token);
$subjectId = $_GET['subjectId'] ?? null;

if ($subjectId) {
    try {
        $allSubjectHours = $subjectHourManager->fetchAll();
        $subjectHours = array_filter($allSubjectHours, function($hour) use ($subjectId) {
            return $hour['subjectsHour_Subjects_Id'] == $subjectId;
        });
        echo json_encode(array_values($subjectHours));
    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode([]);
}
?>
