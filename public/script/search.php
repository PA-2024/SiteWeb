<?php
// Auteur : Capdrake (Bastien LEUWERS)
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();

// Récupérer la requête de recherche (marche plus)
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';


if (!empty($searchQuery)) {
    $searchQueryLower = strtolower($searchQuery);

    // Exemples de redirections basées sur la requête
    if (strpos($searchQueryLower, 'prof') !== false) {
        header('Location: professor_list.php');
        exit;
    } elseif (strpos($searchQueryLower, 'élève') !== false || strpos($searchQueryLower, 'etudiant') !== false) {
        header('Location: student_list.php');
        exit;
    } elseif (strpos($searchQueryLower, 'cours') !== false) {
        header('Location: subjects_list.php');
        exit;
    } elseif (strpos($searchQueryLower, 'ecole') !== false) {
        header('Location: school_list.php');
        exit;
    } else {
        // Redirection par défaut vers une page de résultats générique
        header('Location: search_results.php?query=' . urlencode($searchQuery));
        exit;
    }
} else {
    // Rediriger vers la page d'accueil si la requête est vide
    header('Location: index.php');
    exit;
}
?>
