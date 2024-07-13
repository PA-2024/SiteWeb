<?php
// Auteur : Capdrake
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';
use GeSign\SessionManager;
use GeSign\User;

$sessionManager = SessionManager::getInstance();
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
	$user = $users[0];//Sert à accéder au premier élément du tableau
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
    exit;
}

// Ajout de vérifications pour éviter les erreurs lorsque les clés ne sont pas définies
$user_firstname = $user['user_firstname'] ?? 'Prénom non spécifié';
$user_lastname = $user['user_lastname'] ?? 'Nom non spécifié';
$user_role = $user['user_Role']['role_Name'] ?? 'Rôle non spécifié';
$user_num = $user['user_num'] ?? 'Numéro de téléphone non spécifié';
$user_email = $user['user_email'] ?? 'Email non spécifié';
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
                <div class="row">
                    <div class="col-sm-7 col-6">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../../index.php">Dashboard </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">Mon Profil</li>
                        </ul>
                    </div>
                    <div class="col-sm-5 col-6 text-end m-b-30">
                        <a href="../forms/edit-profile.php" class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> Éditer le Profil</a>
                    </div>
                </div>

                <?php if (isset($_GET['message'])): ?>
                    <div class="card bg-white">
                        <div class="card-body">
                            <?php if ($_GET['message'] == 'success'): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Profil mis à jour !</strong> Votre profil a été mis à jour avec succès.
                            <?php elseif ($_GET['message'] == 'error'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Erreur !</strong> La mise à jour du profil a échoué : <?php echo htmlspecialchars($_GET['error']); ?>
                            <?php endif; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="card-box profile-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-view">
                                <div class="profile-img-wrap">
                                    <div class="profile-img">
                                        <a href="#"><img class="avatar" src="https://i.pinimg.com/236x/54/72/d1/5472d1b09d3d724228109d381d617326.jpg" alt="Profil Image"></a>
                                    </div>
                                </div>
                                <div class="profile-basic">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="profile-info-left">
                                                <h3 class="user-name m-t-0 mb-0"><?php echo htmlspecialchars($user_firstname . ' ' . $user_lastname); ?></h3>
                                                <small class="text-muted"><?php echo htmlspecialchars($user_role); ?></small>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <ul class="personal-info">
                                                <li>
                                                    <span class="title">Téléphone:</span>
                                                    <span class="text">
                                                        <?php if (preg_match('/\d{1}/', $user_num)): ?>
                                                            <a href="tel:<?php echo htmlspecialchars($user_num); ?>"><?php echo htmlspecialchars($user_num); ?></a>
                                                        <?php else: ?>
                                                            Numéro non renseigné
                                                        <?php endif; ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span class="title">Email:</span>
                                                    <span class="text"><a href="mailto:<?php echo htmlspecialchars($user_email); ?>"><?php echo htmlspecialchars($user_email); ?></a></span>
                                                </li>
                                                <li>
                                                    <span class="title">Adresse:</span>
                                                    <span class="text">Adresse par défaut</span>
                                                </li>
                                                <li>
                                                    <span class="title">Genre:</span>
                                                    <span class="text">Non spécifié</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>                        
                        </div>
                    </div>
                </div>
                <div class="profile-tabs">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item"><a class="nav-link active" href="#about-cont" data-bs-toggle="tab">À propos</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="about-cont">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-box">
                                        <h3 class="card-title">Informations Générales</h3>
                                        <div class="experience-box">
                                            <ul class="experience-list">
                                                <li>
                                                    <div class="experience-user">
                                                        <div class="before-circle"></div>
                                                    </div>
                                                    <div class="experience-content">
                                                        <div class="timeline-content">
                                                            <span class="text">Aucune information supplémentaire disponible.</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
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
