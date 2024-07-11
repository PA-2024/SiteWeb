<?php
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Presence;

$sessionManager = new SessionManager();
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

try {
    $presenceManager = new Presence($token);
    $presences = $presenceManager->fetchUnconfirmedPresences();
    echo json_encode($presences);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
