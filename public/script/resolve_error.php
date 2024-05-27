<?php
// Auteur : Capdrake

require_once '../../vendor/autoload.php';
use GeSign\Errors;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorId = $_POST['errorId'] ?? null;

    if ($errorId !== null) {
        $errorManager = new Errors();
        try {
            $result = $errorManager->resolveError($errorId);
            if ($result) {
                http_response_code(200);
                echo json_encode(['status' => 'success']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'La résolution de l\'erreur a échoué.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'ID de l\'erreur manquant.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
}
