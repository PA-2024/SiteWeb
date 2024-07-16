<?php
// Auteur : Capdrake (Bastien LEUWERS)
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\QcmResult;
use GeSign\QCM;

// Vérification de la session et du rôle
$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Professeur');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$qcmManager = new QCM($token);
$qcmResultManager = new QcmResult($token);

$qcms = $qcmManager->fetchAllQCMsTeacher(1, 50);
$qcmId = $_GET['qcm_id'] ?? null;
$scores = [];
$details = [];

if ($qcmId) {
    try {
        $scores = $qcmResultManager->fetchAllResultsForQcm($qcmId);
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
?>
<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_prof.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard/prof_dashboard.php">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Résultats des QCM</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de sélection du QCM -->
                <div class="row">
                    <div class="col-md-12">
                        <form action="qcm_results.php" method="get">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="qcm_id">Sélectionner un QCM</label>
                                        <select name="qcm_id" id="qcm_id" class="form-control select2">
                                            <?php foreach ($qcms['items'] as $qcm): ?>
                                                <option value="<?php echo htmlspecialchars($qcm['id']); ?>" <?php echo ($qcmId == $qcm['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($qcm['title']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group text-end">
                                        <button type="submit" class="btn btn-primary">Voir les résultats</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if ($qcmId): ?>
                    <!-- Résultats globaux -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="page-table-header mb-2">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <div class="doctor-table-blk">
                                                    <h3>Scores globaux</h3>
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
                                            <div class="col-auto text-end float-end ms-auto download-grp">
                                                <a href="javascript:;" id="export-csv" class="me-2"><img src="../../assets/img/icons/pdf-icon-03.svg" alt="Export to CSV"></a>
                                                <a href="javascript:;" id="export-xlsx"><img src="../../assets/img/icons/pdf-icon-04.svg" alt="Export to XLSX"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table border-0 custom-table comman-table datatable mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Étudiant</th>
                                                    <th>Secteur</th>
                                                    <th>Score</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($scores as $score): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($score['qcmResult_Student']['student_User']['user_firstname'] . ' ' . $score['qcmResult_Student']['student_User']['user_lastname']); ?></td>
                                                        <td><?php echo htmlspecialchars($score['qcmResult_Student']['student_Sectors']['sectors_Name']); ?></td>
                                                        <td><?php echo htmlspecialchars($score['qcmResult_Score']); ?></td>
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
    </div>
    <div class="sidebar-overlay" data-reff=""></div>

    <!-- jQuery -->
    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Core JS -->
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS -->
    <script src="../../assets/js/select2.min.js"></script>
    <!-- Datatables JS -->
    <script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../assets/plugins/datatables/datatables.min.js"></script>
    <!-- Datatables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.36/build/pdfmake.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.36/vfs_fonts.js"></script>
    <!-- Custom JS -->
    <script src="../../assets/js/app.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();

            var dataTable = $('.datatable').DataTable({
                "searching": true,
                "bDestroy": true,
                "dom": 'lrtip',
                "paging": false,
                "info": false,
                select: 'multi',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export Excel',
                        title: 'QCM Scores Report',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'Export CSV',
                        title: 'QCM Scores Report',
                        exportOptions: {
                            columns: [0, 1, 2]
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
                    url: '../../script/fetch_presences_ajax.php',
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
                $.each(data, function(index, detail) {
                    dataTable.row.add([
                        detail.answerQCM_Student.student_User.user_firstname + ' ' + detail.answerQCM_Student.student_User.user_lastname,
                        detail.answerQCM_Question_Id,
                        detail.answerQCM_Answer
                    ]).draw(false);
                });
            }
        });
    </script>
</body>
</html>
