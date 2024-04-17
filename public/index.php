<?php
// Auteur : Capdrake (Bastien LEUWERS)

require_once '../vendor/autoload.php';

if (class_exists("GeSign\SessionManager")) {
    echo "La classe SessionManager existe.";
} else {
    echo "La classe SessionManager n'existe pas.";
}
use GeSign\SessionManager;

$sessionManager = new SessionManager();
$sessionManager->checkUserLoggedIn();
?>