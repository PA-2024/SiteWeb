<?php
// Auteur : Capdrake
require_once '../../vendor/autoload.php';

use GeSign\Sectors;
use GeSign\Schools;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // on récupère les données du formulaire
    $schoolId = htmlspecialchars($_POST['school_Id']);
    $roomName = htmlspecialchars($_POST['room_name']);

    $sectorsManager = new Sectors();
    $schoolManager = new Schools();

    try {
        // On récupère les détails de l'école pour laquelle nous ajoutons la salle
        $schools = $schoolManager->fetchSchools();
        $selectedSchool = null;

        foreach ($schools as $school) {
            if ($school['school_Id'] == $schoolId) {
                $selectedSchool = $school;
                break;
            }
        }

        if ($selectedSchool) {
            $result = $sectorsManager->addSector(
                $roomName,
                $selectedSchool['school_Id']
            );

            header("Location: ../views/lists/sectors_list.php?message=success");
            exit;
        } else {
            throw new Exception("École sélectionnée non trouvée.");
        }
    } catch (Exception $e) {
        header("Location: ../views/lists/sectors_list.php?message=error");
        exit;
    }
} else {
    header("Location: ../views/lists/sectors_list.php");
    exit;
}
