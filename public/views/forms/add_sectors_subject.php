<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Subjects;
use GeSign\Sectors;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$subjectManager = new Subjects($token);
$sectorsManager = new Sectors($token);

$subjects = $subjectManager->fetchSubjects();
$sectors = $sectorsManager->fetchSectors();
?>
<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_gestion.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-7 col-6">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../../index.php">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Ajouter une Classe à un Cours</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Alert Zone -->
                <?php if (isset($_GET['message'])): ?>
                    <div class="card bg-white">
                        <div class="card-body">
                            <?php if ($_GET['message'] == 'success'): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Action réussie !</strong> La classe a été ajoutée avec succès.
                            <?php elseif ($_GET['message'] == 'error'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Erreur !</strong> Une erreur s'est produite...
                            <?php elseif ($_GET['message'] == 'conflict'): ?>
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>Conflit !</strong> Un ou plusieurs étudiants sont déjà présents dans ce cours.
                            <?php endif; ?>
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
                                <form id="addClassForm" action="../../script/add_class_to_subject.php" method="POST">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-block">
                                                <label>Cours</label>
                                                <select id="subjectSelect" name="subjectId" class="form-control" required>
                                                    <option value="">Sélectionnez un cours</option>
                                                    <?php foreach ($subjects as $subject): ?>
                                                        <option value="<?php echo $subject['subjects_Id']; ?>">
                                                            <?php echo htmlspecialchars($subject['subjects_Name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-block">
                                                <label>Classe</label>
                                                <select id="sectorSelect" name="sectorId" class="form-control" required>
                                                    <option value="">Sélectionnez une classe</option>
                                                    <?php foreach ($sectors as $sector): ?>
                                                        <option value="<?php echo $sector['sectors_Name']; ?>">
                                                            <?php echo htmlspecialchars($sector['sectors_Name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Ajouter</button>
                                        <a href="../lists/subjects_list.php" class="btn btn-secondary">Annuler</a>
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
