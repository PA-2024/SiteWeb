<?php 
//Auteur : Capdrake

include 'header/entete.php'; 

require_once '../vendor/autoload.php';

use GeSign\Schools;

$schoolManager = new Schools();
$schools = $schoolManager->fetchSchools();
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
                                <li class="breadcrumb-item"><a href="schools_list.php">Écoles</a></li>
								<li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Liste des écoles</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table">
                            <div class="card-body">
                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table custom-table datatable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nom de l'école</th>
                                                <th>Token</th>
                                                <th>Date de création</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($schools as $school): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($school['school_Id']); ?></td>
                                                    <td><?php echo htmlspecialchars($school['school_Name']); ?></td>
                                                    <td><?php echo htmlspecialchars($school['school_token']); ?></td>
                                                    <td>10/05/2024<?php //echo date("d/m/Y", strtotime($school['created_at']));?></td>
                                                </tr>
                                            <?php endforeach; ?>
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
	<!-- Datatables Initialization -->
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable();
        });
    </script>

</body>

</html>