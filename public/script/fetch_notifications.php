<?php
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\SubjectsHour;
use GeSign\QCM;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();

$token = $_SESSION['token'] ?? $_COOKIE['token'];
$userId = $_SESSION['user_id'];
$role = $_SESSION['user_role'];

if (!$token) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

$notifications = [];

if ($role === 'Professeur' || $role === 'Eleve') {
    $subjectsHourManager = new SubjectsHour($token);
    $qcmManager = new QCM($token);
    
    $today = new DateTime();
    $todayStart = $today->format('Y-m-d') . 'T00:00:00';
    $todayEnd = $today->format('Y-m-d') . 'T23:59:59';
    
    // Fetch today's subjects
    $subjectsHoursToday = $subjectsHourManager->fetchSubjectsHoursByDateRange($todayStart, $todayEnd);
    
    foreach ($subjectsHoursToday as $subjectHour) {
        $notifications[] = [
            'type' => 'course',
            'message' => "Vous avez cours à {$subjectHour['subjectsHour_DateStart']} dans le bâtiment {$subjectHour['building']['building_Name']} en salle {$subjectHour['subjectsHour_Room']}."
        ];
    }
    
    // Fetch today's QCMs
    /*$qcmsToday = $qcmManager->fetchQCMByRange($todayStart, $todayEnd);

    foreach ($qcmsToday as $qcm) {
        $notifications[] = [
            'type' => 'qcm',
            'message' => "Un QCM est prévu aujourd'hui à {$qcm['qcm_Start']}."
        ];
    }*/
}

echo json_encode($notifications);
?>
