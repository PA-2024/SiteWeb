<?php
// Auteur : Capdrake
require_once '../../vendor/autoload.php';
use GeSign\User;

session_start();

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];
$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];

if (!$token && $user_id) {
    header('Location: ../login.php');
    exit;
}

$userManager = new User($token);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_firstname = $_POST['user_firstname'] ?? '';
    $user_lastname = $_POST['user_lastname'] ?? '';
    $user_num = $_POST['user_num'] ?? '';
    $user_email = $_POST['user_email'] ?? '';

    try {
        $userManager->updateUser($user_id, $user_firstname, $user_lastname, $user_num, $user_email);
        header('Location: ../profile.php?message=success');
        exit;
    } catch (Exception $e) {
        header('Location: ../profile.php?message=error&error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: ../profile.php');
    exit;
}
?>
