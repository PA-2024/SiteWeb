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
        $details = $qcmResultManager->fetchAllResultsDetailsForQcm($qcmId);
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
                                    <h4>Scores globaux</h4>
                                    <div class="table-responsive">
                                        <table class="table border-0 custom-table">
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

                    <!-- Détails des résultats -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4>Détails des résultats</h4>
                                    <div class="table-responsive">
                                        <table class="table border-0 custom-table">
                                            <thead>
                                                <tr>
                                                    <th>Étudiant</th>
                                                    <th>Question ID</th>
                                                    <th>Réponse</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($details as $detail): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($detail['qcmResult_Student']['student_User']['user_firstname'] . ' ' . $detail['qcmResult_Student']['student_User']['user_lastname']); ?></td>
                                                        <td><?php echo htmlspecialchars($detail['qcmResultDetails_Question_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($detail['qcmResultDetails_Answer']); ?></td>
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
    <!-- Custom JS -->
    <script src="../../assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
</body>
</html>
