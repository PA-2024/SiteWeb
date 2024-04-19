<?php
// Auteur : Capdrake (Bastien LEUWERS)

header("HTTP/1.0 404 Not Found");
include 'error-404.php';
exit();

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