<?php 
//Auteur : Capdrake

include '../../header/entete.php'; 

require_once '../../../vendor/autoload.php';
use GeSign\SessionManager;
use GeSign\Errors;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Admin');

$errorManager = new Errors();
$errors = $errorManager->fetchErrors();
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
                                <li class="breadcrumb-item"><a href="../dashboard/admin_dashboard.php">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Liste des erreurs</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Zone pour les messages d'alerte -->
                <div id="alert-placeholder"></div>

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
                                                <h3>Liste des erreurs</h3>
                                                <div class="doctor-search-blk">
                                                    <div class="top-nav-search table-search-blk">
                                                        <form id="dataTableSearchForm">
                                                            <input type="text" class="form-control" placeholder="Rechercher" id="dataTableSearchInput">
                                                            <button type="submit" class="btn"><img src="../../assets/img/icons/search-normal.svg" alt="Search"></button>
                                                        </form>
                                                    </div>
                                                    <div class="add-group">
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
                                
                                <!-- Recherche par date -->
                                <div class="staff-search-table">
                                    <form id="dateRangeForm">
                                        <div class="row">
                                            <div class="col-12 col-md-6 col-xl-4">
                                                <div class="input-block local-forms cal-icon">
                                                    <label>De</label>
                                                    <input class="form-control datetimepicker" type="text" id="dateFrom">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 col-xl-4">
                                                <div class="input-block local-forms cal-icon">
                                                    <label>A</label>
                                                    <input class="form-control datetimepicker" type="text" id="dateTo">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 col-xl-4 ms-auto">
                                                <div class="doctor-submit">
                                                    <button type="submit" class="btn btn-primary submit-list-form me-2">Rechercher</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
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
                                                <th>Fonction</th>
                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Résolu</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($errors as $error): ?>
                                                <tr>
                                                    <td>
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($error['error_id']); ?>">
                                                        </div>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($error['error_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($error['error_Funtion']); ?></td>
                                                    <td><?php echo date("d/m/Y H:i", strtotime($error['error_DateTime'])); ?></td>
                                                    <td><?php echo htmlspecialchars($error['error_Description']); ?></td>
                                                    <td>
                                                        <?php if ($error['error_Solved']): ?>
                                                            <button class="custom-badge status-green">Oui</button>
                                                        <?php else: ?>
                                                            <button class="custom-badge status-pink">Non</button>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="dropdown dropdown-action">
                                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item resolve-link" href="javascript:;" data-id="<?php echo htmlspecialchars($error['error_id']); ?>"><i class="fa fa-check m-r-5"></i> Résoudre</a>
                                                                <a class="dropdown-item delete-link" href="#" data-bs-toggle="modal" data-bs-target="#delete_error" data-id="<?php echo htmlspecialchars($error['error_id']); ?>"><i class="fa fa-trash-alt m-r-5"></i> Supprimer</a>
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
        <div id="delete_error" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img src="../../assets/img/sent.png" alt="" width="50" height="46">
                        <h3>Êtes-vous sûr de vouloir supprimer cette erreur ?</h3>
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
	
	<!-- Datepicker Core JS -->
	<script src="../../assets/plugins/moment/moment.min.js"></script>
	<script src="../../assets/js/bootstrap-datetimepicker.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- Datatables Initialization -->
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
                        title: 'Erreur Data',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function (doc) {
                            doc.content.splice(0, 1, {
                                text: 'Report for Erreur Data',
                                fontSize: 16,
                                alignment: 'center'
                            });
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Export Excel',
                        title: 'Erreur Data Report',
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
                        title: 'Erreur Data Report',
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
                    url: '../../script/fetch_errors_ajax.php',
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
                $.each(data, function(index, error) {
                    var formattedDate = moment(error.error_DateTime).format('DD/MM/YYYY HH:mm');
                    var status = error.error_Solved ? 'Oui' : 'Non';
                    var statusClass = error.error_Solved ? 'status-green' : 'status-pink';
                    dataTable.row.add([
                        '<input type="checkbox" class="form-check-input" value="' + error.error_id + '">',
                        error.error_id,
                        error.error_Funtion,
                        formattedDate,
                        error.error_Description,
                        '<button class="custom-badge ' + statusClass + '">' + status + '</button>',
                        '<div class="dropdown dropdown-action"><a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item resolve-link" href="javascript:;" data-id="' + error.error_id + '"><i class="fa fa-check m-r-5"></i> Résoudre</a><a class="dropdown-item delete-link" href="#" data-id="' + error.error_id + '"><i class="fa fa-trash-alt m-r-5"></i> Supprimer</a></div></div>'
                    ]).draw(false);
                });
            }

            // Réinitialiser et configurer les événements de la modale de suppression
            $(document).on('click', '.delete-link', function() {
                var errorId = $(this).data('id');
                $('#delete_error').data('id', errorId);
                $('#delete_error').modal('show');
            });

            $('#delete_error').on('shown.bs.modal', function() {
                $(this).find('.btn-danger').off('click').on('click', function() {
                    var idToDelete = $('#delete_error').data('id');
                    $.ajax({
                        url: '../../script/delete_error.php',
                        type: 'POST',
                        data: { errorId: idToDelete },
                        success: function(response) {
                            $('#delete_error').modal('hide');
                            refreshTable();
                            $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Erreur supprimée !</strong> Cette erreur a bien été supprimée.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                        },
                        error: function() {
                            $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Erreur !</strong> La suppression de l\'erreur a échoué.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                        }
                    });
                });
            });

            $(document).on('click', '.resolve-link', function() {
                var errorId = $(this).data('id');
                $.ajax({
                    url: '../../script/resolve_error.php',
                    type: 'POST',
                    data: { errorId: errorId },
                    success: function(response) {
                        refreshTable();
                        $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Erreur résolue !</strong> Cette erreur a bien été résolue.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                    },
                    error: function() {
                        $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Erreur !</strong> La résolution de l\'erreur a échoué.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                    }
                });
            });

            // Gestion de la recherche par date
            $('#dateRangeForm').on('submit', function(event) {
                event.preventDefault();
                var dateFrom = $('#dateFrom').val();
                var dateTo = $('#dateTo').val();
                if (dateFrom && dateTo) {
                    $.ajax({
                        url: '../../script/fetch_errors_ajax.php',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            dateFrom: dateFrom,
                            dateTo: dateTo
                        },
                        success: function(data) {
                            updateTable(data);
                        },
                        error: function() {
                            alert('Impossible de recharger les données.');
                        }
                    });
                }
            });

            // Initialisation des datepickers
            $('.datetimepicker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Custom filtering function which will search data in column four between two values
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = $('#dateFrom').val();
                    var max = $('#dateTo').val();
                    var date = data[3]; // use data for the date column

                    if (
                        (min == '' || max == '') ||
                        (moment(date, 'DD/MM/YYYY HH:mm').isSameOrAfter(moment(min, 'YYYY-MM-DD')) &&
                        moment(date, 'DD/MM/YYYY HH:mm').isSameOrBefore(moment(max, 'YYYY-MM-DD')))
                    ) {
                        return true;
                    }
                    return false;
                }
            );

            // Re-draw the table when the date range filter changes
            $('#dateFrom, #dateTo').change(function() {
                dataTable.draw();
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
