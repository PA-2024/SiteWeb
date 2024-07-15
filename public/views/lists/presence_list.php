<?php
// Auteur : Capdrake (Bastien LEUWERS)
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Presence;
use GeSign\SubjectsHour;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Professeur');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$presenceManager = new Presence($token);
$subjectsHourManager = new SubjectsHour($token);

try {
    $subjectsHours = $subjectsHourManager->fetchAll();
    $presences = isset($_GET['subjectsHourId']) ? $presenceManager->fetchPresencesBySubjectsHourId($_GET['subjectsHourId']) : [];
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}

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
        <?php include '../../menu/menu_prof.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard/professor_dashboard.php">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Liste des Présences</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php if (isset($errorMessage)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong> <?php echo htmlspecialchars($errorMessage); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card bg-white">
                    <div class="card-body">
                        <form method="get" class="form-inline mb-4">
                            <label for="subjectsHourId" class="mr-2">Sélectionner l'heure de cours:</label>
                            <select name="subjectsHourId" id="subjectsHourId" class="form-control mr-2" required>
                                <?php foreach ($subjectsHours as $subjectsHour): ?>
                                    <option value="<?php echo htmlspecialchars($subjectsHour['subjectsHour_Id']); ?>" <?php echo isset($_GET['subjectsHourId']) && $_GET['subjectsHourId'] == $subjectsHour['subjectsHour_Id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($subjectsHour['subjectsHour_Subjects']['subjects_Name'] . ' - ' . formatDateInFrench($subjectsHour['subjectsHour_DateStart'])); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <br>
                            <button type="submit" class="btn btn-primary">Afficher</button>
                        </form>

                        <?php if (!empty($presences)): ?>
                            <div class="table-responsive">
                                <table class="table border-0 custom-table comman-table datatable mb-0">
                                    <thead>
                                        <tr>
                                            <th>Étudiant</th>
                                            <th>Présence</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($presences['students'] as $student): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($student['student_User']['user_firstname'] . ' ' . $student['student_User']['user_lastname']); ?></td>
                                                <td>
                                                    <?php if ($student['isPresent']): ?>
                                                        <button class="custom-badge status-green">Présent</button>
                                                    <?php else: ?>
                                                        <button class="custom-badge status-red">Absent</button>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <form method="post" class="d-inline validate-presence-form">
                                                                <input type="hidden" name="presence_id" value="<?php echo htmlspecialchars($student['presence_id']); ?>">
                                                                <button type="submit" name="action" value="validate" class="dropdown-item"><i class="fa-solid fa-check m-r-5"></i> Valider</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p>Aucune présence trouvée pour cette heure de cours.</p>
                        <?php endif; ?>
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

    <script>
        $(document).ready(function() {
            $('.validate-presence-form').on('submit', function(event) {
                event.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                $.ajax({
                    url: '../../script/validate_presence.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        location.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error validating presence: ', textStatus, errorThrown);
                    }
                });
            });
        });
    </script>
</body>
</html>
