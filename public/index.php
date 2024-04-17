<?php
// Auteur : Capdrake (Bastien LEUWERS)

require_once '../vendor/autoload.php';
use GeSign\SessionManager;

$sessionManager = new SessionManager();
$sessionManager->checkUserLoggedIn();
?>