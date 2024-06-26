<?php
// Auteur : Capdrake (Bastien LEUWERS)
require_once '../../vendor/autoload.php';
use GeSign\SessionManager;
use GeSign\Subjects;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$token = $_SESSION['token'] ?? $_COOKIE['token'];
$subjectManager = new Subjects($token);

$action = $_GET['action'] ?? '';
switch ($action) {
    case 'add':
        $subjectName = $_POST['subjectName'];
        $teacherId = $_POST['teacherId'];
        try {
            $subjectManager->createSubject($subjectName, $teacherId);
            header('Location: ../views/lists/subjects_list.php?message=success');
        } catch (Exception $e) {
            header('Location: ../views/forms/add_subject.php?message=error&error=' . urlencode($e->getMessage()));
        }
        break;

    case 'edit':
        $subjectId = $_GET['id'];
        $subjectName = $_POST['subjectName'];
        $teacherId = $_POST['teacherId'];
        try {
            $subjectManager->updateSubject($subjectId, $subjectName, $teacherId);
            header('Location: ../views/lists/subjects_list.php?message=success');
        } catch (Exception $e) {
            header('Location: ../views/forms/edit_subject.php?id=' . $subjectId . '&message=error&error=' . urlencode($e->getMessage()));
        }
        break;

    case 'delete':
        $subjectId = $_POST['subjectId'] ?? null;

        if (!$subjectId) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Missing subject ID']);
            exit;
        }
    
        try {
            $result = $subjectManager->deleteSubject($subjectId);
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                header('HTTP/1.1 500 Internal Server Error');
                echo json_encode(['error' => 'Failed to delete subject']);
            }
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    default:
        header('Location: ../error-500.php');
        break;
}
