<?php 
// Auteur : Capdrake
include 'header/entete.php';
require_once '../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Buildings;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: login.php');
    exit;
}

$buildingManager = new Buildings($token);

// Récupération de l'ID de l'école à partir de la session ou du cookie
$schoolId = $_SESSION['schoolId'] ?? $_COOKIE['schoolId'];

if (!$schoolId) {
    // Rediriger vers une page d'erreur si l'ID de l'école n'est pas disponible
    header('Location: error-404.php');
    exit;
}

$buildings = $buildingManager->fetchBuildingsBySchoolId($schoolId);
?>

<body>
    <div class="main-wrapper">
        <!-- On ajoute notre header ici -->
        <?php include 'header/entete_dashboard.php'; ?>
        <!-- On ajoute notre menu à gauche ici -->
        <?php include 'menu/menu_gestion.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="buildings_list.php">Bâtiments</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Liste des bâtiments</li>
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
                                    <strong>Bâtiment bien ajouté !</strong> Ce bâtiment a bien été ajouté.
                            <?php elseif ($_GET['message'] == 'error'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Bâtiment non ajouté !</strong> Ce bâtiment n'a pas pu être ajouté...
                            <?php endif; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Table Header -->
                <div class="page-table-header mb-2">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="doctor-table-blk">
                                <h3>Liste des bâtiments</h3>
                                <div class="doctor-search-blk">
                                    <div class="top-nav-search table-search-blk">
                                        <form id="dataTableSearchForm">
                                            <input type="text" class="form-control" placeholder="Rechercher" id="dataTableSearchInput">
                                            <button type="submit" class="btn"><img src="assets/img/icons/search-normal.svg" alt="Search"></button>
                                        </form>
                                    </div>
                                    <div class="add-group">
                                        <a href="add_building.php" class="btn btn-primary add-pluss ms-2"><img src="assets/img/icons/plus.svg" alt=""></a>
                                        <a href="javascript:;" id="refreshTableBtn" class="btn btn-primary doctor-refresh ms-2"><img src="assets/img/icons/re-fresh.svg" alt="Refresh"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end float-end ms-auto download-grp">
                            <a href="javascript:;" id="export-csv" class="me-2"><img src="assets/img/icons/pdf-icon-03.svg" alt="Export to CSV"></a>
                            <a href="javascript:;" id="export-xlsx"><img src="assets/img/icons/pdf-icon-04.svg" alt="Export to XLSX"></a>
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
                                                <th>Ville</th>
                                                <th>Adresse</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($buildings as $building): ?>
                                                <tr>
                                                    <td>
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($building['bulding_Id']); ?>">
                                                        </div>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($building['bulding_Id']); ?></td>
                                                    <td><?php echo htmlspecialchars($building['bulding_Name']); ?></td>
                                                    <td><?php echo htmlspecialchars($building['bulding_City']); ?></td>
                                                    <td><?php echo htmlspecialchars($building['bulding_Adress']); ?></td>
                                                    <td class="text-end">
                                                        <div class="dropdown dropdown-action">
                                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="edit_building.php?id=<?php echo htmlspecialchars($building['bulding_Id']); ?>"><i class="fa-solid fa-pen-to-square m-r-5"></i> Éditer</a>
                                                                <a class="dropdown-item delete-link" href="#" data-bs-toggle="modal" data-bs-target="#delete_building" data-id="<?php echo htmlspecialchars($building['bulding_Id']); ?>"><i class="fa fa-trash-alt m-r-5"></i> Supprimer</a>
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
        <!-- Modal de confirmation de suppression -->
        <div id="delete_building" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img src="assets/img/sent.png" alt="" width="50" height="46">
                        <h3>Êtes-vous sûr de vouloir supprimer ce bâtiment ?</h3>
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
                        title: 'Building Data',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(doc) {
                            doc.content.splice(0, 1, {
                                text: 'Report for Building Data',
                                fontSize: 16,
                                alignment: 'center'
                            });
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Export Excel',
                        title: 'Building Data Report',
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
                        title: 'Building Data Report',
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
                    url: 'script/fetch_buildings_ajax.php',
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
                $.each(data, function(index, building) {
                    dataTable.row.add([
                        '<input type="checkbox" class="form-check-input" value="' + building.bulding_Id + '">',
                        building.bulding_Id,
                        building.bulding_Name,
                        building.bulding_City,
                        building.bulding_Adress,
                        '<div class="dropdown dropdown-action"><a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu"><a class="dropdown-item" href="edit_building.php?id=' + building.bulding_Id + '">Éditer</a><a class="dropdown-item delete-link" href="#" data-id="' + building.bulding_Id + '">Supprimer</a></div></div>'
                    ]).draw(false);
                });
            }

            // Réinitialiser et configurer les événements de la modale de suppression
            $(document).on('click', '.delete-link', function() {
                var buildingId = $(this).data('id');
                $('#delete_building').data('id', buildingId);
                $('#delete_building').modal('show');
            });

            $('#delete_building').on('shown.bs.modal', function() {
                $(this).find('.btn-danger').off('click').on('click', function() {
                    var idToDelete = $('#delete_building').data('id');
                    $.ajax({
                        url: 'script/delete_building.php',
                        type: 'POST',
                        data: { buildingId: idToDelete },
                        success: function(response) {
                            $('#delete_building').modal('hide');
                            refreshTable();
                            $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Bâtiment supprimé !</strong> Ce bâtiment a bien été supprimé.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                        },
                        error: function() {
                            $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Erreur !</strong> La suppression du bâtiment a échoué.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
