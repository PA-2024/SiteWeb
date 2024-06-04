<?php
// Auteur : Capdrake

require_once '../../vendor/autoload.php';
use GeSign\Buildings;

if (isset($_POST['buildingName']) && isset($_POST['buildingCity']) && isset($_POST['buildingAddress']) && isset($_POST['buildingSchool'])) {
    $buildingManager = new Buildings();
    try {
        $result = $buildingManager->createBuilding(
            $_POST['buildingCity'],
            $_POST['buildingName'],
            $_POST['buildingAddress'],
            $_POST['buildingSchool']
        );
        if ($result === true) {
            header('Location: ../buildings_list.php?message=success');
        } else {
            header('Location: ../buildings_list.php?message=error&error=' . urlencode($result));
        }
        exit;
    } catch (Exception $e) {
        header('Location: ../buildings_list.php?message=error&error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: ../buildings_list.php?message=error&error=' . urlencode('Requête invalide'));
    exit;
}
?>
