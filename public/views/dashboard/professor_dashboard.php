<?php 
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

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    header('Content-Type: application/json');
    try {
        $subjectsHourManager = new SubjectsHour($token);

        $today = new DateTime();
        $startOfWeek = (clone $today)->modify('this week')->format('Y-m-d') . 'T00:00:00';
        $endOfWeek = (clone $today)->modify('this week +6 days')->format('Y-m-d') . 'T23:59:59';
        $subjectsHoursWeek = $subjectsHourManager->fetchSubjectsHoursByDateRange($startOfWeek, $endOfWeek);

        $todayStart = $today->format('Y-m-d') . 'T00:00:00';
        $todayEnd = $today->format('Y-m-d') . 'T23:59:59';
        $subjectsHoursToday = $subjectsHourManager->fetchSubjectsHoursByDateRange($todayStart, $todayEnd);

        $hoursThisWeek = 0;
        foreach ($subjectsHoursWeek as $hour) {
            $start = new DateTime($hour['subjectsHour_DateStart']);
            $end = new DateTime($hour['subjectsHour_DateEnd']);
            $hoursThisWeek += $end->getTimestamp() - $start->getTimestamp();
        }
        $hoursThisWeek = $hoursThisWeek / 3600;

        echo json_encode([
            'hoursThisWeek' => number_format($hoursThisWeek, 2),
            'subjectsHoursToday' => $subjectsHoursToday
        ]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
include '../../header/entete.php'; 
?>
<body>
    <div class="main-wrapper">
        <!-- Affichage de l'indicateur de chargement -->
        <div class="loading-overlay">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <!-- On ajoute notre header ici -->
        <?php include '../../header/entete_dashboard.php'; ?>
        <!-- On ajoute notre menu à gauche ici -->
        <?php include '../../menu/menu_prof.php'; ?>

        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../../index.php">Tableau de bord </a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Panel Professeur</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <!-- Affichage du nom de l'utilisateur -->
                <div class="good-morning-blk">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="morning-user">
                                <h2>Bonjour, <span id="user-name"></span></h2>
                                <p>Gérez les absences de vos étudiants !</p>
                            </div>
                        </div>
                        <div class="col-md-6 position-blk">
                            <div class="morning-img">
                                <img src="../../assets/img/morning-img-01.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenu chargé après le chargement des données -->
                <div class="content-loaded">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                            <div class="dash-widget">
                                <div class="dash-boxs comman-flex-center">
                                    <img src="../../assets/img/icons/clock.svg" alt="">
                                </div>
                                <div class="dash-content dash-count">
                                    <h4>Heures de cours cette semaine</h4>
                                    <h2><span class="counter-up" id="hours-this-week"></span> heures</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h4>Cours du jour</h4>
                            <div id="courses-today"></div>
                        </div>
                    </div>
                </div> <!-- Fin du contenu chargé -->
            </div>
        </div>
    </div>
    <div class="sidebar-overlay" data-reff=""></div>

    <!-- jQuery -->
    <script src="../../assets/js/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>

    <!-- Feather Js -->
    <script src="../../assets/js/feather.min.js"></script>

    <!-- Slimscroll -->
    <script src="../../assets/js/jquery.slimscroll.js"></script>

    <!-- Select2 Js -->
    <script src="../../assets/js/select2.min.js"></script>

    <!-- Datatables JS -->
    <script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../assets/plugins/datatables/datatables.min.js"></script>

    <!-- Counter-Up JS -->
    <script src="../../assets/js/jquery.waypoints.js"></script>
    <script src="../../assets/js/jquery.counterup.min.js"></script>

    <!-- Apexchart JS -->
    <script src="../../assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="../../assets/plugins/apexchart/chart-data.js"></script>

    <!-- Custom JS -->
    <script src="../../assets/js/app.js"></script>

    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3rem;
            color: #ffc107; 
        }
        .content-loaded {
            display: none;
        }
        .course-card {
            background: #fff;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 10px;
        }
        .course-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .course-time {
            font-size: 14px;
            color: #666;
        }
        .course-room {
            font-size: 14px;
            color: #666;
        }
        .course-building {
            font-size: 14px;
            color: #007bff;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('.loading-overlay').show();

            $.ajax({
                url: 'professor_dashboard.php?ajax=1',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        window.location.href = '../misc/error-500.php';
                        return;
                    }

                    $('#hours-this-week').text(data.hoursThisWeek);

                    var coursesTodayHtml = '';
                    if (data.subjectsHoursToday.length > 0) {
                        data.subjectsHoursToday.forEach(function(hour) {
                            coursesTodayHtml += `
                                <div class="course-card">
                                    <div class="course-title">${hour.subjectsHour_Subject.subjects_Name}</div>
                                    <div class="course-time">${new Date(hour.subjectsHour_DateStart).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})} - ${new Date(hour.subjectsHour_DateEnd).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}</div>
                                    <div class="course-room">Salle: ${hour.subjectsHour_Room}</div>
                                    <div class="course-building">
                                        Bâtiment: <a href="https://maps.google.com/?q=${hour.building.building_Address}" target="_blank">${hour.building.building_Name}</a>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        coursesTodayHtml = '<p>Aucun cours aujourd\'hui.</p>';
                    }
                    $('#courses-today').html(coursesTodayHtml);

                    // Initialiser Counter-Up
                    if ($.fn.counterUp) {
                        $('.counter-up').counterUp({
                            delay: 10,
                            time: 1000
                        });
                    }

                    $('.loading-overlay').hide();
                    $('.content-loaded').show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    window.location.href = '../misc/error-500.php';
                }
            });
        });
    </script>
</body>
</html>
