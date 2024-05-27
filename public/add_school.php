<?php 
//Auteur : Capdrake
include 'header/entete.php'; 
require_once '../vendor/autoload.php';
use GeSign\SessionManager;
use GeSign\Schools;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();

$schoolManager = new Schools();
?>
<body>
    <div class="main-wrapper">
		<!--On ajoute notre header ici -->
		<?php include 'header/entete_dashboard.php'; ?>
        <!--On ajoute notre menu à gauche ici-->
		<?php include 'menu/menu_admin.php'; ?>
                <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="schools_list.php">Écoles</a></li>
								<li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Ajouter une école</li>
                            </ul>
                        </div>
                    </div>
                </div>
<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['school_Name'] ?? 'Default Name';
    $token = $_POST['school_token'] ?? 'Default Token';
    $allowSite = isset($_POST['school_allowSite']) && $_POST['school_allowSite'] === 'on' ? true : false;

    try {
        $result = $schoolManager->createSchool($name, $token, $allowSite);
        echo '<div class="card bg-white">
				<div class="card-body">
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<strong>Ecole bien ajoutée !</strong> Cette école a bien été créée !
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				</div>
			</div>';
    } catch (Exception $e) {
		echo '<div class="card bg-white">
			<div class="card-body">
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Ecole non créée ! :(</strong> ' . $e->getMessage() . '
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			</div>
		</div>';
    }
}
?>

                <!-- Main Content -->
				<div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-heading">
                                                <h4>Ajouter une école</h4>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="input-block">
                                                <label>Nom de l'école <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="school_Name" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="input-block">
                                                <label>Token de l'école <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="school_token" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-block">
                                                <label>Site autorisé <span class="login-danger">*</span></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="school_allowSite">
                                                    <label class="form-check-label">Oui</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="doctor-submit text-end">
                                                <button type="submit" class="btn btn-primary submit-form me-2">Créer l'école</button>
                                                <button type="reset" class="btn btn-primary cancel-form">Annuler</button>
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