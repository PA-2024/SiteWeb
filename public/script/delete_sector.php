<?php
// Auteur : Capdrake

//Ce script sert à faire appel pour la suppression d'une classe

require_once '../../vendor/autoload.php';
use GeSign\Sectors;

if (isset($_POST['sectorId'])) {
    $sectorsManager = new Sectors();
    try {
        $sectorsManager->deleteSector($_POST['sectorId']);
        echo 'Success';
    } catch (Exception $e) {
        http_response_code(500);
        echo 'Error: ' . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo 'Invalid request';
}
