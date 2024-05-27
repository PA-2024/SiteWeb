<?php
// Auteur : Capdrake

require_once '../../vendor/autoload.php';
use GeSign\Errors;

$errorManager = new Errors();
$errors = $errorManager->fetchErrors();
echo json_encode($errors);
