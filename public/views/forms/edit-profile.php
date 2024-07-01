<?php
// Auteur : Capdrake
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';
use GeSign\SessionManager;
use GeSign\User;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$userManager = new User($token);

try {
    $users = $userManager->fetchUserByToken();
    if (empty($users)) {
        throw new Exception('Aucune donnée utilisateur trouvée.');
    }

    $user = $users[0]; // Accéder au premier élément du tableau
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
    exit;
}

// Ajout de vérifications pour éviter les erreurs lorsque les clés ne sont pas définies
$user_firstname = $user['user_firstname'] ?? '';
$user_lastname = $user['user_lastname'] ?? '';
$user_num = $user['user_num'] ?? '';
$user_email = $user['user_email'] ?? '';
$user_role = $user['user_Role']['role_Name'] ?? '';
?>
<body>
    <div class="main-wrapper">
        <!-- On ajoute notre header ici -->
        <?php include '../../header/entete_dashboard.php'; ?>
        <!-- On ajoute notre menu à gauche ici -->
        <?php
        if ($user_role === 'Gestion Ecole') {
            include '../../menu/menu_gestion.php';
        } elseif ($user_role === 'Admin') {
            include '../../menu/menu_admin.php';
        } elseif ($user_role === 'Professeur') {
            include '../../menu/menu_prof.php';
        } elseif ($user_role === 'Eleve') {
            include '../../menu/menu_student.php';
        }
        ?>
        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../../index.php">Tableau de bord </a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Edit Profile</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <form action="../../script/update_profile.php" method="post">
                    <div class="card-box">
                        <h3 class="card-title">Informations</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="profile-img-wrap">
                                    <img class="inline-block" src="https://i.pinimg.com/236x/54/72/d1/5472d1b09d3d724228109d381d617326.jpg" alt="user">
                                </div>
                                <div class="profile-basic">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-block local-forms">
                                                <label class="focus-label">Prénom</label>
                                                <input type="text" class="form-control floating" value="<?php echo htmlspecialchars($user_firstname); ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-block local-forms">
                                                <label class="focus-label">Nom</label>
                                                <input type="text" class="form-control floating" value="<?php echo htmlspecialchars($user_lastname); ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-block local-forms">
                                                <label class="focus-label">Numéro</label>
                                                <input type="text" class="form-control floating" name="user_num" value="<?php echo htmlspecialchars($user_num); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-block local-forms">
                                                <label class="focus-label">Email</label>
                                                <input type="email" class="form-control floating" value="<?php echo htmlspecialchars($user_email); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary submit-btn mb-4" type="submit">Sauvegarder</button>
                    </div>
                </form>
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
    
    <!-- Counterup JS -->
    <script src="../../assets/js/jquery.waypoints.js"></script>
    <script src="../../assets/js/jquery.counterup.min.js"></script>
    
    <!-- Apexchart JS -->
    <script src="../../assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="../../assets/plugins/apexchart/chart-data.js"></script>
    
    <!-- Custom JS -->
    <script src="../../assets/js/app.js"></script>

</body>
</html>
