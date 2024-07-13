<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\ProofAbsence;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$proofAbsenceManager = new ProofAbsence($token);
$proofAbsences = $proofAbsenceManager->fetchProofAbsenceAll();
?>
<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_gestion.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard/director_dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Liste des Justifications d'Absences</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="alert-placeholder"></div>
                <?php if (isset($_GET['message'])): ?>
                    <div class="card bg-white">
                        <div class="card-body">
                            <?php if ($_GET['message'] == 'success'): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Action réussie !</strong> La justification a été traitée avec succès.
                            <?php elseif ($_GET['message'] == 'error'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Erreur !</strong> Une erreur s'est produite...
                            <?php endif; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="page-table-header mb-2">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="doctor-table-blk">
                                <h3>Liste des Justifications d'Absences</h3>
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

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table show-entire">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nom de l'Étudiant</th>
                                                <th>Email de l'Étudiant</th>
                                                <th>Classe</th>
                                                <th>Raison de l'Absence</th>
                                                <th>Commentaire de l'École</th>
                                                <th>Statut</th>
                                                <th>URL du fichier</th>
                                                <th>Date de début</th>
                                                <th>Date de fin</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($proofAbsences as $proofAbsence): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($proofAbsence['student']['student_User']['user_lastname']) . ' ' . htmlspecialchars($proofAbsence['student']['student_User']['user_firstname']); ?></td>
                                                    <td><?php echo htmlspecialchars($proofAbsence['student']['student_User']['user_email']); ?></td>
                                                    <td><?php echo htmlspecialchars($proofAbsence['student']['student_Sectors']['sectors_Name']); ?></td>
                                                    <td><?php echo htmlspecialchars($proofAbsence['proofAbsenceResponse']['proofAbsence_ReasonAbscence']); ?></td>
                                                    <td><?php echo htmlspecialchars($proofAbsence['proofAbsenceResponse']['proofAbsence_SchoolCommentaire']); ?></td>
                                                    <td><?php echo htmlspecialchars($proofAbsence['proofAbsenceResponse']['proofAbsence_Status']); ?></td>
                                                    <td><a href="<?php echo htmlspecialchars($proofAbsence['proofAbsenceResponse']['proofAbsence_UrlFile']); ?>" target="_blank">Voir le fichier</a></td>
                                                    <td><?php echo htmlspecialchars($proofAbsence['subjectHour_DateStart']); ?></td>
                                                    <td><?php echo htmlspecialchars($proofAbsence['subjectHour_DateEnd']); ?></td>
                                                    <td class="text-end">
                                                        <button class="btn btn-success validate-absence" data-id="<?php echo htmlspecialchars($proofAbsence['proofAbsenceResponse']['proofAbsence_Id']); ?>">Valider</button>
                                                        <button class="btn btn-danger refuse-absence" data-id="<?php echo htmlspecialchars($proofAbsence['proofAbsenceResponse']['proofAbsence_Id']); ?>">Refuser</button>
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

    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/feather.min.js"></script>
    <script src="../../assets/js/jquery.slimscroll.js"></script>
    <script src="../../assets/js/select2.min.js"></script>
    <script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../assets/plugins/datatables/datatables.min.js"></script>
    <script src="../../assets/js/app.js"></script>

    <script>
    $(document).ready(function() {
        var dataTable = $('.datatable').DataTable({
            "searching": true,
            "bDestroy": true,
            "dom": 'lrtip',
            select: 'multi'
        });

        $(document).on('submit', '#dataTableSearchForm', function(event) {
            event.preventDefault();
            var searchTerm = $('#dataTableSearchInput').val();
            dataTable.search(searchTerm).draw();
        });

        $('#dataTableSearchInput').on('keyup change', function() {
            dataTable.search(this.value).draw();
        });

        $(document).on('click', '.validate-absence', function() {
            var id = $(this).data('id');
            // Implémenter la logique pour valider l'absence
            console.log('Valider absence ID:', id);
        });

        $(document).on('click', '.refuse-absence', function() {
            var id = $(this).data('id');
            // Implémenter la logique pour refuser l'absence
            console.log('Refuser absence ID:', id);
        });
    });
    </script>
</body>
</html>
