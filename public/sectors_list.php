<?php 
// Auteur : Capdrake
include 'header/entete.php';
require_once '../vendor/autoload.php';

use GeSign\SessionManager;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();

use GeSign\Schools;
use GeSign\Sectors;

$schoolManager = new Schools();
$sectorsManager = new Sectors();

$schools = $schoolManager->fetchSchools();
$sectors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selectedSchoolId = htmlspecialchars($_POST['school_Id']);
    $allSectors = $sectorsManager->fetchSectors();

    foreach ($allSectors as $sector) {
        if (isset($sector['sectors_School']) && $sector['sectors_School'] !== null) {
            if ($sector['sectors_School']['school_Id'] == $selectedSchoolId) {
                $sectors[] = $sector;
            }
        }
    }
}
?>
<body>
    <div class="main-wrapper">
        <!-- On ajoute notre header ici -->
        <?php include 'header/entete_dashboard.php'; ?>
        <!-- On ajoute notre menu à gauche ici -->
        <?php include 'menu/menu.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="sectors_list.php">Salles</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Liste des salles</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="sectors_list.php" method="post">
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="input-block local-forms">
                                                <label>École <span class="login-danger">*</span></label>
                                                <select name="school_Id" id="school-select" class="form-control select" required>
                                                    <option value="">Sélectionnez une école</option>
                                                    <?php foreach ($schools as $school): ?>
                                                        <option value="<?php echo htmlspecialchars($school['school_Id']); ?>">
                                                            <?php echo htmlspecialchars($school['school_Name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="doctor-submit text-end">
                                                <button type="submit" class="btn btn-primary submit-form me-2">Afficher les salles</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>							
                    </div>					
                </div>
                
                <!-- Salles -->
                <?php if (!empty($sectors)): ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-table show-entire">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table border-0 custom-table comman-table datatable mb-0">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nom de la salle</th>
                                                    <th>ID de l'école</th>
                                                    <th>Nom de l'école</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($sectors as $sector): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($sector['sectors_Id']); ?></td>
                                                        <td><?php echo htmlspecialchars($sector['sectors_Name']); ?></td>
                                                        <td><?php echo htmlspecialchars($sector['sectors_School']['school_Id']); ?></td>
                                                        <td><?php echo htmlspecialchars($sector['sectors_School']['school_Name']); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                    <div class="card bg-white">
                        <div class="card-body">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Aucune salle !</strong> Il n'y a pas de salles dans cette école.
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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
