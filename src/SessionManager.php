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
                $_SESSION['token'] = $_COOKIE['token'];
                $_SESSION['school'] = $_COOKIE['school'];
                $_SESSION['schoolId'] = $_COOKIE['schoolId'];
                $this->redirectToDashboard2();
            } else {
                $this->redirectToLogin();
            }
        } else {
            $this->redirectToDashboard2();
        }
    }

    protected function redirectToLogin()
    {
        header('Location: views/auth/login.php');
        exit;
    }

    protected function redirectToLogin2()
    {
        header('Location: ../views/auth/login.php');
        exit;
    }

    protected function redirectToError()
    {
        header('Location: views/misc/error-404.php');
        exit;
    }

    public function loginUser($userId, $userName, $userRole, $token, $school, $schoolId, $remember = false)
    {
        session_start();
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $userName;
        $_SESSION['user_role'] = $userRole;
        $_SESSION['token'] = $token;
        $_SESSION['school'] = $school;
        $_SESSION['schoolId'] = $schoolId;

        if ($remember) {
            setcookie('user_logged_in', 'true', time() + (86400 * 30), "/"); // 30 jours
            setcookie('user_id', $userId, time() + (86400 * 30), "/");
            setcookie('user_name', $userName, time() + (86400 * 30), "/");
            setcookie('user_role', $userRole, time() + (86400 * 30), "/");
            setcookie('token', $token, time() + (86400 * 30), "/");
            setcookie('school', $school, time() + (86400 * 30), "/");
            setcookie('schoolId', $school, time() + (86400 * 30), "/");
        }

        $this->redirectToDashboard();
    }

    protected function redirectToDashboard()
    {
        if (!isset($_SESSION['user_role'])) {
            $this->redirectToError();
        }

        switch ($_SESSION['user_role']) {
            case 'Admin':
                header('Location: ../dashboard/admin_dashboard.php');
                break;
            case 'Gestion Ecole':
                header('Location: ../dashboard/director_dashboard.php');
                break;
            case 'Professeur':
                header('Location: ../dashboard/professor_dashboard.php');
                break;
            case 'Eleve':
                header('Location: ../dashboard/student_dashboard.php');
                break;
            default:
                $this->redirectToError();
        }
        exit;
    }

    protected function redirectToDashboard2()
    {
        if (!isset($_SESSION['user_role'])) {
            $this->redirectToError();
        }

        switch ($_SESSION['user_role']) {
            case 'Admin':
                header('Location: views/dashboard/admin_dashboard.php');
                break;
            case 'Gestion Ecole':
                header('Location: views/dashboard/director_dashboard.php');
                break;
            case 'Professeur':
                header('Location: views/dashboard/professor_dashboard.php');
                break;
            case 'Eleve':
                header('Location: views/dashboard/student_dashboard.php');
                break;
            default:
                $this->redirectToError();
        }
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
        setcookie('token', '', time() - 3600, "/");
        setcookie('school', '', time() - 3600, "/");
        setcookie('schoolId', '', time() - 3600, "/");

        $this->redirectToLogin2();
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
                $_SESSION['token'] = $_COOKIE['token'];
                $_SESSION['school'] = $_COOKIE['school'];
                $_SESSION['schoolId'] = $_COOKIE['schoolId'];
            } else {
                $this->redirectToError();
            }
        }
    }

    public function checkUserRole($requiredRole)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $requiredRole) {
            header('Location: ../misc/error-404.php');
            exit;
        }
    }
}
