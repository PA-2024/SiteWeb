<?php
// Auteur : Capdrake (Bastien LEUWERS)
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\File;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $destination = '../../uploads/' . $fileName;

        if (move_uploaded_file($fileTmpPath, $destination)) {
            try {
                $fileManager = new File($token);
                $response = $fileManager->importFile('https://gesign.wstr.fr/uploads/' . $fileName);
                if (isset($response['status']) && strpos($response['status'], 'succès') !== false) {
                    $successMessage = $response['status'];
                } else {
                    $errorMessage = 'Erreur lors de l\'importation du fichier. Avez-vous bien vérifié votre';
                }
            } catch (Exception $e) {
                $errorMessage = 'Erreur : ' . $e->getMessage();
            }
            
            // Supprimer le fichier après traitement
            if (file_exists($destination)) {
                unlink($destination);
            }
        } else {
            $errorMessage = 'Erreur lors du déplacement du fichier.';
        }
    } else {
        $errorMessage = 'Veuillez sélectionner un fichier à importer.';
    }
}
?>
<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_gestion.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-7 col-6">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../../index.php">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Importer un fichier</li>
                            </ul>
                        </div>
                    </div>
                </div>

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

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="file">Sélectionner un fichier</label>
                                        <input type="file" name="file" id="file" class="form-control" required>
                                    </div>
                                    <br>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Importer</button>
                                        <a href="../../index.php" class="btn btn-secondary">Annuler</a>
                                    </div>
                                </form>
                                <!-- Note d'information -->
                                <div class="alert alert-info mt-3" role="alert">
                                    <h5>Format attendu du fichier CSV</h5>
                                    <p>Le fichier CSV doit contenir les colonnes suivantes :</p>
                                    <ul>
                                        <li>User_email</li>
                                        <li>User_lastname</li>
                                        <li>User_firstname</li>
                                        <li>User_num</li>
                                        <li>ClassName</li>
                                    </ul>
                                    <p>Exemple :</p>
                                    <pre>
User_email,User_lastname,User_firstname,User_num,ClassName
john1.doe@example.com,Doe,John,123456,AL1
jane1.doe@example.com,Doe,Jane,654321,AL1
alice.smith@example.com,Smith,Alice,789012,AL1
bob.jones@example.com,Jones,Bob,345678,AL1
                                    </pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/feather.min.js"></script>
    <script src="../../assets/js/jquery.slimscroll.js"></script>
    <script src="../../assets/js/select2.min.js"></script>
    <script src="../../assets/js/app.js"></script>
</body>
</html>
