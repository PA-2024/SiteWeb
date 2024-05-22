<?php
// Auteur : Capdrake (Bastien LEUWERS)

namespace GeSign;

class SessionManager
{
    public function checkUserLoggedIn()
    {
        session_start();

        if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
            if (isset($_COOKIE['user_logged_in']) && $_COOKIE['user_logged_in'] === 'true') {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $_COOKIE['user_id'];
                $_SESSION['user_name'] = $_COOKIE['user_name'];
                $_SESSION['user_role'] = $_COOKIE['user_role'];
                $this->redirectToDashboard();
            } else {
                $this->redirectToLogin();
            }
        } else {
            $this->redirectToDashboard();
        }
    }

    protected function redirectToLogin()
    {
        header('Location: login.php');
        exit;
    }

    protected function redirectToError()
    {
        header('Location: error-500.php');
        exit;
    }

    public function loginUser($userId, $userName, $userRole, $remember = false)
    {
        session_start();
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $userName;
        $_SESSION['user_role'] = $userRole;

        if ($remember) {
            setcookie('user_logged_in', 'true', time() + (86400 * 30), "/"); // 30 jours
            setcookie('user_id', $userId, time() + (86400 * 30), "/");
            setcookie('user_name', $userName, time() + (86400 * 30), "/");
            setcookie('user_role', $userRole, time() + (86400 * 30), "/");
        }

        $this->redirectToDashboard();
    }

    protected function redirectToDashboard()
    {
        header('Location: admin_dashboard.php');
        exit;
    }

    public function logoutUser()
    {
        session_start();
        session_destroy();

        setcookie('user_logged_in', '', time() - 3600, "/");
        setcookie('user_id', '', time() - 3600, "/");
        setcookie('user_name', '', time() - 3600, "/");
        setcookie('user_role', '', time() - 3600, "/");

        $this->redirectToLogin();
    }

    public function restrictAccessToLoginUsers()
    {
        session_start();

        if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
            if (isset($_COOKIE['user_logged_in']) && $_COOKIE['user_logged_in'] === 'true') {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $_COOKIE['user_id'];
                $_SESSION['user_name'] = $_COOKIE['user_name'];
                $_SESSION['user_role'] = $_COOKIE['user_role'];
            } else {
                $this->redirectToError();
            }
        }
    }

    public function checkUserRole($requiredRole)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $requiredRole) {
            header('Location: error-500.php');
            exit;
        }
    }
}
