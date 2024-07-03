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
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade none-border" id="event-modal">
                            <div class="modal-dialog">
                                <div class="modal-content modal-md">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Détail de l'événement</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body"></div>
                                    <div class="modal-footer text-center">
                                        <button type="button" class="btn btn-primary submit-btn save-event" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="add_event" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content modal-md">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Ajouter un événement</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="input-block">
                                                <label>Nom de l'événement <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text">
                                            </div>
                                            <div class="input-block">
                                                <label>Date de l'événement <span class="text-danger">*</span></label>
                                                <div class="cal-icon">
                                                    <input class="form-control datetimepicker" type="text">
                                                </div>
                                            </div>
                                            <div class="m-t-20 text-center">
                                                <button class="btn btn-primary submit-btn">Créer l'événement</button>
                                            </div>
                                        </form>
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
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                locale: 'fr',
                editable: false,
                events: [
                    {
                        title: 'Cours de Mathématiques',
                        start: '2024-07-02T10:00:00',
                        end: '2024-07-02T12:00:00',
                        room: 'A101',
                        color: '#007bff'
                    },
                    {
                        title: 'Cours de Physique',
                        start: '2024-07-03T14:00:00',
                        end: '2024-07-03T16:00:00',
                        room: 'B202',
                        color: '#007bff'
                    }
                ],
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
                            $('#calendar').fullCalendar('removeEvents');
                            $('#calendar').fullCalendar('addEventSource', events);
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
