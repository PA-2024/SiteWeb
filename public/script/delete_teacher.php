<?php
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Teacher;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacherId = $_POST['teacherId'];

    if (!$teacherId) {
        echo json_encode(['error' => 'ID de professeur non fourni']);
        exit;
    }

    try {
        $teacherManager = new Teacher($token);
        $teacherManager->deleteTeacher($teacherId);
        echo json_encode(['success' => 'Professeur supprimé']);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
