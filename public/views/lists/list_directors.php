<?php
// Auteur : Capdrake (Bastien LEUWERS)
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Director;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Admin');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$directorManager = new Director($token);

try {
    $directors = $directorManager->fetchAllDirectors();
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}
?>
<body>
    <div class="main-wrapper">
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
                                <li class="breadcrumb-item"><a href="../dashboard/admin_dashboard.php">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Liste des Directeurs</li>
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
                                    <strong>Directeur bien ajouté !</strong> Ce directeur a bien été ajouté.
                            <?php elseif ($_GET['message'] == 'error'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Directeur non ajouté !</strong> Ce directeur n'a pas pu être ajouté...
                            <?php elseif ($_GET['message'] == 'success2'): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Directeur bien modifié !</strong> Ce directeur a bien été modifié.
                            <?php elseif ($_GET['message'] == 'error2'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Directeur non modifié !</strong> Ce directeur n'a pas pu être modifié...
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
                                    <h3>Liste des directeurs</h3>
                                    <div class="doctor-search-blk">
                                        <div class="top-nav-search table-search-blk">
                                            <form id="dataTableSearchForm">
                                                <input type="text" class="form-control" placeholder="Rechercher" id="dataTableSearchInput">
                                                <button type="submit" class="btn"><img src="../../assets/img/icons/search-normal.svg" alt="Search"></button>
                                            </form>
                                        </div>
                                        <div class="add-group">
                                            <a href="../forms/create_director.php" class="btn btn-primary add-pluss ms-2"><img src="../../assets/img/icons/plus.svg" alt=""></a>
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
                                                    <th>ID de l'école</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($directors as $director): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="form-check check-tables">
                                                                <input class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($director['user_Id']); ?>">
                                                            </div>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($director['user_Id']); ?></td>
                                                        <td><?php echo htmlspecialchars($director['user_lastname']); ?></td>
                                                        <td><?php echo htmlspecialchars($director['user_firstname']); ?></td>
                                                        <td><?php echo htmlspecialchars($director['user_email']); ?></td>
                                                        <td>
                                                            <?php 
                                                            if (preg_match('/\d{2}/', $director['user_num'])) {
                                                                echo '<a href="tel:' . htmlspecialchars($director['user_num']) . '">' . htmlspecialchars($director['user_num']) . '</a>';
                                                            } else {
                                                                echo 'Numéro non renseigné';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($director['user_School_Id']); ?></td>
                                                        <td class="text-end">
                                                            <div class="dropdown dropdown-action">
                                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="../forms/edit_director.php?id=<?php echo htmlspecialchars($director['user_Id']); ?>"><i class="fa-solid fa-pen-to-square m-r-5"></i> Éditer</a>
                                                                    <a class="dropdown-item delete-link" href="#" data-bs-toggle="modal" data-bs-target="#delete_director" data-id="<?php echo htmlspecialchars($director['user_Id']); ?>"><i class="fa fa-trash-alt m-r-5"></i> Supprimer</a>
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
        <div id="delete_director" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img src="../../assets/img/sent.png" alt="" width="50" height="46">
                        <h3>Êtes-vous sûr de vouloir supprimer ce directeur ?</h3>
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
                    title: 'Directors Data',
                    exportOptions: {
                        columns: ':visible'
                    },
                    customize: function(doc) {
                        doc.content.splice(0, 1, {
                            text: 'Report for Directors Data',
                            fontSize: 16,
                            alignment: 'center'
                        });
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    title: 'Directors Data Report',
                    exportOptions: {
                        modifier: {
                            selected: true
                        },
                        columns: [1, 2, 3, 4, 5, 6]
                        }
                    },
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    title: 'Directors Data Report',
                    exportOptions: {
                        modifier: {
                            selected: true
                        },
                        columns: [1, 2, 3, 4, 5, 6]
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
                url: '../../script/fetch_directors_ajax.php',
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
            $.each(data, function(index, director) {
                dataTable.row.add([
                    '<input type="checkbox" class="form-check-input" value="' + director.user_Id + '">',
                    director.user_Id,
                    director.user_lastname,
                    director.user_firstname,
                    director.user_email,
                    director.user_num ? '<a href="tel:' + director.user_num + '">' + director.user_num + '</a>' : 'Numéro non renseigné',
                    director.user_School_Id,
                    '<div class="dropdown dropdown-action"><a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu"><a class="dropdown-item" href="edit_director.php?id=' + director.user_Id + '">Éditer</a><a class="dropdown-item delete-link" href="#" data-id="' + director.user_Id + '">Supprimer</a></div></div>'
                ]).draw(false);
            });
            rebindEvents();
        }

        function rebindEvents() {
            $(document).on('click', '.delete-link', function() {
                var directorId = $(this).data('id');
                $('#delete_director').data('id', directorId);
                $('#delete_director').modal('show');
            });

            $('#delete_director').on('shown.bs.modal', function() {
                $(this).find('.btn-danger').off('click').on('click', function() {
                    var idToDelete = $('#delete_director').data('id');
                    $.ajax({
                        url: '../../script/delete_director.php',
                        type: 'POST',
                        data: { directorId: idToDelete },
                        success: function(response) {
                            console.log(response);
                            $('#delete_director').modal('hide');
                            refreshTable();
                            $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Directeur supprimé !</strong> Ce directeur a bien été supprimé.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                        },
                        error: function() {
                            $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Erreur !</strong> La suppression du directeur a échoué.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
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
