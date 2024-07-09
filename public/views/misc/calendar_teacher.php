<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\SubjectsHour;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Professeur');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$subjectsHourManager = new SubjectsHour($token);
$startDate = (new DateTime('-1 year'))->format('Y-m-d') . 'T00:00:00';
$endDate = (new DateTime('+1 year'))->format('Y-m-d') . 'T23:59:59';
$subjectsHours = $subjectsHourManager->fetchSubjectsHoursByDateRange($startDate, $endDate);

echo '<script>console.log(' . json_encode($subjectsHours) . ');</script>';
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
</head>

<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_prof.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-8 col-4">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard/professor_dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">Calendrier du professeur</li>
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
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/jquery.slimscroll.js"></script>
    <script src="../../assets/js/select2.min.js"></script>
    <script src="../../assets/js/moment.min.js"></script>
    <script src="../../assets/js/jquery-ui.min.js"></script>
    <script src="../../assets/js/fullcalendar.min.js"></script>
    <script src="../../assets/js/jquery.fullcalendar.js"></script>
    <script src="../../assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="../../assets/js/app.js"></script>
    <script>
        $(document).ready(function () {
            const events = [
                <?php foreach ($subjectsHours as $subjectsHour): ?>
                {
                    title: '<?php echo addslashes($subjectsHour['subjectsHour_Subject']['subjects_Name']); ?>',
                    start: '<?php echo (new DateTime($subjectsHour['subjectsHour_DateStart']))->format('Y-m-d\TH:i:s'); ?>',
                    end: '<?php echo (new DateTime($subjectsHour['subjectsHour_DateEnd']))->format('Y-m-d\TH:i:s'); ?>',
                    room: '<?php echo addslashes($subjectsHour['subjectsHour_Room']); ?>',
                    building: '<?php echo addslashes($subjectsHour['building']['building_Name'] . ', ' . $subjectsHour['building']['building_Address'] . ', ' . $subjectsHour['building']['building_City']); ?>',
                    mapLink: 'https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($subjectsHour['building']['building_Address'] . ', ' . $subjectsHour['building']['building_City']); ?>',
                    color: '#007bff',
                    description: `
                        <strong>Salle:</strong> <?php echo addslashes($subjectsHour['subjectsHour_Room']); ?><br>
                        <strong>Bâtiment:</strong> <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($subjectsHour['building']['building_Address'] . ', ' . $subjectsHour['building']['building_City']); ?>" target="_blank"><?php echo addslashes($subjectsHour['building']['building_Name']); ?></a><br>
                        <strong>Début:</strong> <?php echo (new DateTime($subjectsHour['subjectsHour_DateStart']))->format("H:i"); ?><br>
                        <strong>Fin:</strong> <?php echo (new DateTime($subjectsHour['subjectsHour_DateEnd']))->format("H:i"); ?>
                    `
                },
                <?php endforeach; ?>
            ];

            console.log(events);

            $('#calendar1').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                locale: 'fr',
                timeFormat: 'H(:mm)',
                editable: false,
                events: events,
                eventRender: function (event, element) {
                    if (event.title && event.start && event.end) {
                        element.find('.fc-title').html(`
                            <strong>${event.title}</strong><br/>
                            ${moment(event.start).format("HH:mm")} - ${moment(event.end).format("HH:mm")}
                        `);
                        element.popover({
                            title: event.title,
                            content: event.description,
                            trigger: 'hover',
                            placement: 'top',
                            container: 'body',
                            html: true
                        });
                    } else {
                        console.error('Event data missing:', event);
                    }
                },
                eventClick: function (event) {
                    if (event.title && event.start && event.end) {
                        $('#event-modal .modal-body').html(`
                            <p><strong>Nom du cours:</strong> ${event.title}</p>
                            <p><strong>Salle:</strong> ${event.room}</p>
                            <p><strong>Bâtiment:</strong> <a href="${event.mapLink}" target="_blank">${event.building}</a></p>
                            <p><strong>Début:</strong> ${moment(event.start).format('YYYY-MM-DD HH:mm')}</p>
                            <p><strong>Fin:</strong> ${moment(event.end).format('YYYY-MM-DD HH:mm')}</p>
                        `);
                        $('#event-modal').modal('show');
                    } else {
                        console.error('Event data missing:', event);
                    }
                }
            });
        });
    </script>
</body>

</html>
