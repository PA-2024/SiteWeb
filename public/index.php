<?php
// Auteur : Capdrake (Bastien LEUWERS)

require_once '../vendor/autoload.php';

use GeSign\SessionManager;

$sessionManager = SessionManager::getInstance();
$sessionManager->checkUserLoggedIn();
?>
