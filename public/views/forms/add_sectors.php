<?php
// Auteur : Capdrake
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';
use GeSign\SessionManager;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

use GeSign\Schools;

$schoolManager = new Schools();

// Récupération du nom de l'école à partir de la session ou du cookie
$schoolName = $_SESSION['school'] ?? $_COOKIE['school'];

if (!$schoolName) {
    // Rediriger vers une page d'erreur si le nom de l'école n'est pas disponible
    header('Location: ../misc/error-404.php');
    exit;
}

try {
    // Récupérer les détails de l'école par son nom
    $school = $schoolManager->fetchSchoolByName($schoolName);
} catch (Exception $e) {
    // Gérer l'exception et afficher un message d'erreur
    $errorMessage = $e->getMessage();
}
?>
<body>
    <div class="main-wrapper">
        <!-- On ajoute notre header ici -->
        <?php include '../../header/entete_dashboard.php'; ?>
        <!-- On ajoute notre menu à gauche ici -->
        <?php include '../../menu/menu_gestion.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../lists/sectors_list.php">Classe</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Ajouter une classe</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <?php if (isset($errorMessage)): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Erreur !</strong> <?php echo htmlspecialchars($errorMessage); ?>
                                    </div>
                                <?php else: ?>
                                    <form action="../../script/add_room.php" method="post">
                                        <div class="row">
                                            <div class="col-12 col-md-6 col-xl-4">
                                                <div class="input-block local-forms">
                                                    <label>École <span class="login-danger">*</span></label>
                                                    <input class="form-control" type="text" name="school_name" value="<?php echo htmlspecialchars($school['school_Name']); ?>" readonly>
                                                    <input type="hidden" name="school_Id" value="<?php echo htmlspecialchars($school['school_Id']); ?>">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 col-xl-4">
                                                <div class="input-block local-forms">
                                                    <label>Nom de la classe <span class="login-danger">*</span></label>
                                                    <input class="form-control" type="text" name="room_name" id="room-name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="doctor-submit text-end">
                                                <button type="submit" class="btn btn-primary submit-form me-2">Ajouter</button>
                                                <button type="reset" class="btn btn-primary cancel-form">Annuler</button>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>                            
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
