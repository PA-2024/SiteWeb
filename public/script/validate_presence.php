<?php
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Presence;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $presenceId = $_POST['presence_id'];
    echo '<script> alert("'.$presenceId.'"); </script>';
    try {
        $presenceManager = new Presence($token);
        $presenceManager->validatePresence($presenceId);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
