<?php
// Auteur : Capdrake (Bastien LEUWERS)
session_start();

// Vérifier si l'utilisateur est connecté.
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    // Si l'utilisateur n'est pas connecté, on redirige vers login.php
    header('Location: login.php');
    exit;
} else {
    // Si l'utilisateur est connecté, on redirige vers une autre page
    header('Location: login.php');
    exit;
}
?>
