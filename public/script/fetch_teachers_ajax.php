<?php
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Teacher;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    echo json_encode(['error' => 'Accès refusé. Token manquant.']);
    exit;
}

try {
    $teacherManager = new Teacher($token);
    $teachers = $teacherManager->fetchTeachers();
    echo json_encode($teachers);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
