<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\QCM;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Eleve');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$qcmManager = new QCM($token);

// Définir les dates de début et de fin pour aujourd'hui
$today = new DateTime();
//today->sub(new DateInterval('P1D'));
$startDate = $today->format('Y-m-d') . 'T00:00:00';
$endDate = $today->format('Y-m-d') . 'T23:59:59';

$qcms = $qcmManager->fetchQCMByRange($startDate, $endDate);
?>
<body>
    <div class="main-wrapper">
        <!-- On ajoute notre header ici -->
        <?php include '../../header/entete_dashboard.php'; ?>
        <!-- On ajoute notre menu à gauche ici -->
        <?php include '../../menu/menu_student.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="list_qcm.php">QCM</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">QCMs du jour</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <!-- Zone pour les messages d'alerte -->
                <div id="alert-placeholder"></div>
                <!-- Zone pour les messages d'alerte 2 -->
                <?php if (isset($_GET['message'])): ?>
                    <div class="card bg-white">
                        <div class="card-body">
                            <?php if ($_GET['message'] == 'success'): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>QCM bien rejoint !</strong> Vous avez bien rejoint ce QCM.
                            <?php elseif ($_GET['message'] == 'error'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Erreur !</strong> Vous n'avez pas pu rejoindre ce QCM...
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
                                <h3>Liste des QCMs du jour</h3>
                                <div class="doctor-search-blk">
                                    <div class="top-nav-search table-search-blk">
                                        <form id="dataTableSearchForm">
                                            <input type="text" class="form-control" placeholder="Rechercher" id="dataTableSearchInput">
                                            <button type="submit" class="btn"><img src="../../assets/img/icons/search-normal.svg" alt="Search"></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                                                <th>ID</th>
                                                <th>Titre</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($qcms as $qcm): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($qcm['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($qcm['title']); ?></td>
                                                    <td class="text-end">
                                                        <a href="join_qcm.php?id=<?php echo htmlspecialchars($qcm['id']); ?>" class="btn btn-primary">Rejoindre</a>
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
                        title: 'QCMs du jour',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(doc) {
                            doc.content.splice(0, 1, {
                                text: 'Report for QCMs du jour',
                                fontSize: 16,
                                alignment: 'center'
                            });
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Export Excel',
                        title: 'QCMs du jour',
                        exportOptions: {
                            modifier: {
                                selected: true
                            },
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'Export CSV',
                        title: 'QCMs du jour',
                        exportOptions: {
                            modifier: {
                                selected: true
                            },
                            columns: [0, 1, 2, 3]
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
                    url: '../../script/fetch_qcms_ajax.php',
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
                $.each(data, function(index, qcm) {
                    dataTable.row.add([
                        qcm.id,
                        qcm.title,
                        moment(qcm.startTime).format('HH:mm'),
                        moment(qcm.endTime).format('HH:mm'),
                        '<a href="join_qcm.php?id=' + qcm.id + '" class="btn btn-primary">Rejoindre</a>'
                    ]).draw(false);
                });
            }
        });
    </script>
</body>
</html>
