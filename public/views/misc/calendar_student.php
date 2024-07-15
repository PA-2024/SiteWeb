<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\SubjectsHour;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Eleve');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$subjectsHourManager = new SubjectsHour($token);
$startDate = (new DateTime('-1 year'))->format('Y-m-d') . 'T00:00:00';
$endDate = (new DateTime('+1 year'))->format('Y-m-d') . 'T23:59:59';
$subjectsHours = $subjectsHourManager->fetchByDateRange($startDate, $endDate);

$events = [];
foreach ($subjectsHours as $subjectsHour) {
    $events[] = [
        'title' => htmlspecialchars($subjectsHour['subject']['subjects_Name'], ENT_QUOTES, 'UTF-8'),
        'start' => (new DateTime($subjectsHour['subjectsHour_DateStart']))->format('Y-m-d\TH:i:s'),
        'end' => (new DateTime($subjectsHour['subjectsHour_DateEnd']))->format('Y-m-d\TH:i:s'),
        'room' => htmlspecialchars($subjectsHour['subjectsHour_Room'], ENT_QUOTES, 'UTF-8'),
        'building' => htmlspecialchars($subjectsHour['building']['building_Name'] . ', ' . $subjectsHour['building']['building_Address'] . ', ' . $subjectsHour['building']['building_City'], ENT_QUOTES, 'UTF-8'),
        'mapLink' => 'https://www.google.com/maps/search/?api=1&query=' . urlencode($subjectsHour['building']['building_Address'] . ', ' . $subjectsHour['building']['building_City']),
        'description' => "
            <strong>Salle:</strong> " . htmlspecialchars($subjectsHour['subjectsHour_Room'], ENT_QUOTES, 'UTF-8') . "<br>
            <strong>Professeur:</strong> " . htmlspecialchars($subjectsHour['subject']['teacher']['user_firstname'] . ' ' . $subjectsHour['subject']['teacher']['user_lastname'], ENT_QUOTES, 'UTF-8') . "<br>
            <strong>Bâtiment:</strong> <a href='https://www.google.com/maps/search/?api=1&query=" . urlencode($subjectsHour['building']['building_Address'] . ', ' . $subjectsHour['building']['building_City']) . "' target='_blank'>" . htmlspecialchars($subjectsHour['building']['building_Name'], ENT_QUOTES, 'UTF-8') . "</a><br>
            <strong>Début:</strong> " . (new DateTime($subjectsHour['subjectsHour_DateStart']))->format('H:i') . "<br>
            <strong>Fin:</strong> " . (new DateTime($subjectsHour['subjectsHour_DateEnd']))->format('H:i')
    ];
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="../../assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/fullcalendar.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="../../assets/css/feather.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <style>
        #calendar {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
        }

        .fc-event {
            border: 1px solid #007bff;
            background-color: #007bff;
            color: #ffffff;
        }

        .fc-event:hover {
            background-color: #0056b3;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
    </style>
    <script type='importmap'>
        {
            "imports": {
                "@fullcalendar/core": "https://cdn.skypack.dev/@fullcalendar/core@6.1.14",
                "@fullcalendar/daygrid": "https://cdn.skypack.dev/@fullcalendar/daygrid@6.1.14",
                "@fullcalendar/timegrid": "https://cdn.skypack.dev/@fullcalendar/timegrid@6.1.14",
                "@fullcalendar/list": "https://cdn.skypack.dev/@fullcalendar/list@6.1.14",
                "@fullcalendar/interaction": "https://cdn.skypack.dev/@fullcalendar/interaction@6.1.14"
            }
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type='module'>
        import { Calendar } from '@fullcalendar/core'
        import dayGridPlugin from '@fullcalendar/daygrid'
        import timeGridPlugin from '@fullcalendar/timegrid'
        import listPlugin from '@fullcalendar/list'
        import interactionPlugin from '@fullcalendar/interaction'

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar1');
            const calendar = new Calendar(calendarEl, {
                plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: 'fr',
                buttonText: {
                    today: 'Aujourd\'hui',
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour'
                },
                firstDay: 1,
                allDaySlot: false,
                events: <?php echo json_encode($events); ?>,
                eventDidMount: function(info) {
                    new bootstrap.Tooltip(info.el, {
                        title: info.event.extendedProps.description,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body',
                        html: true
                    });
                },
                eventClick: function(info) {
                    const event = info.event;
                    const modalBody = document.querySelector('#event-modal .modal-body');
                    modalBody.innerHTML = `
                        <p><strong>Nom du cours:</strong> ${event.title}</p>
                        <p><strong>Salle:</strong> ${event.extendedProps.room}</p>
                        <p><strong>Professeur:</strong> ${event.extendedProps.professor}</p>
                        <p><strong>Bâtiment:</strong> <a href="${event.extendedProps.mapLink}" target="_blank">${event.extendedProps.building}</a></p>
                        <p><strong>Début:</strong> ${moment(event.start).format('DD/MM/YYYY HH:mm')}</p>
                        <p><strong>Fin:</strong> ${moment(event.end).format('DD/MM/YYYY HH:mm')}</p>
                    `;
                    $('#event-modal').modal('show');
                }
            });
            calendar.render();
        });
    </script>
</head>

<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_student.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-8 col-4">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/student_dashboard.php">Tableau de bord </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">Calendrier de l'étudiant</li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box mb-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="calendar1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade none-border" id="event-modal">
                            <div class="modal-dialog">
                                <div class="modal-content modal-md">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Détails du cours</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body"></div>
                                    <div class="modal-footer text-center">
                                        <button type="button" class="btn btn-primary submit-btn save-event" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sidebar-overlay" data-reff=""></div>
    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/jquery.slimscroll.js"></script>
    <script src="../../assets/js/select2.min.js"></script>
    <script src="../../assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="../../assets/js/app.js"></script>
</body>

</html>
