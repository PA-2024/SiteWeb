<?php 
include 'header/entete.php'; 

require_once '../vendor/autoload.php';

use GeSign\Schools;

$schoolManager = new Schools();
$schools = $schoolManager->fetchSchools();

// Récupération du nombre d'écoles pour le mois actuel et le mois précédent
$currentMonth = date('m');
$currentYear = date('Y');
$previousMonth = date('m', strtotime("-1 month"));
$previousYear = ($currentMonth == 1) ? $currentYear - 1 : $currentYear;

$currentMonthCount = $schoolManager->countSchoolsByMonth($currentYear, $currentMonth);
$previousMonthCount = $schoolManager->countSchoolsByMonth($previousYear, $previousMonth);

// Calcul du pourcentage de variation
if ($previousMonthCount > 0) {
    $percentageChange = (($currentMonthCount - $previousMonthCount) / $previousMonthCount) * 100;
} else {
    $percentageChange = ($currentMonthCount > 0) ? 100 : 0;
}

?>
<body>
    <div class="main-wrapper">
		<!--On ajoute notre header ici -->
		<?php include 'header/entete_dashboard.php'; ?>
        <!--On ajoute notre menu à gauche ici-->
		<?php include 'menu/menu.php'; ?>
        <div class="page-wrapper">
            <div class="content">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col-sm-12">
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php">Dashboard </a></li>
								<li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
								<li class="breadcrumb-item active">Admin Dashboard</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="good-morning-blk">
					<div class="row">
						<div class="col-md-6">
							<div class="morning-user">
								<h2>Bonjour, <span>Bastien LEUWERS</span></h2>
								<p>Passez une bonne journée au travail</p>
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
								<img src="assets/img/icons/profile-add.svg" alt="">
							</div>
							<div class="dash-content dash-count">
								<h4>Nouveaux membres</h4>
								<h2><span class="counter-up" ></span></h2>
								<p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i>20%</span> vs dernier mois</p>
							</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="dash-widget">
							<div class="dash-boxs comman-flex-center">
								<img src="assets/img/icons/calendar.svg" alt="">
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
								<img src="assets/img/icons/star.svg" alt="">
							</div>
							<div class="dash-content dash-count">
								<h4>Nombre d'écoles</h4>
								<h2><span class="counter-up"><?php echo count($schools); ?></span></h2>
								<p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i><?php echo sprintf("%.2f", $percentageChange); ?>%</span> vs dernier mois</p>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-xl-12">
						<div class="card">
							<div class="card-header pb-0">
								<h4 class="card-title d-inline-block">Nouveaux membres récents </h4> <a href="members.php" class="float-end patient-views">Tout montrer</a>
							</div>
							<div class="card-block table-dash">
								<div class="table-responsive">
									<table class="table mb-0 border-0 datatable custom-table">
										<thead>
											<tr>
												<th>
													<div class="form-check check-tables">
														<input class="form-check-input" type="checkbox" value="something">
													</div>
												</th>
												<th>ID</th>
												<th>Nom</th>
												<th>Age</th>
												<th>Date de naissance</th>
												<th>Rôle</th>
												<th>Triage</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<div class="form-check check-tables">
														<input class="form-check-input" type="checkbox" value="something">
													</div>
												</td>
												<td>R00001</td>
												<td class="table-image">
													<img width="28" height="28" class="rounded-circle" src="assets/img/profiles/avatar-02.jpg" alt=""> 
													<h2>Bastien LEUWERS</h2>
												</td>
												<td>24</td>
												<td>26/09/1999</td>
												<td>Bogoss</td>
												<td><button class="custom-badge status-green ">Non Urgent</button></td>
												<td class="text-end">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
														<div class="dropdown-menu dropdown-menu-end">
															<a class="dropdown-item" href="edit-members.php"><i class="fa-solid fa-pen-to-square m-r-5"></i> Editer</a>
															<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_appointment"><i class="fa fa-trash-alt m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check check-tables">
														<input class="form-check-input" type="checkbox" value="something">
													</div>
												</td>
												<td>R00001</td>
												<td class="table-image">
													<img width="28" height="28" class="rounded-circle" src="assets/img/profiles/avatar-02.jpg" alt=""> 
													<h2>Bastien LEUWERS</h2>
												</td>
												<td>24</td>
												<td>26/09/1999</td>
												<td>Bogoss</td>
												<td><button class="custom-badge status-pink ">Non Urgent</button></td>
												<td class="text-end">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
														<div class="dropdown-menu dropdown-menu-end">
															<a class="dropdown-item" href="edit-members.php"><i class="fa-solid fa-pen-to-square m-r-5"></i> Editer</a>
															<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_appointment"><i class="fa fa-trash-alt m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check check-tables">
														<input class="form-check-input" type="checkbox" value="something">
													</div>
												</td>
												<td>R00001</td>
												<td class="table-image">
													<img width="28" height="28" class="rounded-circle" src="assets/img/profiles/avatar-02.jpg" alt=""> 
													<h2>Bastien LEUWERS</h2>
												</td>
												<td>24</td>
												<td>26/09/1999</td>
												<td>Bogoss</td>
												<td><button class="custom-badge status-gray ">Non Urgent</button></td>
												<td class="text-end">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
														<div class="dropdown-menu dropdown-menu-end">
															<a class="dropdown-item" href="edit-members.php"><i class="fa-solid fa-pen-to-square m-r-5"></i> Editer</a>
															<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_appointment"><i class="fa fa-trash-alt m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check check-tables">
														<input class="form-check-input" type="checkbox" value="something">
													</div>
												</td>
												<td>R00001</td>
												<td class="table-image">
													<img width="28" height="28" class="rounded-circle" src="assets/img/profiles/avatar-02.jpg" alt=""> 
													<h2>Bastien LEUWERS</h2>
												</td>
												<td>24</td>
												<td>26/09/1999</td>
												<td>Bogoss</td>
												<td><button class="custom-badge status-orange ">Non Urgent</button></td>
												<td class="text-end">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
														<div class="dropdown-menu dropdown-menu-end">
															<a class="dropdown-item" href="edit-members.php"><i class="fa-solid fa-pen-to-square m-r-5"></i> Editer</a>
															<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_appointment"><i class="fa fa-trash-alt m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
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

</body>

</html>