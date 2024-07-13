<?php
// Auteur : Capdrake (Bastien LEUWERS)
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\SubjectsHour;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../views/auth/login.php');
    exit;
}

$subjectHourManager = new SubjectsHour($token);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';
    switch ($action) {
        case 'add':
            try {
                $data = [
                    'subjectsHour_Subjects_Id' => $_POST['subjectsHour_Subjects_Id'],
                    'subjectsHour_Building_Id' => $_POST['subjectsHour_Building_Id'],
                    'subjectsHour_Room' => $_POST['subjectsHour_Room'],
                    'subjectsHour_DateStart' => $_POST['subjectsHour_DateStart'],
                    'subjectsHour_DateEnd' => $_POST['subjectsHour_DateEnd'],
                ];
                $subjectHourManager->create($data);
                header('Location: ../views/lists/list_subject_hours.php?message=success');
            } catch (Exception $e) {
                header('Location: ../views/lists/list_subject_hours.php?message=error');
            }
            break;

        case 'edit':
            try {
                $subjectHourId = $_POST['subjectsHour_Id'];
                $data = [
                    'subjectsHour_Id' => $_POST['subjectsHour_Id'],
                    'subjectsHour_Subjects_Id' => $_POST['subjectsHour_Subjects_Id'],
                    'subjectsHour_Bulding_Id' => $_POST['subjectsHour_Building_Id'],
                    'subjectsHour_Room' => $_POST['subjectsHour_Room'],
                    'subjectsHour_DateStart' => $_POST['subjectsHour_DateStart'],
                    'subjectsHour_DateEnd' => $_POST['subjectsHour_DateEnd'],
                ];
                $subjectHourManager->update($subjectHourId, $data);
                header('Location: ../views/lists/list_subject_hours.php?message=success2');
            } catch (Exception $e) {
                header('Location: ../views/lists/list_subject_hours.php?message=error2');
            }
            break;

        case 'delete':
            try {
                $subjectHourId = $_POST['subjectHourId'];
                $subjectHourManager->delete($subjectHourId);
                echo json_encode(['status' => 'success']);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
            break;

        default:
            header('Location: ../views/lists/list_subject_hours.php');
            break;
    }
} else {
    header('Location: ../views/lists/list_subject_hours.php');
}
?>
