<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\QCM;
use GeSign\QcmResult;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Eleve');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$qcmManager = new QCM($token);
$today = new DateTime();
$startDate = (new DateTime('-1 year'))->format('Y-m-d') . 'T00:00:00';
$endDate = $today->format('Y-m-d') . 'T23:59:59';
$qcms = $qcmManager->fetchQCMByRange($startDate, $endDate);

$results = [];
$qcmId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qcmId = $_POST['qcm_id'];
    $qcmResultManager = new QcmResult($token);
    $results = $qcmResultManager->fetchStudentResultsForQcm($qcmId);
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
                                <li class="breadcrumb-item active">Résultats des QCM</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de sélection du QCM -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="select_qcm_results.php">
                                    <div class="form-group">
                                        <label for="qcm_id">Sélectionner un QCM :</label>
                                        <select class="form-control" id="qcm_id" name="qcm_id" required>
                                            <option value="">Sélectionner un QCM</option>
                                            <?php foreach ($qcms as $qcm): ?>
                                                <option value="<?php echo htmlspecialchars($qcm['id']); ?>" <?php echo ($qcm['id'] == $qcmId) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($qcm['title']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-primary">Voir les résultats</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Affichage des résultats -->
                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <?php if (!empty($results)): ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card card-table show-entire">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table border-0 custom-table comman-table datatable mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Nom</th>
                                                        <th>Prénom</th>
                                                        <th>Email</th>
                                                        <th>Score</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($results as $result): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($result['qcmResult_Student']['student_User']['user_lastname']); ?></td>
                                                            <td><?php echo htmlspecialchars($result['qcmResult_Student']['student_User']['user_firstname']); ?></td>
                                                            <td><?php echo htmlspecialchars($result['qcmResult_Student']['student_User']['user_email']); ?></td>
                                                            <td><?php echo htmlspecialchars($result['qcmResult_Score']); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>                            
                            </div>                    
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning" role="alert">
                            Vous n'avez pas réalisé ce QCM.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
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
</body>
</html>
