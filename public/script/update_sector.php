<?php
// Auteur : Capdrake

// Ce script sert à faire appel pour la mise à jour d'une classe

require_once '../../vendor/autoload.php';
use GeSign\Sectors;

if (isset($_POST['sectorId']) && isset($_POST['sectorsName'])) {

    // Récupération du token de l'utilisateur connecté
    $token = $_SESSION['token'] ?? $_COOKIE['token'];

    if (!$token) {
        header('Location: ../auth/login.php');
        exit;
    }
    $sectorsManager = new Sectors($token);
    try {
        $sectorsManager->updateSector(
            $_POST['sectorId'],
            $_POST['sectorsName'],
            $_POST['schoolId']
        );
        header('Location: ../views/lists/sectors_list.php?message=success2');
        exit;
    } catch (Exception $e) {
        header('Location: ../views/lists/sectors_list.php?message=error2&error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: ../views/lists/sectors_list.php?message=error2&error=' . urlencode('Requête invalide'));
    exit;
}
