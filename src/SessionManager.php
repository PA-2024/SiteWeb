<?php
// Auteur : Capdrake (Bastien LEUWERS)

namespace GeSign;

class SessionManager
{
    public function __construct()
    {
        session_start();
    }

    public function checkUserLoggedIn()
    {
        if (!$this->isUserLoggedIn()) {
            $this->attemptLoginFromCookies();
        }

        if ($this->isUserLoggedIn()) {
            $this->redirectToDashboard();
        } else {
            $this->redirectToLogin();
        }
    }

    private function isUserLoggedIn()
    {
        return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
    }

    private function attemptLoginFromCookies()
    {
        if (isset($_COOKIE['user_logged_in']) && $_COOKIE['user_logged_in'] === 'true') {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $_COOKIE['user_id'];
            $_SESSION['user_name'] = $_COOKIE['user_name'];
            $_SESSION['user_role'] = $_COOKIE['user_role'];
            $_SESSION['token'] = $_COOKIE['token'];
            $_SESSION['school'] = $_COOKIE['school'];
            $_SESSION['schoolId'] = $_COOKIE['schoolId'];
        }
    }

    protected function redirectToLogin()
    {
        header('Location: /views/auth/login.php');
        exit;
    }

    protected function redirectToError()
    {
        header('Location: /views/misc/error-404.php');
        exit;
    }

    public function loginUser($userId, $userName, $userRole, $token, $school, $schoolId, $remember = false)
    {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $userName;
        $_SESSION['user_role'] = $userRole;
        $_SESSION['token'] = $token;
        $_SESSION['school'] = $school;
        $_SESSION['schoolId'] = $schoolId;

        if ($remember) {
            $this->setRememberMeCookies($userId, $userName, $userRole, $token, $school, $schoolId);
        }

        $this->redirectToDashboard();
    }

    private function setRememberMeCookies($userId, $userName, $userRole, $token, $school, $schoolId)
    {
        $cookieExpiration = time() + (86400 * 30); // 30 jours
        setcookie('user_logged_in', 'true', $cookieExpiration, "/");
        setcookie('user_id', $userId, $cookieExpiration, "/");
        setcookie('user_name', $userName, $cookieExpiration, "/");
        setcookie('user_role', $userRole, $cookieExpiration, "/");
        setcookie('token', $token, $cookieExpiration, "/");
        setcookie('school', $school, $cookieExpiration, "/");
        setcookie('schoolId', $schoolId, $cookieExpiration, "/");
    }

    protected function redirectToDashboard()
    {
        if (!isset($_SESSION['user_role'])) {
            $this->redirectToError();
        }

        $role = $_SESSION['user_role'];
        $dashboardMap = [
            'Admin' => '/views/dashboard/admin_dashboard.php',
            'Gestion Ecole' => '/views/dashboard/director_dashboard.php',
            'Professeur' => '/views/dashboard/professor_dashboard.php',
            'Eleve' => '/views/dashboard/student_dashboard.php'
        ];

        if (isset($dashboardMap[$role])) {
            header('Location: ' . $dashboardMap[$role]);
            exit;
        } else {
            $this->redirectToError();
        }
    }

    public function logoutUser()
    {
        session_destroy();
        $this->clearCookies();
        $this->redirectToLogin();
    }

    private function clearCookies()
    {
        $cookies = ['user_logged_in', 'user_id', 'user_name', 'user_role', 'token', 'school', 'schoolId'];
        foreach ($cookies as $cookie) {
            setcookie($cookie, '', time() - 3600, "/");
        }
    }

    public function restrictAccessToLoginUsers()
    {
        if (!$this->isUserLoggedIn()) {
            $this->attemptLoginFromCookies();
            if (!$this->isUserLoggedIn()) {
                $this->redirectToError();
            }
        }
    }

    public function checkUserRole($requiredRole)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $requiredRole) {
            $this->redirectToError();
        }
    }
}
