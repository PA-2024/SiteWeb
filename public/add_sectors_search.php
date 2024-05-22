<?php
// Auteur : Capdrake
include 'header/entete.php';
require_once '../vendor/autoload.php';
use GeSign\SessionManager;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();

use GeSign\Schools;

$schoolManager = new Schools();
$schools = $schoolManager->fetchSchools();
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
                                <li class="breadcrumb-item active">Ajouter une salle</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="script/add_room.php" method="post">
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
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="input-block local-forms">
                                                <label>Nom de la salle <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="room_name" id="room-name" required disabled>
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

    <!-- Activation du champ salle -->
    <script>
        $(document).ready(function() {
            $('#school-select').on('change', function() {
                if ($(this).val() !== '') {
                    $('#room-name').prop('disabled', false);
                } else {
                    $('#room-name').prop('disabled', true);
                }
            });
        });
    </script>
</body>

</html>
