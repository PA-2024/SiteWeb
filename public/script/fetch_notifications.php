<?php
require_once '../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\SubjectsHour;
use GeSign\QCM;

$sessionManager = SessionManager::getInstance();
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
    if ($role === 'Professeur') {
        $subjectsHoursToday = $subjectsHourManager->fetchSubjectsHoursByDateRange($todayStart, $todayEnd);
    } else {
        $subjectsHoursToday = $subjectsHourManager->fetchByDateRange($todayStart, $todayEnd);
    }
    
    foreach ($subjectsHoursToday as $subjectHour) {
        $dateStart = new DateTime($subjectHour['subjectsHour_DateStart']);
        $formattedDateStart = $dateStart->format('H:i');
        
        $notifications[] = [
            'type' => 'course',
            'message' => "Vous avez cours à <strong>{$formattedDateStart}</strong> dans le bâtiment <strong>{$subjectHour['building']['building_Name']}</strong> en salle <strong>{$subjectHour['subjectsHour_Room']}</strong>."
        ];
    }
    
    // Fetch today's QCMs
    
    $qcmsToday = $qcmManager->fetchQCMByRange($todayStart, $todayEnd);

    foreach ($qcmsToday as $qcm) {
        //$qcmStart = new DateTime($qcm['qcm_Start']);
        //$formattedQcmStart = $qcmStart->format('H:i');

        $notifications[] = [
            'type' => 'qcm',
            'message' => "Un QCM est prévu aujourd'hui à <strong>not good</strong>."
        ];
    }

}

echo json_encode($notifications);
?>
