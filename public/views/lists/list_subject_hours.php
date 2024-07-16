<?php
// Auteur : Capdrake (Bastien LEUWERS)
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\SubjectsHour;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$subjectHourManager = new SubjectsHour($token);
$subjectHours = $subjectHourManager->fetchAll();

// Fonction pour formater la date en français
function formatDateInFrench($dateString) {
    $date = new DateTime($dateString);
    $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
    $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    $dayOfWeek = $days[$date->format('w')];
    $day = $date->format('d');
    $month = $months[$date->format('n') - 1];
    $year = $date->format('Y');
    $time = $date->format('H:i');

    return "$dayOfWeek $day $month $year à $time";
}
?>
<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_gestion.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="list_subject_hours.php">Heures de Cours</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Liste des Heures de Cours</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Zone pour les messages d'alerte -->
                <div id="alert-placeholder"></div>
                <?php if (isset($_GET['message'])): ?>
                    <div class="card bg-white">
                        <div class="card-body">
                            <?php if ($_GET['message'] == 'success'): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Heure de cours ajoutée avec succès !</strong>
                            <?php elseif ($_GET['message'] == 'error'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Erreur lors de l'ajout de l'heure de cours !</strong>
                            <?php elseif ($_GET['message'] == 'success2'): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Heure de cours modifiée avec succès !</strong>
                            <?php elseif ($_GET['message'] == 'error2'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Erreur lors de la modification de l'heure de cours !</strong>
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
                                    <h3>Liste des Heures de Cours</h3>
                                    <div class="doctor-search-blk">
                                        <div class="top-nav-search table-search-blk">
                                            <form id="dataTableSearchForm">
                                                <input type="text" class="form-control" placeholder="Rechercher" id="dataTableSearchInput">
                                                <button type="submit" class="btn"><img src="../../assets/img/icons/search-normal.svg" alt="Search"></button>
                                            </form>
                                        </div>
                                        <div class="add-group">
                                            <a href="../forms/add_subject_hour.php" class="btn btn-primary add-pluss ms-2"><img src="../../assets/img/icons/plus.svg" alt=""></a>
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
                                                    <th>Nom du Cours</th>
                                                    <th>Salle</th>
                                                    <th>Date de Début</th>
                                                    <th>Date de Fin</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($subjectHours as $subjectHour): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($subjectHour['subjectsHour_Subjects']['subjects_Name']); ?></td>
                                                        <td><?php echo htmlspecialchars($subjectHour['subjectsHour_Room']); ?></td>
                                                        <td><?php echo formatDateInFrench($subjectHour['subjectsHour_DateStart']); ?></td>
                                                        <td><?php echo formatDateInFrench($subjectHour['subjectsHour_DateEnd']); ?></td>
                                                        <td class="text-end">
                                                            <div class="dropdown dropdown-action">
                                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="../forms/edit_subject_hour.php?id=<?php echo htmlspecialchars($subjectHour['subjectsHour_Id']); ?>"><i class="fa-solid fa-pen-to-square m-r-5"></i> Éditer</a>
                                                                    <a class="dropdown-item delete-link" href="#" data-bs-toggle="modal" data-bs-target="#delete_subject_hour" data-id="<?php echo htmlspecialchars($subjectHour['subjectsHour_Id']); ?>"><i class="fa fa-trash-alt m-r-5"></i> Supprimer</a>
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

        <div id="delete_subject_hour" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img src="../../assets/img/sent.png" alt="" width="50" height="46">
                        <h3>Êtes-vous sûr de vouloir supprimer cette heure de cours ?</h3>
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

    <!-- Custom JS -->
    <script src="../../assets/js/app.js"></script>

    <!-- Datatables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.36/build/pdfmake.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.36/vfs_fonts.js"></script>

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
                    title: 'Subject hours Data',
                    exportOptions: {
                        columns: ':visible :not(.text-end)'
                    },
                    customize: function(doc) {
                        doc.content.splice(0, 1, {
                            text: 'Subject Hours Report',
                            fontSize: 16,
                            alignment: 'center'
                        });
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    title: 'Subject Hours Report',
                    exportOptions: {
                        modifier: {
                            selected: true
                        },
                        columns: ':visible :not(.text-end)'
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    title: 'Subject Hours Report',
                    exportOptions: {
                        modifier: {
                            selected: true
                        },
                        columns: ':visible :not(.text-end)'
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
                url: '../../script/fetch_subject_hours_ajax.php',
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
            $.each(data, function(index, subjectHour) {
                dataTable.row.add([
                    subjectHour.subjectsHour_Subjects.subjects_Name,
                    subjectHour.subjectsHour_Room,
                    formatDateInFrench(subjectHour.subjectsHour_DateStart),
                    formatDateInFrench(subjectHour.subjectsHour_DateEnd),
                    '<div class="dropdown dropdown-action"><a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu"><a class="dropdown-item" href="edit_subject_hour.php?id=' + subjectHour.subjectsHour_Id + '">Éditer</a><a class="dropdown-item delete-link" href="#" data-id="' + subjectHour.subjectsHour_Id + '">Supprimer</a></div></div>'
                ]).draw(false);
            });
            rebindEvents();
        }

        function rebindEvents() {
            $(document).on('click', '.delete-link', function() {
                var subjectHourId = $(this).data('id');
                $('#delete_subject_hour').data('id', subjectHourId);
                $('#delete_subject_hour').modal('show');
            });

            $('#delete_subject_hour').on('shown.bs.modal', function() {
                $(this).find('.btn-danger').off('click').on('click', function() {
                    var idToDelete = $('#delete_subject_hour').data('id');
                    $.ajax({
                        url: '../../script/subject_hour_scripts.php?action=delete',
                        type: 'POST',
                        data: { subjectHourId: idToDelete },
                        success: function(response) {
                            $('#delete_subject_hour').modal('hide');
                            refreshTable();
                            $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Heure de cours supprimée !</strong> Cette heure de cours a bien été supprimée.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                        },
                        error: function() {
                            $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Erreur !</strong> La suppression de l\'heure de cours a échoué.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                        }
                    });
                });
            });
        }
        rebindEvents();
    });
    </script>
</body>
</html>
