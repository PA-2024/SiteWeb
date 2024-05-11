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
						<div class="card card-table show-entire">
							<div class="card-body">
								<!-- Table Header -->
								<div class="page-table-header mb-2">
									<div class="row align-items-center">
										<div class="col">
											<div class="doctor-table-blk">
												<h3>Liste des écoles</h3>
												<div class="doctor-search-blk">
													<div class="top-nav-search table-search-blk">
														<form>
															<input type="text" class="form-control" placeholder="Rechercher">
															<a class="btn"><img src="assets/img/icons/search-normal.svg" alt=""></a>
														</form>
													</div>
													<div class="add-group">
														<a href="add_school.php" class="btn btn-primary add-pluss ms-2"><img src="assets/img/icons/plus.svg" alt=""></a>
														<a href="javascript:;" id="refreshTableBtn" class="btn btn-primary doctor-refresh ms-2"><img src="assets/img/icons/re-fresh.svg" alt="Refresh"></a>
													</div>
												</div>
											</div>
										</div>
										<div class="col-auto text-end float-end ms-auto download-grp">
											<a href="javascript:;" class=" me-2"><img src="assets/img/icons/pdf-icon-01.svg" alt=""></a>
											<a href="javascript:;" class=" me-2"><img src="assets/img/icons/pdf-icon-02.svg" alt=""></a>
											<a href="javascript:;" class=" me-2"><img src="assets/img/icons/pdf-icon-03.svg" alt=""></a>
											<a href="javascript:;" ><img src="assets/img/icons/pdf-icon-04.svg" alt=""></a>
											
										</div>
									</div>
								</div>
								<!-- /Table Header -->
                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                            <tr>
												<th>
													<div class="form-check check-tables">
														<input class="form-check-input" type="checkbox" value="">
													</div>
												</th>
                                                <th>ID</th>
                                                <th>Nom de l'école</th>
                                                <th>Token</th>
                                                <th>Date de création</th>
												<th ></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($schools as $school): ?>
                                                <tr>
													<td>
														<div class="form-check check-tables">
															<input class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($school['school_Id']); ?>">
														</div>
													</td>
                                                    <td><?php echo htmlspecialchars($school['school_Id']); ?></td>
                                                    <td><?php echo htmlspecialchars($school['school_Name']); ?></td>
                                                    <td><?php echo htmlspecialchars($school['school_token']); ?></td>
                                                    <td>10/05/2024<?php //echo date("d/m/Y", strtotime($school['created_at']));?></td>
													<td class="text-end">
														<div class="dropdown dropdown-action">
															<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
															<div class="dropdown-menu dropdown-menu-end">
																<a class="dropdown-item" href="edit-appointment.html"><i class="fa-solid fa-pen-to-square m-r-5"></i> Editer</a>
																<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_school"><i class="fa fa-trash-alt m-r-5"></i> Supprimer</a>
															</div>
														</div>
													</td>
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
		<div id="delete_school" class="modal fade delete-modal" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-body text-center">
						<img src="assets/img/sent.png" alt="" width="50" height="46">
						<h3>Êtes vous sûr de vouloir supprimer cette école ?</h3>
						<div class="m-t-20"> <a href="#" class="btn btn-white" data-bs-dismiss="modal">Fermer</a>
							<button type="submit" class="btn btn-danger">Oui</button>
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
	<script>
	$(document).ready(function() {
		var dataTable = $('.datatable').DataTable();

		$('#refreshTableBtn').click(function() {
			$.ajax({
				url: 'fetch_schools_ajax.php',
				type: 'GET',
				dataType: 'json',
				success: function(data) {
					dataTable.clear();
					$.each(data, function(index, school) {
						dataTable.row.add([
							'<div class="form-check check-tables"><input class="form-check-input" type="checkbox" value="' + school.school_Id + '"></div>',
							school.school_Id,
							school.school_Name,
							school.school_token,
							'10/05/2024',
							'<div class="dropdown dropdown-action"><a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="edit-appointment.html"><i class="fa-solid fa-pen-to-square m-r-5"></i> Editer</a><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_school"><i class="fa fa-trash-alt m-r-5"></i> Supprimer</a></div></div>' 
						]).draw();
					});
				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert('Impossible de recharger le tableau. Erreur: ' + textStatus);
				}
			});
		});

	});
	</script>

</body>

</html>