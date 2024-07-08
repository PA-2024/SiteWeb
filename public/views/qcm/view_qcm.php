<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\QCM;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Professeur');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$qcmManager = new QCM($token);
$qcmId = $_GET['id'];

try {
    $qcm = $qcmManager->fetchQCMById($qcmId);
    if (is_array($qcm) && count($qcm) > 0) {
        $qcm = $qcm[0]; // Prendre le premier élément du tableau car on nous retourne un tableau...
    } else {
        throw new Exception('QCM introuvable.');
    }
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
    exit;
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
                                <li class="breadcrumb-item"><a href="list_qcm.php">QCM</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Voir le QCM</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <h4 class="card-title"><?php echo htmlspecialchars($qcm['title']); ?></h4>
                            <?php foreach ($qcm['questions'] as $question): ?>
                                <div class="form-group">
                                    <label><?php echo htmlspecialchars($question['text']); ?></label>
                                    <?php foreach ($question['options'] as $option): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" disabled <?php if (in_array($option['id'], $question['correctOption'])) echo 'checked'; ?>>
                                            <label class="form-check-label">
                                                <?php echo htmlspecialchars($option['text']); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/select2.min.js"></script>
    <script src="../../assets/js/app.js"></script>
</body>

</html>
