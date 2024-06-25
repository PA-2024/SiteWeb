<?php 
include 'header/entete.php'; 

require_once '../vendor/autoload.php';

use GeSign\Schools;
use GeSign\SessionManager;
use GeSign\Presence;
use GeSign\Student;
use GeSign\Professor;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: login.php');
    exit;
}

try {
    $schoolManager = new Schools();
    $presenceManager = new Presence($token);
    $studentManager = new Student($token);
    //$professorManager = new Professor($token);

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
    $professors = 10; //$professorManager->fetchProfessors();

    $userName = $_SESSION['user_name'];
} catch (Exception $e) {
    // Redirection vers la page d'erreur 500
    //header('Location: error-500.php');
    exit();
}
?>
<body>
    <div class="main-wrapper">
        <!--On ajoute notre header ici -->
        <?php include 'header/entete_dashboard.php'; ?>
        <!--On ajoute notre menu à gauche ici-->
        <?php include 'menu/menu_gestion.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Panel directeur</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                
                <div class="good-morning-blk">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="morning-user">
                                <h2>Bonjour, <span><?php echo htmlspecialchars($userName); ?></span></h2>
                                <p>Gérez votre école ici !</p>
                            </div>
                        </div>
                        <div class="col-md-6 position-blk">
                            <div class="morning-img">
                                <img src="assets/img/morning-img-01.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="dash-widget">
                            <div class="dash-boxs comman-flex-center">
                                <img src="assets/img/icons/warning.svg" alt="">
                            </div>
                            <div class="dash-content dash-count">
                                <h4>Absences</h4>
                                <h2><span class="counter-up"><?php echo $totalMissed; ?></span></h2>
                                <p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i>40%</span> vs dernier mois</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="dash-widget">
                            <div class="dash-boxs comman-flex-center">
                                <img src="assets/img/icons/medal-01.svg" alt="">
                            </div>
                            <div class="dash-content dash-count">
                                <h4>Présences</h4>
                                <h2><span class="counter-up"><?php echo $totalPresent; ?></span></h2>
                                <p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i>60%</span> vs dernier mois</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="dash-widget">
                            <div class="dash-boxs comman-flex-center">
                                <img src="assets/img/icons/profile.svg" alt="">
                            </div>
                            <div class="dash-content dash-count">
                                <h4>Nombre d'étudiants</h4>
                                <h2><span class="counter-up"><?php echo count($students); ?></span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="dash-widget">
                            <div class="dash-boxs comman-flex-center">
                                <img src="assets/img/icons/profile-add.svg" alt="">
                            </div>
                            <div class="dash-content dash-count">
                                <h4>Nombre de professeurs</h4>
                                <h2><span class="counter-up"><?php echo $professors//count($professors); ?></span></h2>
                            </div>
                        </div>
                    </div>
                </div>

				<div class="page-table-header mb-2">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="student-table-blk">
                                <h3>Liste des Étudiants</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datatable -->
				<div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table show-entire">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <th>Email</th>
                                                <th>Classe</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($students as $student): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($student['student_Id']); ?></td>
                                                    <td><?php echo htmlspecialchars($student['student_User']['user_lastname']); ?></td>
                                                    <td><?php echo htmlspecialchars($student['student_User']['user_firstname']); ?></td>
                                                    <td><?php echo htmlspecialchars($student['student_User']['user_email']); ?></td>
                                                    <td><?php echo htmlspecialchars($student['student_Sectors']['sectors_Name']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
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
									<img src="assets/img/icons/user-icon.svg" alt="">
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
    <div class="sidebar-overlay" data-reff=""></div>
    
    <!-- jQuery -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap Core JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    
    <!-- Feather Js -->
    <script src="assets/js/feather.min.js"></script>
    
    <!-- Slimscroll -->
    <script src="assets/js/jquery.slimscroll.js"></script>
    
    <!-- Select2 Js -->
    <script src="assets/js/select2.min.js"></script>
    
    <!-- Datatables JS -->
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/datatables.min.js"></script>
    
    <!-- counterup JS -->
    <script src="assets/js/jquery.waypoints.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>
    
    <!-- Apexchart JS -->
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/app.js"></script>

    <script>
        $(document).ready(function() {
            $('.datatable').DataTable();

            // Mise à jour du graphique Donut pour les présences/absences
            if ($('#donut-chart-dash').length > 0) {
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
                    series: [<?php echo $totalPresent; ?>, <?php echo $totalMissed; ?>],
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
        });
    </script>
</body>
</html>
