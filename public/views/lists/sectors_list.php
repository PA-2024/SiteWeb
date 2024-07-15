<?php 
// Auteur : Capdrake
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Schools;
use GeSign\Sectors;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

$schoolManager = new Schools();
$sectorsManager = new Sectors();

// Récupération du nom de l'école à partir de la session ou du cookie
$schoolName = $_SESSION['school'] ?? $_COOKIE['school'];

if (!$schoolName) {
    // Rediriger vers une page d'erreur si le nom de l'école n'est pas disponible
    header('Location: ../misc/error-404.php');
    exit;
}

try {
    // Récupérer les détails de l'école par son nom
    $school = $schoolManager->fetchSchoolByName($schoolName);

    // Récupérer les secteurs de l'école
    $allSectors = $sectorsManager->fetchSectors();
    $sectors = [];

    foreach ($allSectors as $sector) {
        if (isset($sector['sectors_School']) && $sector['sectors_School'] !== null) {
            if ($sector['sectors_School']['school_Id'] == $school['school_Id']) {
                $sectors[] = $sector;
            }
        }
    }
} catch (Exception $e) {
    // Gérer l'exception et afficher un message d'erreur
    $errorMessage = $e->getMessage();
}
?>
<body>
    <div class="main-wrapper">
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
                                <li class="breadcrumb-item"><a href="sectors_list.php">Classes</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Liste des classes</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Zone pour les messages d'alerte -->
                <div id="alert-placeholder"></div>
                <!-- Zone pour les messages d'alerte 2 -->
                <?php if (isset($_GET['message'])): ?>
                    <div class="card bg-white">
                        <div class="card-body">
                            <?php if ($_GET['message'] == 'success'): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Classe bien ajoutée !</strong> Cette classe a bien été ajoutée.
                            <?php elseif ($_GET['message'] == 'error'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Classe non ajoutée !</strong> Cette classe n'a pas pu être ajoutée...
                            <?php elseif ($_GET['message'] == 'success2'): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Classe bien modifiée !</strong> Cette classe a bien été modifiée.
                            <?php elseif ($_GET['message'] == 'error2'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Classe non modifiée !</strong> Cette classe n'a pas pu être modifiée...
                            <?php endif; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- Main Content -->
                <?php if (isset($errorMessage)): ?>
                    <div class="card bg-white">
                        <div class="card-body">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Erreur !</strong> <?php echo htmlspecialchars($errorMessage); ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Table Header -->
                    <div class="page-table-header mb-2">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="doctor-table-blk">
                                    <h3>Liste des classes</h3>
                                    <div class="doctor-search-blk">
                                        <div class="top-nav-search table-search-blk">
                                            <form id="dataTableSearchForm">
                                                <input type="text" class="form-control" placeholder="Rechercher" id="dataTableSearchInput">
                                                <button type="submit" class="btn"><img src="../../assets/img/icons/search-normal.svg" alt="Search"></button>
                                            </form>
                                        </div>
                                        <div class="add-group">
                                            <a href="../forms/add_sectors.php" class="btn btn-primary add-pluss ms-2"><img src="../../assets/img/icons/plus.svg" alt=""></a>
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
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-table show-entire">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table border-0 custom-table comman-table datatable mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Nom de la classe</th>
                                                    <th>ID de l'école</th>
                                                    <th>Nom de l'école</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($sectors as $sector): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($sector['sectors_Name']); ?></td>
                                                        <td><?php echo htmlspecialchars($sector['sectors_School']['school_Id']); ?></td>
                                                        <td><?php echo htmlspecialchars($sector['sectors_School']['school_Name']); ?></td>
                                                        <td class="text-end">
                                                            <div class="dropdown dropdown-action">
                                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                                <div class="dropdown-menu dropdown-menu-end">
																    <a class="dropdown-item" href="view_students.php?sector_id=<?php echo htmlspecialchars($sector['sectors_Id']); ?>"><i class="fa-solid fa-users m-r-5"></i> Voir les étudiants</a>
                                                                    <a class="dropdown-item" href="../forms/edit_sector.php?id=<?php echo htmlspecialchars($sector['sectors_Id']); ?>"><i class="fa-solid fa-pen-to-square m-r-5"></i> Éditer</a>
                                                                    <a class="dropdown-item delete-link" href="#" data-bs-toggle="modal" data-bs-target="#delete_sector" data-id="<?php echo htmlspecialchars($sector['sectors_Id']); ?>"><i class="fa fa-trash-alt m-r-5"></i> Supprimer</a>
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
                <?php endif; ?>
            </div>
        </div>
        <!-- Modal de confirmation de suppression -->
        <div id="delete_sector" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img src="../../assets/img/sent.png" alt="" width="50" height="46">
                        <h3>Êtes-vous sûr de vouloir supprimer cette classe ?</h3>
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

    <!-- Script pour gérer le tableau et la suppression -->
    <script>
	$(document).ready(function() {
		var dataTable = $('.datatable').DataTable({
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
					customize: function(doc) {
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
			dataTable.search(searchTerm).draw(); // On effectue la recherche
		});

		// Pour rechercher lors de l'écriture
		$('#dataTableSearchInput').on('keyup change', function() {
			dataTable.search(this.value).draw();
		});

		$('#refreshTableBtn').click(function() {
			refreshTable();
		});

		function refreshTable() {
			$.ajax({
				url: '../../script/fetch_sectors_ajax.php',
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
			$.each(data, function(index, sector) {
				dataTable.row.add([
					sector.sectors_Name,
					sector.sectors_School.school_Id,
					sector.sectors_School.school_Name,
					'<div class="dropdown dropdown-action"><a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu"><a class="dropdown-item" href="edit_sector.php?id=' + sector.sectors_Id + '">Éditer</a><a class="dropdown-item" href="view_students.php?sector_id=' + sector.sectors_Id + '">Voir les étudiants</a><a class="dropdown-item delete-link" href="#" data-id="' + sector.sectors_Id + '">Supprimer</a></div></div>'
				]).draw(false);
			});
			rebindEvents();
		}

		function rebindEvents() {
			$(document).on('click', '.delete-link', function() {
				var sectorId = $(this).data('id');
				$('#delete_sector').data('id', sectorId);
				$('#delete_sector').modal('show');
			});

			$('#delete_sector').on('shown.bs.modal', function() {
				$(this).find('.btn-danger').off('click').on('click', function() {
					var idToDelete = $('#delete_sector').data('id');
					$.ajax({
						url: '../../script/delete_sector.php',
						type: 'POST',
						data: { sectorId: idToDelete },
						success: function(response) {
							$('#delete_sector').modal('hide');
							refreshTable();
							$('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Classe supprimée !</strong> Cette classe a bien été supprimée.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
						},
						error: function() {
							$('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Erreur !</strong> La suppression de la classe a échoué.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
						}
					});
				});
			});
		}

		// Initial bind events
		rebindEvents();
	});
    </script>
</body>
</html>
