<?php
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\StudentSubjects;
use GeSign\Student;

$sessionManager = new SessionManager();
$sessionManager->checkUserRole('Gestion Ecole');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../views/auth/login.php');
    exit;
}

$subjectId = $_POST['subjectId'] ?? null;
$sectorId = $_POST['sectorId'] ?? null;

if (!$subjectId || !$sectorId) {
    header('Location: ../views/forms/add_sectors_subject.php?message=error');
    exit;
}

try {
    // Récupérer les étudiants de la classe
    $studentManager = new Student($token);
    $students = $studentManager->fetchStudentsByClass($sectorId);
    $studentIds = array_column($students, 'student_Id');

    // Ajouter les étudiants au sujet
    $studentSubjectsManager = new StudentSubjects($token);
    $response = $studentSubjectsManager->addStudentsToSubject($subjectId, $studentIds);

    if ($response['status'] == 409) {
        header('Location: ../views/forms/add_sectors_subject.php?message=conflict');
    } else {
        header('Location: ../views/forms/add_sectors_subject.php?message=success');
    }
} catch (Exception $e) {
    header('Location: ../views/forms/add_sectors_subject.php?message=error');
}
