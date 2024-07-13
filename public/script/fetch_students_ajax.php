<?php

include '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Student;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$studentManager = new Student($token);
$students = $studentManager->fetchStudents();

echo json_encode($students);
?>
