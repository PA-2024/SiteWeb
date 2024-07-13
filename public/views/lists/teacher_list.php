<?php
// Auteur : Capdrake (Bastien LEUWERS)
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
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

$teacherManager = new Teacher($token);

try {
    $teachers = $teacherManager->fetchTeachers();
} catch (Exception $e) {
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
                                <li class="breadcrumb-item"><a href="../dashboard/director_dashboard.php">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Liste des Professeurs</li>
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
                                    <strong>Professeur bien ajouté !</strong> Ce professeur a bien été ajouté.
                            <?php elseif ($_GET['message'] == 'error'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Professeur non ajouté !</strong> Ce professeur n'a pas pu être ajouté...
                            <?php elseif ($_GET['message'] == 'success2'): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Professeur bien modifié !</strong> Ce professeur a bien été modifié.
                            <?php elseif ($_GET['message'] == 'error2'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Professeur non modifié !</strong> Ce professeur n'a pas pu être modifié...
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
                                    <h3>Liste des professeurs</h3>
                                    <div class="doctor-search-blk">
                                        <div class="top-nav-search table-search-blk">
                                            <form id="dataTableSearchForm">
                                                <input type="text" class="form-control" placeholder="Rechercher" id="dataTableSearchInput">
                                                <button type="submit" class="btn"><img src="../../assets/img/icons/search-normal.svg" alt="Search"></button>
                                            </form>
                                        </div>
                                        <div class="add-group">
                                            <a href="../forms/add_teacher.php" class="btn btn-primary add-pluss ms-2"><img src="../../assets/img/icons/plus.svg" alt=""></a>
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
                                                    <th>
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input" type="checkbox" value="">
                                                        </div>
                                                    </th>
                                                    <th>ID</th>
                                                    <th>Nom</th>
                                                    <th>Prénom</th>
                                                    <th>Email</th>
                                                    <th>Téléphone</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($teachers as $teacher): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="form-check check-tables">
                                                                <input class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($teacher['user_Id']); ?>">
                                                            </div>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($teacher['user_Id']); ?></td>
                                                        <td><?php echo htmlspecialchars($teacher['user_lastname']); ?></td>
                                                        <td><?php echo htmlspecialchars($teacher['user_firstname']); ?></td>
                                                        <td><?php echo htmlspecialchars($teacher['user_email']); ?></td>
                                                        <td>
                                                            <?php 
                                                            if (preg_match('/\d{2}/', $teacher['user_num'])) {
                                                                echo '<a href="tel:' . htmlspecialchars($teacher['user_num']) . '">' . htmlspecialchars($teacher['user_num']) . '</a>';
                                                            } else {
                                                                echo 'Numéro non renseigné';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="text-end">
                                                            <div class="dropdown dropdown-action">
                                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="../forms/edit_teacher.php?id=<?php echo htmlspecialchars($teacher['user_Id']); ?>"><i class="fa-solid fa-pen-to-square m-r-5"></i> Éditer</a>
                                                                    <a class="dropdown-item delete-link" href="#" data-bs-toggle="modal" data-bs-target="#delete_teacher" data-id="<?php echo htmlspecialchars($teacher['user_Id']); ?>"><i class="fa fa-trash-alt m-r-5"></i> Supprimer</a>
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
        <div id="delete_teacher" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img src="../../assets/img/sent.png" alt="" width="50" height="46">
                        <h3>Êtes-vous sûr de vouloir supprimer ce professeur ?</h3>
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
					title: 'Teacher Data',
					exportOptions: {
						columns: ':visible'
					},
					customize: function(doc) {
						doc.content.splice(0, 1, {
							text: 'Report for Teacher Data',
							fontSize: 16,
							alignment: 'center'
						});
					}
				},
				{
					extend: 'excelHtml5',
					text: 'Export Excel',
					title: 'Teacher Data Report',
					exportOptions: {
						modifier: {
							selected: true
						},
						columns: [1, 2, 3, 4, 5]
					}
				},
				{
					extend: 'csvHtml5',
					text: 'Export CSV',
					title: 'Teacher Data Report',
					exportOptions: {
						modifier: {
							selected: true
						},
						columns: [1, 2, 3, 4, 5]
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
				url: '../../script/fetch_teachers_ajax.php',
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
			$.each(data, function(index, teacher) {
				dataTable.row.add([
					'<input type="checkbox" class="form-check-input" value="' + teacher.user_Id + '">',
					teacher.user_Id,
					teacher.user_lastname,
					teacher.user_firstname,
					teacher.user_email,
					teacher.user_num ? '<a href="tel:' + teacher.user_num + '">' + teacher.user_num + '</a>' : 'Numéro non renseigné',
					'<div class="dropdown dropdown-action"><a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu"><a class="dropdown-item" href="edit_teacher.php?id=' + teacher.user_Id + '">Éditer</a><a class="dropdown-item delete-link" href="#" data-id="' + teacher.user_Id + '">Supprimer</a></div></div>'
				]).draw(false);
			});
			rebindEvents();
		}

		function rebindEvents() {
			$(document).on('click', '.delete-link', function() {
				var teacherId = $(this).data('id');
				$('#delete_teacher').data('id', teacherId);
				$('#delete_teacher').modal('show');
			});

			$('#delete_teacher').on('shown.bs.modal', function() {
				$(this).find('.btn-danger').off('click').on('click', function() {
					var idToDelete = $('#delete_teacher').data('id');
					$.ajax({
						url: '../../script/delete_teacher.php',
						type: 'POST',
						data: { teacherId: idToDelete },
						success: function(response) {
							$('#delete_teacher').modal('hide');
							refreshTable();
							$('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Professeur supprimé !</strong> Ce professeur a bien été supprimé.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
						},
						error: function() {
							$('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Erreur !</strong> La suppression du professeur a échoué.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
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
