<!-- Auteur : Capdrake (Bastien LEUWERS) -->
<?php 
include '../../header/entete_login.php';
require_once '../../../vendor/autoload.php';

use GeSign\Auth;

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';

    if (!empty($email)) {
        try {
            $auth = new Auth();
            $response = $auth->resetPassword($email);
            if ($response) {
                $successMessage = 'Un email de réinitialisation a été envoyé. Vérifiez votre boite mail(et vos spams)';
            } else {
                $errorMessage = 'Une erreur s\'est produite lors de la réinitialisation du mot de passe.';
            }
        } catch (Exception $e) {
            $errorMessage = 'Erreur : ' . $e->getMessage();
        }
    } else {
        $errorMessage = 'Veuillez entrer votre adresse email.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser votre mot de passe</title>
</head>
<body>
    <!-- Main Wrapper -->
    <div class="main-wrapper login-body">
        <div class="container-fluid px-0">
            <div class="row">
                <!-- Login logo -->
                <div class="col-lg-6 login-wrap d-flex justify-content-center align-items-center">
                    <div>
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
                                    <h2>Réinitialiser votre mot de passe</h2>
                                    <?php if ($errorMessage): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $errorMessage; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($successMessage): ?>
                                        <div class="alert alert-success" role="alert">
                                            <?php echo $successMessage; ?>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Form -->
                                    <form action="" method="POST">
                                        <div class="input-block">
                                            <label>Email <span class="login-danger">*</span></label>
                                            <input class="form-control" type="text" name="email" required>
                                        </div>
                                        <div class="input-block login-btn">
                                            <button class="btn btn-primary btn-block" type="submit">Envoyer</button>
                                        </div>
                                    </form>
                                    <!-- /Form -->
                                    <div class="next-sign">
                                        <p class="account-subtitle">Vous voulez vous connecter? <a href="login.php">Connexion</a></p>
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
