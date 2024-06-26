<!-- Auteur : Capdrake (Bastien LEUWERS) -->
<?php 
require_once '../../../vendor/autoload.php';

use GeSign\Auth;
use GeSign\SessionManager;

$auth = new Auth();
$sessionManager = new SessionManager();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = $_POST['userName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $phoneNumber = $_POST['phoneNumber'];

    if ($password !== $confirmPassword) {
        $error = 'Les mots de passe ne correspondent pas.';
    } else {
        $result = $auth->register($userName, $email, $password, $phoneNumber);

        if ($result == "Inscription réussie.") {
            header('Location: login.php?success=1');
            exit;
        } else {
            $error = 'Inscription échouée: ' . $result;
        }
    }
}
?>
<?php include '../../header/entete_login.php'; ?>
    <body>
        <!-- Main Wrapper -->
        <div class="main-wrapper login-body">
            <div class="container-fluid px-0">
                <div class="row">
                    <!-- Login logo -->
                    <div class="col-lg-6 login-wrap">
                        <div class="login-sec">
                            <div class="log-img">
                                <img class="img-fluid" src="../../assets/img/logo.png" alt="Logo" style="width: 600px; height: auto;">
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
                                            <a href="index.php"><img src="../../assets/img/banner.png" alt="" style="width: 150px; height: auto;"></a>
                                        </div>
                                        <h2>Inscription</h2>
                                        <?php if ($error): ?>
											<div class="alert alert-danger alert-dismissible fade show" role="alert">
												<?php echo $error; ?>
												<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
											</div>
                                        <?php endif; ?>
                                        <!-- Form -->
                                        <form action="register.php" method="post">
                                            <div class="input-block">
                                                <label>Votre nom/prénom <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="userName" required>
                                            </div>
                                            <div class="input-block">
                                                <label>Email <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="email" required>
                                            </div>
                                            <div class="input-block">
                                                <label>Numéro de téléphone <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="phoneNumber" required>
                                            </div>
                                            <div class="input-block">
                                                <label>Mot de passe <span class="login-danger">*</span></label>
                                                <input class="form-control pass-input" type="password" name="password" required>
                                                <span class="profile-views feather-eye-off toggle-password"></span>
                                            </div>
                                            <div class="input-block">
                                                <label>Confirmer le mot de passe <span class="login-danger">*</span></label>
                                                <input class="form-control pass-input-confirm" type="password" name="confirmPassword" required>
                                                <span class="profile-views feather-eye-off confirm-password"></span>
                                            </div>
                                            <div class="forgotpass">
                                                <div class="remember-me">
                                                    <label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> J'accepte <a href="javascript:;">&nbsp terms of service </a>&nbsp et <a href="javascript:;">&nbsp privacy policy </a>
                                                    <input type="checkbox" name="terms" required>
                                                    <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="input-block login-btn">
                                                <button class="btn btn-primary btn-block" type="submit">Inscription</button>
                                            </div>
                                        </form>
                                        <!-- /Form -->
                                          
                                        <div class="next-sign">
                                            <p class="account-subtitle">Vous avez déjà un compte? <a href="login.php">Connexion</a></p>
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
        <script src="../../assets/js/jquery-3.7.1.min.js"></script>
        
        <!-- Bootstrap Core JS -->
        <script src="../../assets/js/bootstrap.bundle.min.js"></script>
        
        <!-- Feather Js -->
        <script src="../../assets/js/feather.min.js"></script>
        
        <!-- Custom JS -->
        <script src="../../assets/js/app.js"></script>
        
    </body>
</html>
