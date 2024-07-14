<!-- Auteur : Capdrake (Bastien LEUWERS) -->
<?php 
include '../../header/entete_login.php'; 
require_once '../../../vendor/autoload.php';

use GeSign\Auth;

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password === $confirmPassword) {
        try {
            $auth = new Auth();
            $response = $auth->setNewPassword($userId, $token, $password);

            if ($response['status'] === 200) {
                $successMessage = 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.';
            } else {
                $errorMessage = 'Erreur lors de la réinitialisation du mot de passe. Veuillez réessayer.';
            }
        } catch (Exception $e) {
            $errorMessage = 'Erreur : ' . $e->getMessage();
        }
    } else {
        $errorMessage = 'Les mots de passe ne correspondent pas. Veuillez réessayer.';
    }
}

$userId = $_GET['user_id'] ?? '';
$token = $_GET['token'] ?? '';
?>

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
                                    <form action="reset-password.php" method="POST">
                                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId); ?>">
                                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                                        <div class="input-block">
                                            <label>Nouveau mot de passe <span class="login-danger">*</span></label>
                                            <input class="form-control pass-input" type="password" name="password" required>
                                            <span class="profile-views feather-eye-off toggle-password"></span>
                                        </div>
                                        <div class="input-block">
                                            <label>Confirmez le nouveau mot de passe <span class="login-danger">*</span></label>
                                            <input class="form-control pass-input" type="password" name="confirm_password" required>
                                            <span class="profile-views feather-eye-off toggle-password"></span>
                                        </div>
                                        <div class="input-block login-btn">
                                            <button class="btn btn-primary btn-block" type="submit">Envoyer</button>
                                        </div>
                                    </form>
                                    <!-- /Form -->
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
le