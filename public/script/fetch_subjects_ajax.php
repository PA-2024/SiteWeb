<?php
// Auteur : Capdrake (Bastien LEUWERS)
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Subjects;
use GeSign\Schools;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$schoolName = $_SESSION['school'] ?? $_COOKIE['school'];

if (!$schoolName) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'School name not available']);
    exit;
}

$schoolManager = new Schools();
$subjectManager = new Subjects($token);

try {
    $school = $schoolManager->fetchSchoolByName($schoolName);
    $subjects = $subjectManager->fetchSubjects();

    header('Content-Type: application/json');
    echo json_encode($subjects);
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
