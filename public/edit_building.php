<?php
// Auteur : Capdrake
include 'header/entete.php';
require_once '../vendor/autoload.php';
use GeSign\SessionManager;
use GeSign\Buildings;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: login.php');
    exit;
}

$buildingManager = new Buildings($token);

if (isset($_GET['id'])) {
    $buildingId = htmlspecialchars($_GET['id']);
    $building = $buildingManager->fetchBuildingById($buildingId);
    if (!$building) {
        echo "Bâtiment non trouvé.";
        exit;
    }
} else {
    header("Location: error-500.php");
    exit;
}

// Récupération de l'ID de l'école à partir de la session ou du cookie
$schoolId = $_SESSION['school'] ?? $_COOKIE['school'];
?>

<body>
    <div class="main-wrapper">
        <?php include 'header/entete_dashboard.php'; ?>
        <?php include 'menu/menu_gestion.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="buildings_list.php">Bâtiments</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Editer un bâtiment</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="script/update_building.php" method="post">
                                    <input type="hidden" name="buildingId" value="<?php echo htmlspecialchars($building['bulding_Id']); ?>">
                                    <input type="hidden" name="buildingSchool" value="<?php echo htmlspecialchars($schoolId); ?>">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-heading">
                                                <h4>Détails du bâtiment</h4>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="input-block local-forms">
                                                <label>Nom du bâtiment <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="buildingName" value="<?php echo htmlspecialchars($building['bulding_Name']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="input-block local-forms">
                                                <label>Ville <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="buildingCity" value="<?php echo htmlspecialchars($building['bulding_City']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="input-block local-forms">
                                                <label>Adresse <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="buildingAddress" value="<?php echo htmlspecialchars($building['bulding_Adress']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="doctor-submit text-end">
                                                <button type="submit" class="btn btn-primary submit-form me-2">Modifier</button>
                                                <a href="buildings_list.php" class="btn btn-primary cancel-form">Annuler</a>
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
