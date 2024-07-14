<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\ProofAbsence;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Eleve');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$proofAbsenceManager = new ProofAbsence($token);
$proofAbsences = $proofAbsenceManager->fetchProofAbsenceByStudent();
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
        <?php include '../../menu/menu_student.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard/student_dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Mes Justifications d'Absences</li>
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
                                    <strong>Action réussie !</strong> Votre justification a été traitée avec succès.
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
                                <h3>Mes Justifications d'Absences</h3>
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
                                                <th>ID</th>
                                                <th>Raison de l'Absence</th>
                                                <th>Commentaire de l'École</th>
                                                <th>Statut</th>
                                                <th>URL du fichier</th>
                                                <th>Date de début</th>
                                                <th>Date de fin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($proofAbsences as $proofAbsence): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($proofAbsence['proofAbsenceResponse']['proofAbsence_Id']); ?></td>
                                                    <td><?php echo htmlspecialchars($proofAbsence['proofAbsenceResponse']['proofAbsence_ReasonAbscence']); ?></td>
                                                    <td><?php echo htmlspecialchars($proofAbsence['proofAbsenceResponse']['proofAbsence_SchoolCommentaire']); ?></td>
                                                    <td><?php echo htmlspecialchars($proofAbsence['proofAbsenceResponse']['proofAbsence_Status']); ?></td>
                                                    <td><a href="<?php echo htmlspecialchars($proofAbsence['proofAbsenceResponse']['proofAbsence_UrlFile']); ?>" target="_blank">Voir le fichier</a></td>
                                                    <td><?php echo formatDateInFrench($proofAbsence['subjectHour_DateStart']); ?></td>
                                                    <td><?php echo formatDateInFrench($proofAbsence['subjectHour_DateEnd']); ?></td>
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
    });
    </script>
</body>
</html>
