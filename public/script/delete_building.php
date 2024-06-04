<?php
// Auteur : Capdrake

require_once '../../vendor/autoload.php';
use GeSign\Buildings;

if (isset($_POST['buildingId'])) {
    $buildingManager = new Buildings();
    try {
        $result = $buildingManager->deleteBuilding($_POST['buildingId']);
        if ($result === true) {
            echo 'Success';
        } else {
            http_response_code(500);
            echo 'Error: ' . $result;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo 'Error: ' . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo 'Invalid request';
}
?>
