<?php 
require_once '../../../vendor/autoload.php';

use GeSign\Schools;
use GeSign\SessionManager;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Admin');

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    header('Content-Type: application/json');
    try {
        $schoolManager = new Schools();
        $schools = $schoolManager->fetchSchools();

        $currentMonth = date('m');
        $currentYear = date('Y');
        $previousMonth = date('m', strtotime("-1 month"));
        $previousYear = ($currentMonth == 1) ? $currentYear - 1 : $currentYear;

        $currentMonthCount = $schoolManager->countSchoolsByMonth($currentYear, $currentMonth);
        $previousMonthCount = $schoolManager->countSchoolsByMonth($previousYear, $previousMonth);

        if ($previousMonthCount > 0) {
            $percentageChange = (($currentMonthCount - $previousMonthCount) / $previousMonthCount) * 100;
        } else {
            $percentageChange = ($currentMonthCount > 0) ? 100 : 0;
        }

        $userName = $_SESSION['user_name'];

        echo json_encode([
            'schools' => $schools,
            'currentMonthCount' => $currentMonthCount,
            'percentageChange' => $percentageChange,
            'userName' => $userName
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
        <?php include '../../menu/menu_admin.php'; ?>

        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../../index.php">Tableau de bord </a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Admin</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="good-morning-blk">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="morning-user">
                                <h2>Bonjour, <span id="user-name"></span></h2>
                                <p>Bienvenue sur votre tableau de bord. Passez une excellente journée au travail !</p>
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
                                    <img src="../../assets/img/icons/profile-add.svg" alt="">
                                </div>
                                <div class="dash-content dash-count">
                                    <h4>Nouveaux membres</h4>
                                    <h2><span class="counter-up" id="current-month-count"></span></h2>
                                    <p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i>20%</span> vs dernier mois</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                            <div class="dash-widget">
                                <div class="dash-boxs comman-flex-center">
                                    <img src="../../assets/img/icons/calendar.svg" alt="">
                                </div>
                                <div class="dash-content dash-count">
                                    <h4>Absences</h4>
                                    <h2><span class="counter-up" ></span></h2>
                                    <p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i>40%</span> vs dernier mois</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                            <div class="dash-widget">
                                <div class="dash-boxs comman-flex-center">
                                    <img src="../../assets/img/icons/star.svg" alt="">
                                </div>
                                <div class="dash-content dash-count">
                                    <h4>Nombre d'écoles</h4>
                                    <h2><span class="counter-up" id="school-count"></span></h2>
                                    <p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i><span id="percentage-change"></span>%</span> vs dernier mois</p>
                                </div>
                            </div>
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
    
    <!-- counterup JS -->
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
    </style>

    <script>
        $(document).ready(function() {
            $('.loading-overlay').show();

            $.ajax({
                url: 'admin_dashboard.php?ajax=1',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        window.location.href = '../misc/error-500.php';
                        return;
                    }

                    $('#user-name').text(data.userName);
                    $('#school-count').text(data.schools.length);
                    $('#current-month-count').text(data.currentMonthCount);
                    $('#percentage-change').text(data.percentageChange.toFixed(2));

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
