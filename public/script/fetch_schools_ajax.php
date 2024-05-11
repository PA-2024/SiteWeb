<?php
// Auteur : Capdrake

//Ce petit script sert au rechargement du tableau dans la liste des écoles
require_once '../../vendor/autoload.php';
use GeSign\Schools;

$schoolManager = new Schools();
$schools = $schoolManager->fetchSchools();

header('Content-Type: application/json');
echo json_encode($schools);
exit;
