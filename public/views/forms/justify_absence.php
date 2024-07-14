<?php
// Auteur : Capdrake (Bastien LEUWERS)
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

$presenceId = $_GET['presenceId'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $proofAbsenceManager = new ProofAbsence($token);

        // Gestion du fichier téléchargé
        $targetDir = "../../uploads/";
        $originalFileName = basename($_FILES["proofFile"]["name"]);
        $newFileName = uniqid() . '-' . $originalFileName;
        $targetFile = $targetDir . $newFileName;
        
        if (move_uploaded_file($_FILES["proofFile"]["tmp_name"], $targetFile)) {
            $proofAbsenceData = [
                'proofAbsence_StudentComment' => $_POST['reason'],
                'proofAbsence_UrlFile' => $targetFile
            ];

            $proofAbsenceManager->createProofAbsence($presenceId, $proofAbsenceData);
            header('Location: justify_absence.php?message=success');
            exit;
        } else {
            throw new Exception("Erreur lors du téléchargement du fichier.");
        }
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
?>
<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_student.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-7 col-6">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard/student_dashboard.php">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Justifier une Absence</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Alert Zone -->
                <?php if (isset($_GET['message']) && $_GET['message'] == 'success'): ?>
                    <div class="card bg-white">
                        <div class="card-body">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Action réussie !</strong> Le justificatif a été envoyé avec succès.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- Main Content -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <?php if (isset($errorMessage)): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Erreur !</strong> <?php echo htmlspecialchars($errorMessage); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <form action="justify_absence.php?presenceId=<?php echo htmlspecialchars($presenceId); ?>" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-block">
                                                <label>Raison de l'absence</label>
                                                <input type="text" name="reason" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="input-block">
                                                <label>Preuve de l'absence (fichier)</label>
                                                <input type="file" name="proofFile" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary">Justifier</button>
                                        <a href="list_absences.php" class="btn btn-secondary">Annuler</a>
                                    </div>
                                </form>
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
    <script src="../../assets/js/app.js"></script>
</body>
</html>
