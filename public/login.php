<!-- Auteur : Capdrake (Bastien LEUWERS) -->
<?php 
require_once '../vendor/autoload.php';

use GeSign\Auth;
use GeSign\SessionManager;

$auth = new Auth();
$sessionManager = new SessionManager();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    $result = $auth->login($email, $password);

    if (is_array($result) && isset($result['token'])) {
        $userId = $result['user_Id'];
        $userName = $result['userName'];
		$userRole = $result['role'];
		$token = $result['token'];
		$school = $result['school'];
		$schoolId = $result['schoolId'];
        $sessionManager->loginUser($userId, $userName, $userRole, $token, $school, $schoolId, $remember);
    } else {
        $error = 'Connexion échouée: ' . $result;
    }
}

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = '<strong>Inscription réussie.</strong> Vous pouvez maintenant vous connecter.';
}
?>
<?php include 'header/entete_login.php'; ?>
<body>
    <!-- Main Wrapper -->
    <div class="main-wrapper login-body">
        <div class="container-fluid px-0">
            <div class="row">
                <!-- Login logo -->
                <div class="col-lg-6 login-wrap">
                    <div class="login-sec">
                        <div class="log-img">
                            <img class="img-fluid" src="assets/img/logo.png" alt="Logo" style="width: 600px; height: auto;">
                        </div>
                    </div>
                </div>
                <!-- /Login logo -->
                
                <!-- Login Content -->
                <div class="col-lg-6 login-wrap-bg">
                    <div class="login-wrapper">
                        <div class="loginbox">
                            <div class="login-right">
                                <div class="login-right-wrap">
                                    <div class="account-logo">
                                        <a href="index.php"><img src="assets/img/banner.png" alt="" style="width: 150px; height: auto;"></a>
                                    </div>
                                    <h2>Connexion</h2>
                                    <?php if ($error): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <?php echo $error; ?>
											<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($success): ?>
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <?php echo $success; ?>
											<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Form -->
                                    <form action="login.php" method="post">
                                        <div class="input-block">
                                            <label>Email <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="email" required>
                                        </div>
                                        <div class="input-block">
                                            <label>Mot de passe <span class="login-danger">*</span></label>
                                            <input class="form-control pass-input" type="password" name="password" required>
                                            <span class="profile-views feather-eye-off toggle-password"></span>
                                        </div>
                                        <div class="forgotpass">
                                            <div class="remember-me">
                                                <label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Se souvenir de moi
                                                <input type="checkbox" name="remember">
                                                <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <a href="forgot-password.php">Vous avez oublié votre mot de passe?</a>
                                        </div>
                                        <div class="input-block login-btn">
                                            <button class="btn btn-primary btn-block" type="submit">Connexion</button>
                                        </div>
                                    </form>
                                    <!-- /Form -->
                                    <div class="next-sign">
                                        <p class="account-subtitle">Besoin d'un compte? <a href="register.php">Inscription</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Login Content -->
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->
    
    <!-- jQuery -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap Core JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    
    <!-- Feather Js -->
    <script src="assets/js/feather.min.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/app.js"></script>
</body>
</html>
