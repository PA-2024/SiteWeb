<?php 
//Auteur : Capdrake

include '../../header/entete.php'; 

require_once '../../../vendor/autoload.php';
use GeSign\SessionManager;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Admin');

use GeSign\Schools;

$schoolManager = new Schools();
$schools = $schoolManager->fetchSchools();
?>
<body>
    <div class="main-wrapper">
		<!--On ajoute notre header ici -->
		<?php include '../../header/entete_dashboard.php'; ?>
        <!--On ajoute notre menu à gauche ici-->
		<?php include '../../menu/menu_admin.php'; ?>
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
				
				<!-- Zone pour les messages d'alerte -->
				<div id="alert-placeholder"></div>
				<!-- Zone pour les messages d'alerte 2 -->
                <?php if (isset($_GET['message']) && $_GET['message'] == 'success'): ?>
                    <div class="card bg-white">
                        <div class="card-body">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>École modifiée !</strong> Cette école a bien été modifiée.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

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
														<form id="dataTableSearchForm">
															<input type="text" class="form-control" placeholder="Rechercher" id="dataTableSearchInput">
															<button type="submit" class="btn"><img src="../../assets/img/icons/search-normal.svg" alt="Search"></button>
														</form>
													</div>

													<div class="add-group">
														<a href="../forms/add_school.php" class="btn btn-primary add-pluss ms-2"><img src="../../assets/img/icons/plus.svg" alt=""></a>
														<a href="javascript:;" id="refreshTableBtn" class="btn btn-primary doctor-refresh ms-2"><img src="../../assets/img/icons/re-fresh.svg" alt="Refresh"></a>
													</div>
												</div>
											</div>
										</div>
										<div class="col-auto text-end float-end ms-auto download-grp">
											<a href="javascript:;" id="export-csv" class="me-2"><img src="../../assets/img/icons/pdf-icon-03.svg" alt="Export to CSV"></a>
											<a href="javascript:;" id="export-xlsx"><img src="../../assets/img/icons/pdf-icon-04.svg" alt="Export to XLSX"></a>
										</div>
									</div>
								</div>
								<!-- /Table Header -->
                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nom de l'école</th>
                                                <th>Date de création</th>
												<th ></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($schools as $school): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($school['school_Name']); ?></td>
                                                    <td><?php echo date("d/m/Y", strtotime($school['school_Date']));?></td>
													<td class="text-end">
														<div class="dropdown dropdown-action">
															<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
															<div class="dropdown-menu dropdown-menu-end">
																<a class="dropdown-item" href="../forms/edit_school.php?id=<?php echo htmlspecialchars($school['school_Id']); ?>"><i class="fa-solid fa-pen-to-square m-r-5"></i> Editer</a>
																<a class="dropdown-item delete-link" href="#" data-bs-toggle="modal" data-bs-target="#delete_school" data-id="<?php echo htmlspecialchars($school['school_Id']); ?>"><i class="fa fa-trash-alt m-r-5"></i> Supprimer</a>

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
						<img src="../../assets/img/sent.png" alt="" width="50" height="46">
						<h3>Êtes-vous sûr de vouloir supprimer cette école ?</h3>
						<div class="m-t-20">
							<a href="#" class="btn btn-white" data-bs-dismiss="modal">Fermer</a>
							<button type="button" class="btn btn-danger confirm-delete">Oui</button>
						</div>
					</div>
				</div>
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
	

	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

	<!-- Datatables Initialization -->
	<script>
		$(document).ready(function() {
			var dataTable = $('.datatable').DataTable( {
			  "searching": true,
			  "bDestroy": true,
			  "dom": 'lrtip',
			  select: 'multi',
			  buttons: [
					{
						extend: 'pdfHtml5',
						title: 'School Data',
						exportOptions: {
							columns: ':visible'
						},
						customize: function (doc) {
							doc.content.splice(0, 1, {
								text: 'Report for School Data',
								fontSize: 16,
								alignment: 'center'
							});
						}
					},
					{
						extend: 'excelHtml5',
						text: 'Export Excel',
						title: 'School Data Report',
						exportOptions: {
							modifier: {
								selected: true
							},
							columns: [1, 2, 3, 4]
						}
					},
					{
						extend: 'csvHtml5',
						text: 'Export CSV',
						title: 'School Data Report',
						exportOptions: {
							modifier: {
								selected: true
							},
							columns: [1, 2, 3, 4]
						}
					}
				],
				select: true
			});
			
			$('#export-csv').on('click', function() {
				dataTable.button('.buttons-csv').trigger();
			});
			$('#export-xlsx').on('click', function() {
				dataTable.button('.buttons-excel').trigger();
			});
						
			$(document).on('submit', '#dataTableSearchForm', function(event) {
				event.preventDefault();
				var searchTerm = $('#dataTableSearchInput').val();
				dataTable.search(searchTerm).draw(); //On effectue la recherche
			});

			//Pour rechercher lors de l'écriture
			$('#dataTableSearchInput').on('keyup change', function() {
				dataTable.search(this.value).draw();
			});

			$('#refreshTableBtn').click(function() {
				refreshTable();
			});

			function refreshTable() {
				$.ajax({
					url: '../../script/fetch_schools_ajax.php',
					type: 'GET',
					dataType: 'json',
					success: function(data) {
						updateTable(data);
					},
					error: function() {
						alert('Impossible de recharger les données.');
					}
				});
			}

			function updateTable(data) {
				dataTable.clear();
				$.each(data, function(index, school) {
					var formattedDate = moment(school.school_Date).format('DD/MM/YYYY'); // Formatage de la date
					dataTable.row.add([
						school.school_Name,
						formattedDate, // Utilisation de la date formatée
						'<div class="dropdown dropdown-action"><a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu"><a class="dropdown-item edit-link" href="edit-appointment.html">Éditer</a><a class="dropdown-item delete-link" href="#" data-id="' + school.school_Id + '">Supprimer</a></div></div>'
					]).draw(false);
				});
			}

			// Réinitialiser et configurer les événements de la modale de suppression
			$(document).on('click', '.delete-link', function() {
				var schoolId = $(this).data('id');
				$('#delete_school').data('id', schoolId);
				$('#delete_school').modal('show');
			});

			$('#delete_school').on('shown.bs.modal', function() {
				$(this).find('.btn-danger').off('click').on('click', function() {
					var idToDelete = $('#delete_school').data('id');
					$.ajax({
						url: '../../script/delete_school.php',
						type: 'POST',
						data: { schoolId: idToDelete },
						success: function(response) {
							$('#delete_school').modal('hide');
							refreshTable();
							$('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-success alert-dismissible fade show" role="alert"><strong>École supprimée !</strong> Cette école a bien été supprimée.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
						},
						error: function() {
							$('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Erreur !</strong> La suppression de l\'école a échoué.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
						}
					});
				});
			});
		});
	</script>
	<!-- Buttons -->
	<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.36/build/pdfmake.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.36/vfs_fonts.js"></script>
</body>

</html>