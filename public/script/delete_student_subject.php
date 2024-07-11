<?php
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\StudentSubjects;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

if (!isset($_POST['studentId']) || !isset($_POST['subjectId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Bad Request: Missing parameters']);
    exit;
}

$studentId = $_POST['studentId'];
$subjectId = $_POST['subjectId'];

echo "<script> alert('".$studentId."'); </script>";

try {
    $subjectManager = new StudentSubjects($token);
    $subjectManager->deleteStudentSubject($studentId, $subjectId);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
