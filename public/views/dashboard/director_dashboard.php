<?php
require_once '../../../vendor/autoload.php';

use GeSign\Schools;
use GeSign\SessionManager;
use GeSign\Presence;
use GeSign\Student;
use GeSign\Teacher;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    header('Content-Type: application/json');
    // Si c'est une requête AJAX, renvoyer les données JSON
    try {
        $schoolManager = new Schools();
        $presenceManager = new Presence($token);
        $studentManager = new Student($token);
        $teacherManager = new Teacher($token);

        $schoolName = $_SESSION['school'] ?? $_COOKIE['school'];
        $school = $schoolManager->fetchSchoolByName($schoolName);

        // Récupération des données de présence pour le tableau de bord du directeur de l'école (nombre d'absences et de présences) 
        $presences = $presenceManager->fetchPresences();
        $totalPresent = 0;
        $totalMissed = 0;
        foreach ($presences as $presence) {
            if ($presence['presence_Is']) {
                $totalPresent++;
            } else {
                $totalMissed++;
            }
        }

        // Récupération des étudiants et des professeurs
        $students = $studentManager->fetchStudents();
        $teachers = $teacherManager->fetchTeachers();

        $userName = $_SESSION['user_name'];

        echo json_encode([
            'totalPresent' => $totalPresent,
            'totalMissed' => $totalMissed,
            'students' => $students,
            'teachers' => $teachers,
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
        <?php include '../../menu/menu_gestion.php'; ?>

        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../../index.php">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Panel directeur</li>
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
                                <p>Gérez votre école ici !</p>
                            </div>
                        </div>
                        <div class="col-md-6 position-blk">
                            <img src="../../assets/img/morning-img-01.png" alt="" loading="lazy">
                        </div>
                    </div>
                </div>

                <!-- Contenu chargé après le chargement des données -->
                <div class="content-loaded">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                            <div class="dash-widget">
                                <div class="dash-boxs comman-flex-center">
                                    <img src="../../assets/img/icons/warning.svg" alt="">
                                </div>
                                <div class="dash-content dash-count">
                                    <h4>Absences</h4>
                                    <h2><span class="counter-up" id="total-missed"></span></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                            <div class="dash-widget">
                                <div class="dash-boxs comman-flex-center">
                                    <img src="../../assets/img/icons/medal-01.svg" alt="">
                                </div>
                                <div class="dash-content dash-count">
                                    <h4>Présences</h4>
                                    <h2><span class="counter-up" id="total-present"></span></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                            <div class="dash-widget">
                                <div class="dash-boxs comman-flex-center">
                                    <img src="../../assets/img/icons/profile.svg" alt="">
                                </div>
                                <div class="dash-content dash-count">
                                    <h4>Nombre d'étudiants</h4>
                                    <h2><span class="counter-up" id="total-students"></span></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                            <div class="dash-widget">
                                <div class="dash-boxs comman-flex-center">
                                    <img src="../../assets/img/icons/profile-add.svg" alt="">
                                </div>
                                <div class="dash-content dash-count">
                                    <h4>Nombre de professeurs</h4>
                                    <h2><span class="counter-up" id="total-professors"></span></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datatable pour les étudiants -->
                    <div class="row">
                        <div class="col-12 col-xl-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4 class="card-title d-inline-block">Liste des Étudiants </h4> <a href="../lists/student_list.php" class="float-end patient-views">Tous voir</a>
                                </div>
                                <div class="card-block table-dash">
                                    <div class="table-responsive">
                                        <table class="table mb-0 border-0 datatable custom-table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nom</th>
                                                    <th>Prénom</th>
                                                    <th>Email</th>
                                                    <th>Classe</th>
                                                </tr>
                                            </thead>
                                            <tbody id="student-list">
                                                <!-- Les étudiants seront ajoutés ici via AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>                          
                        </div>                  
                    </div>

                    <!-- Datatable pour les professeurs -->
                    <div class="row">
                        <div class="col-12 col-xl-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4 class="card-title d-inline-block">Liste des Professeurs </h4> <a href="../lists/teacher_list.php" class="float-end patient-views">Tous voir</a>
                                </div>
                                <div class="card-block table-dash">
                                    <div class="table-responsive">
                                        <table class="table mb-0 border-0 datatable custom-table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nom</th>
                                                    <th>Prénom</th>
                                                    <th>Email</th>
                                                    <th>Classe</th>
                                                </tr>
                                            </thead>
                                            <tbody id="teacher-list">
                                                <!-- Les professeurs seront ajoutés ici via AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>                          
                        </div>                  
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-6 col-xl-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="chart-title patient-visit">
                                        <h4>Tests</h4>
                                        <div >
                                            <ul class="nav chat-user-total">
                                                <li><i class="fa fa-circle current-users" aria-hidden="true"></i>Bogoss 75%</li>
                                                <li><i class="fa fa-circle old-users" aria-hidden="true"></i> Moches 25%</li>
                                            </ul>
                                        </div>
                                        <div class="input-block mb-0">
                                            <select class="form-control select">
                                                <option>2022</option>
                                                <option>2021</option>
                                                <option>2020</option>
                                                <option>2019</option>
                                            </select>
                                        </div>
                                    </div>  
                                    <div id="patient-chart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6 col-xl-3 d-flex">
                            <div class="card">
                                <div class="card-body">
                                    <div class="chart-title">
                                        <h4>Presences/absences</h4>
                                    </div>  
                                    <div id="donut-chart-dash" class="chart-user-icon">
                                        <img src="../../assets/img/icons/user-icon.svg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Fin du contenu chargé -->
            </div>
        </div>
    </div>

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
    </style>

    <script>
        $(document).ready(function() {
            // Afficher l'indicateur de chargement au début
            $('.loading-overlay').show();

            // Charger les données via AJAX
            $.ajax({
                url: 'director_dashboard.php?ajax=1',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#user-name').text(data.userName);
                    $('#total-present').text(data.totalPresent);
                    $('#total-missed').text(data.totalMissed);
                    $('#total-students').text(data.students.length);
                    $('#total-professors').text(data.teachers.length);

                    // Remplir la liste des étudiants
                    let studentList = '';
                    data.students.forEach(student => {
                        studentList += `<tr>
                            <td>${student.student_Id}</td>
                            <td>${student.student_User.user_lastname}</td>
                            <td>${student.student_User.user_firstname}</td>
                            <td>${student.student_User.user_email}</td>
                            <td>${student.student_Sectors.sectors_Name}</td>
                        </tr>`;
                    });
                    $('#student-list').html(studentList);

                    // Remplir la liste des professeurs
                    let teacherList = '';
                    data.teachers.forEach(teacher => {
                        teacherList += `<tr>
                            <td>${teacher.user_Id}</td>
                            <td>${teacher.user_lastname}</td>
                            <td>${teacher.user_firstname}</td>
                            <td>${teacher.user_email}</td>
                            <td>${teacher.sectors_Name}</td>
                        </tr>`;
                    });
                    $('#teacher-list').html(teacherList);

                    // Initialiser DataTables
                    if ($.fn.DataTable) {
                        $('.datatable').DataTable();
                    }

                    // Initialiser Counter-Up
                    if ($.fn.counterUp) {
                        $('.counter-up').counterUp({
                            delay: 10,
                            time: 1000
                        });
                    }

                    // Mise à jour du graphique Donut pour les présences/absences
                    if ($('#donut-chart-dash').length > 0 && typeof ApexCharts !== 'undefined') {
                        var donutChart = {
                            chart: {
                                height: 290,
                                type: 'donut',
                                toolbar: {
                                    show: false,
                                }
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '50%'
                                },
                            },
                            dataLabels: {
                                enabled: false
                            },
                            series: [data.totalPresent, data.totalMissed],
                            labels: [
                                'Présences',
                                'Absences'
                            ],
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        width: 200
                                    },
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }],
                            legend: {
                                position: 'bottom',
                            }
                        }
                        var chart = new ApexCharts(
                            document.querySelector("#donut-chart-dash"),
                            donutChart
                        );
                        chart.render();
                    }

                    // Masquer l'indicateur de chargement et afficher le contenu après le chargement des données
                    $('.loading-overlay').hide();
                    $('.content-loaded').show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });
    </script>

</body>
</html>
