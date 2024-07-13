<?php
// Auteur : Capdrake (Bastien LEUWERS)
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Student;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    echo json_encode(['error' => 'Unauthorized']);
    http_response_code(401);
    exit;
}

$studentManager = new Student($token);

$className = $_GET['class'] ?? null;

if (!$className) {
    echo json_encode(['error' => 'Missing class name']);
    http_response_code(400);
    exit;
}

try {
    $students = $studentManager->fetchStudentsByClass($className);
    echo json_encode($students);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    http_response_code(500);
}
?>
