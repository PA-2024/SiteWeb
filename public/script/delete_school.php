<?php
// Auteur : Capdrake

//Ce script sert à faire appel pour la suppression d'une école

require_once '../../vendor/autoload.php';
use GeSign\Schools;

if (isset($_POST['schoolId'])) {
    $schoolManager = new Schools();
    try {
        $schoolManager->deleteSchool($_POST['schoolId']);
        echo 'Success';
    } catch (Exception $e) {
        http_response_code(500);
        echo 'Error: ' . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo 'Invalid request';
}
?>
