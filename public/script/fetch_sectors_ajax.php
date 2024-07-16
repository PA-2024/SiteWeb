<?php
// Auteur : Capdrake

// Ce script sert au rechargement du tableau dans la liste des classes
require_once '../../vendor/autoload.php';
use GeSign\Sectors;

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$sectorsManager = new Sectors($token);
$sectors = $sectorsManager->fetchSectors();

header('Content-Type: application/json');
echo json_encode($sectors);
exit;
