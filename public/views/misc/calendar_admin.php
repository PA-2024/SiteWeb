<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Subjects;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$schoolName = $_SESSION['school'] ?? $_COOKIE['school'];

if (!$schoolName) {
    header('Location: ../misc/error-404.php');
    exit;
}

$subjectManager = new Subjects($token);
$subjects = $subjectManager->fetchSubjects();
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
    <!-- Feathericon CSS -->
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
        <?php include '../../menu/menu_gestion.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-8 col-4">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../../index.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">Calendrier</li>
                        </ul>
                    </div>
                    <div class="col-sm-4 col-8 text-end m-b-30">
                        <select id="subjectSelect" class="form-control">
                            <option value="">Sélectionner un cours</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo htmlspecialchars($subject['subjects_Id']); ?>"><?php echo htmlspecialchars($subject['subjects_Name']); ?></option>
                            <?php endforeach; ?>
                        </select>
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
            $('#calendar1').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                locale: 'fr',
                editable: false,
                eventRender: function (event, element) {
                    element.popover({
                        title: event.title,
                        content: `
                            <strong>Nom du cours:</strong> ${event.title}<br>
                            <strong>Salle:</strong> ${event.room}<br>
                            <strong>Début:</strong> ${event.start.format('YYYY-MM-DD HH:mm')}<br>
                            <strong>Fin:</strong> ${event.end.format('YYYY-MM-DD HH:mm')}
                        `,
                        trigger: 'hover',
                        placement: 'top',
                        container: 'body',
                        html: true
                    });
                },
                eventClick: function (event) {
                    $('#event-modal .modal-body').html(`
                        <p><strong>Nom du cours:</strong> ${event.title}</p>
                        <p><strong>Salle:</strong> ${event.room}</p>
                        <p><strong>Début:</strong> ${event.start.format('YYYY-MM-DD HH:mm')}</p>
                        <p><strong>Fin:</strong> ${event.end.format('YYYY-MM-DD HH:mm')}</p>
                    `);
                    $('#event-modal').modal('show');
                }
            });

            $('#subjectSelect').on('change', function () {
                var subjectId = $(this).val();
                if (subjectId) {
                    $.ajax({
                        url: '../../script/fetch_subject_hours_ajax_2.php',
                        type: 'GET',
                        dataType: 'json',
                        data: { subjectId: subjectId },
                        success: function (data) {
                            var events = data.map(function (hour) {
                                return {
                                    title: hour.subjectsHour_Subjects.subjects_Name,
                                    start: hour.subjectsHour_DateStart,
                                    end: hour.subjectsHour_DateEnd,
                                    room: hour.subjectsHour_Room,
                                    description: hour.subjectsHour_Subjects.subjects_Description || ''
                                };
                            });
                            $('#calendar1').fullCalendar('removeEvents');
                            $('#calendar1').fullCalendar('addEventSource', events);
                        },
                        error: function () {
                            alert('Impossible de charger les heures de cours.');
                        }
                    });
                } else {
                    $('#calendar').fullCalendar('removeEvents');
                }
            });
        });
    </script>
</body>

</html>
