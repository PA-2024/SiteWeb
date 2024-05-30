<?php
// Auteur : Capdrake

// Ce script sert au rechargement du tableau dans la liste des classes
require_once '../../vendor/autoload.php';
use GeSign\Sectors;

$sectorsManager = new Sectors();
$sectors = $sectorsManager->fetchSectors();

header('Content-Type: application/json');
echo json_encode($sectors);
exit;
