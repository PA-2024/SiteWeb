<?php
// Auteur : Capdrake
include 'header/entete.php';
require_once '../vendor/autoload.php';
use GeSign\SessionManager;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

use GeSign\Sectors;

$sectorsManager = new Sectors();

if (isset($_GET['id'])) {
    $sectorId = htmlspecialchars($_GET['id']);
    $sector = $sectorsManager->fetchSectorById($sectorId);
    if (!$sector) {
        echo "Classe non trouvée.";
        exit;
    }
} else {
    header("Location: error-500.php");
    exit;
}
?>
<body>
    <div class="main-wrapper">
        <!-- On ajoute notre header ici -->
        <?php include 'header/entete_dashboard.php'; ?>
        <!-- On ajoute notre menu à gauche ici -->
        <?php include 'menu/menu_gestion.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="sectors_list.php">Classes</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Editer une classe</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="script/update_sector.php" method="post">
                                    <input type="hidden" name="sectorId" value="<?php echo htmlspecialchars($sector['sectors_Id']); ?>">
                                    <input type="hidden" name="schoolId" value="<?php echo htmlspecialchars($sector['sectors_School']['school_Id']); ?>">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-heading">
                                                <h4>Détails de la classe</h4>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="input-block local-forms">
                                                <label>Nom de la classe <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="sectorsName" value="<?php echo htmlspecialchars($sector['sectors_Name']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="doctor-submit text-end">
                                                <button type="submit" class="btn btn-primary submit-form me-2">Modifier</button>
                                                <a href="sectors_list.php" class="btn btn-primary cancel-form">Annuler</a>
                                            </div>
                                        </div>
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
    
    <!-- jQuery -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap Core JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    
    <!-- Feather Js -->
    <script src="assets/js/feather.min.js"></script>
    
    <!-- Slimscroll -->
    <script src="assets/js/jquery.slimscroll.js"></script>
    
    <!-- Select2 Js -->
    <script src="assets/js/select2.min.js"></script>
    
    <!-- Datatables JS -->
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/datatables.min.js"></script>
    
    <!-- counterup JS -->
    <script src="assets/js/jquery.waypoints.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>
    
    <!-- Apexchart JS -->
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/app.js"></script>

</body>

</html>
