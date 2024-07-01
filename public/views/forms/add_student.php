<?php
// Auteur : Capdrake (Bastien LEUWERS)
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Student;
use GeSign\Sectors;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

// Récupération de l'ID de l'école de l'utilisateur connecté
$schoolId = $_SESSION['schoolId'] ?? null;

if (!$schoolId) {
    echo 'Erreur : Aucune école associée à l\'utilisateur.';
    exit;
}

$sectorManager = new Sectors();
$studentManager = new Student($token);

try {
    $sectors = $sectorManager->fetchSectors();
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $num = $_POST['num'];
    $sectorId = $_POST['sector_id'];

    try {
        $studentManager->registerStudent($email, $password, $lastname, $firstname, $num, $schoolId, $sectorId);
        $successMessage = "L'étudiant a été ajouté avec succès.";
    } catch (Exception $e) {
        $errorMessage = 'Erreur : ' . $e->getMessage();
    }
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
                                <li class="breadcrumb-item"><a href="../lists/student_list.php">Étudiants</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Ajouter un Étudiant</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php if (isset($successMessage)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
                <?php endif; ?>
                <?php if (isset($errorMessage)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
                <?php endif; ?>

                <div class="card bg-white">
                    <div class="card-body">
                        <form action="add_student.php" method="post">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label>Mot de passe</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" class="form-control" name="firstname" required>
                            </div>
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" class="form-control" name="lastname" required>
                            </div>
                            <div class="form-group">
                                <label>Numéro</label>
                                <input type="text" class="form-control" name="num" required>
                            </div>
                            <div class="form-group">
                                <label>Classe</label>
                                <select class="form-control" name="sector_id" required>
                                    <?php foreach ($sectors as $sector): ?>
                                        <option value="<?php echo htmlspecialchars($sector['sectors_Id']); ?>">
                                            <?php echo htmlspecialchars($sector['sectors_Name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                            </div>
                        </form>
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
