<?php
// Auteur : Capdrake (Bastien LEUWERS)

namespace GeSign;

class SessionManager
{
    public function checkUserLoggedIn()
    {
        session_start();

        if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
            $this->redirectToLogin();
        } else {
            $this->redirectToDashboard();
        }
    }

    protected function redirectToLogin()
    {
        header('Location: login.php');
        exit;
    }

    protected function redirectToDashboard()
    {
        header('Location: dashboard.php');
        exit;
    }
}
