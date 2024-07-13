<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Director;
use GeSign\Schools;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Admin');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

$schoolManager = new Schools($token);
$schools = $schoolManager->fetchSchools();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $directorManager = new Director($token);
    $email = $_POST['user_email'];
    $lastname = $_POST['user_lastname'];
    $firstname = $_POST['user_firstname'];
    $num = $_POST['user_num'];
    $schoolId = $_POST['user_school_id'];

    try {
        $director = $directorManager->createDirector($email, $lastname, $firstname, $num, $schoolId);
        header('Location: ../lists/list_directors.php?message=success');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_admin.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../lists/list_directors.php">Directeurs</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Créer un Directeur</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-12">
                        <form action="create_director.php" method="post">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="user_email">Email</label>
                                        <input type="email" name="user_email" id="user_email" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="user_lastname">Nom de famille</label>
                                        <input type="text" name="user_lastname" id="user_lastname" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="user_firstname">Prénom</label>
                                        <input type="text" name="user_firstname" id="user_firstname" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="user_num">Numéro</label>
                                        <input type="text" name="user_num" id="user_num" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="user_school_id">École</label>
                                        <select name="user_school_id" id="user_school_id" class="form-control" required>
                                            <option value="">Sélectionner une école</option>
                                            <?php foreach ($schools as $school): ?>
                                                <option value="<?php echo htmlspecialchars($school['school_Id']); ?>">
                                                    <?php echo htmlspecialchars($school['school_Name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="form-group text-end">
                                        <button type="submit" class="btn btn-primary">Créer</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
    <script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../assets/plugins/datatables/datatables.min.js"></script>
    <script src="../../assets/js/jquery.waypoints.js"></script>
    <script src="../../assets/js/jquery.counterup.min.js"></script>
    <script src="../../assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="../../assets/plugins/apexchart/chart-data.js"></script>
    <script src="../../assets/js/app.js"></script>
</body>
</html>
