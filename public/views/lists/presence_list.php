<?php
// Auteur : Capdrake (Bastien LEUWERS)
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Presence;
use GeSign\SubjectsHour;

$sessionManager = new SessionManager();
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $presenceId = $_POST['presence_id'];
    $action = $_POST['action'];

    try {
        if ($action === 'delete') {
            $presenceManager->deletePresence($presenceId); // Cette méthode n'existe pas dans la classe Presence 
        } elseif ($action === 'validate') {
            $presenceManager->validatePresence($presenceId); // Cette méthode n'existe pas dans la classe Presence
        }
        header('Location: presence_list.php?subjectsHourId=' . $_GET['subjectsHourId']);
        exit;
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Présences</title>
</head>
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
                                        <?php echo htmlspecialchars($subjectsHour['subjectsHour_Subjects']['subjects_Name'] . ' - ' . $subjectsHour['subjectsHour_DateStart']); ?>
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
                                            <th>ID</th>
                                            <th>Étudiant</th>
                                            <th>Présence</th>
                                            <th>Informations de scan</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($presences['students'] as $student): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($student['student_Id']); ?></td>
                                                <td><?php echo htmlspecialchars($student['student_User']['user_firstname'] . ' ' . $student['student_User']['user_lastname']); ?></td>
                                                <td><?php echo htmlspecialchars($student['isPresent'] ? 'Présent' : 'Absent'); ?></td>
                                                <td>Coucou</td> <!-- Cette colonne doit contenir les informations de scan mais pour le moment vide -->
                                                <td class="text-end">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item delete-link" href="#" data-bs-toggle="modal" data-bs-target="#delete_presence" data-id="<?php echo htmlspecialchars($student['student_Id']); ?>"><i class="fa fa-trash-alt m-r-5"></i> Supprimer</a>
                                                            <form method="post" class="d-inline">
                                                                <input type="hidden" name="presence_id" value="<?php echo htmlspecialchars($student['student_Id']); ?>">
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
</body>
</html>
