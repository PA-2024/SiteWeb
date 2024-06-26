<?php
// Auteur : Capdrake
require_once '../../vendor/autoload.php';

use GeSign\Schools;

$schoolManager = new Schools();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $schoolId = htmlspecialchars($_POST['school_Id']);
    $name = htmlspecialchars($_POST['school_Name']);
    $token = htmlspecialchars($_POST['school_token']);
    $allowSite = isset($_POST['school_allowSite']) && $_POST['school_allowSite'] === '1' ? true : false;
	
	echo $schoolId, $name, $token, $allowSite;

    try {
        $updateResult = $schoolManager->updateSchool($schoolId, $name, $token, $allowSite);
        header("Location: ../views/lists/schools_list.php?message=success");
    } catch (Exception $e) {
        echo "Erreur lors de la mise à jour de l'école : " . $e->getMessage();
    }
} else {
    echo "Méthode de requête non valide.";
}
