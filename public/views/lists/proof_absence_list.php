<?php
// Auteur : Capdrake (Bastien LEUWERS)
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
                                                    <td><?php echo formatDateInFrench($proofAbsence['subjectHour_DateStart']); ?></td>
                                                    <td><?php echo formatDateInFrench($proofAbsence['subjectHour_DateEnd']); ?></td>
                                                    <td class="text-end">
                                                        <div class="dropdown dropdown-action">
                                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item validate-absence" href="#" data-id="<?php echo htmlspecialchars($proofAbsence['proofAbsenceResponse']['proofAbsence_Id']); ?>"><i class="fa-solid fa-check m-r-5"></i> Valider</a>
                                                                <a class="dropdown-item refuse-absence" href="#" data-id="<?php echo htmlspecialchars($proofAbsence['proofAbsenceResponse']['proofAbsence_Id']); ?>"><i class="fa fa-times m-r-5"></i> Refuser</a>
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
    </div>
    <div class="sidebar-overlay" data-reff=""></div>

    <!-- Modal pour valider/refuser une absence -->
    <div class="modal fade" id="absence-modal" tabindex="-1" role="dialog" aria-labelledby="absenceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="absenceModalLabel">Détails de la justification d'absence</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Contenu de la modal -->
                    <p><strong>Nom de l'Étudiant:</strong> <span id="student-name"></span></p>
                    <p><strong>Email de l'Étudiant:</strong> <span id="student-email"></span></p>
                    <p><strong>Classe:</strong> <span id="student-class"></span></p>
                    <p><strong>Raison de l'Absence:</strong> <span id="absence-reason"></span></p>
                    <p><strong>Commentaire de l'École:</strong> <input type="text" id="school-comment" class="form-control"></p>
                    <p><strong>URL du fichier:</strong> <a href="#" target="_blank" id="file-url">Voir le fichier</a></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="refuse-absence-btn">Refuser</button>
                    <button type="button" class="btn btn-success" id="validate-absence-btn">Valider</button>
                </div>
            </div>
        </div>
    </div>

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

        var currentAbsenceId = null;

        $(document).on('click', '.validate-absence, .refuse-absence', function() {
            var id = $(this).data('id');
            currentAbsenceId = id;
            var row = $(this).closest('tr');
            var studentName = row.find('td:eq(0)').text();
            var studentEmail = row.find('td:eq(1)').text();
            var studentClass = row.find('td:eq(2)').text();
            var absenceReason = row.find('td:eq(3)').text();
            var fileUrl = row.find('td:eq(6) a').attr('href');

            $('#student-name').text(studentName);
            $('#student-email').text(studentEmail);
            $('#student-class').text(studentClass);
            $('#absence-reason').text(absenceReason);
            $('#file-url').attr('href', fileUrl);

            $('#absence-modal').modal('show');
        });

        $('#validate-absence-btn').on('click', function() {
            var comment = $('#school-comment').val();
            updateProofAbsence(currentAbsenceId, comment, 2); // 2 for validated
        });

        $('#refuse-absence-btn').on('click', function() {
            var comment = $('#school-comment').val();
            updateProofAbsence(currentAbsenceId, comment, 1); // 1 for refused
        });

        function updateProofAbsence(id, comment, status) {
            $.ajax({
                url: '../../script/update_proof_absence.php',
                type: 'POST',
                data: {
                    proofAbsence_Id: id,
                    proofAbsence_SchoolComment: comment,
                    proofAbsence_Status: status
                },
                success: function(response) {
                    $('#absence-modal').modal('hide');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    alert('Erreur lors de la mise à jour de la justification d\'absence.');
                }
            });
        }
    });
    </script>
</body>
</html>
