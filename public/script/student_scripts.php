<?php

include '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Students;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$studentManager = new Students($token);

if ($_GET['action'] == 'delete') {
    $studentId = $_POST['studentId'] ?? null;

    if ($studentId) {
        $result = $studentManager->deleteStudent($studentId);

        if (isset($result['error'])) {
            echo json_encode(['error' => $result['error']]);
        } else {
            echo json_encode(['success' => true]);
        }
    } else {
        echo json_encode(['error' => 'Invalid student ID']);
    }
}
?>
