<?php
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Subjects;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Bad Request: Missing subject ID']);
    exit;
}

$subjectId = $_GET['id'];

try {
    $subjectManager = new Subjects($token);
    $subject = $subjectManager->fetchSubjectById($subjectId);
    echo json_encode($subject);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
