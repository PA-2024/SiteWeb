<?php
// Auteur : Capdrake (Bastien LEUWERS)
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Student;
use GeSign\User;
use GeSign\Sectors;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$userManager = new User($token);
$sectorManager = new Sectors();

try {
    $users = $userManager->fetchAllUsers();
    $sectors = $sectorManager->fetchSectors();
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $classId = $_POST['class_id'];

    $studentManager = new Student($token);
    try {
        $studentManager->createStudent($userId, $classId);
        header('Location: ../lists/student_list.php?message=success');
        exit;
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Étudiant</title>
</head>
<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_gestion.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../lists/student_list.php">Étudiants</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Ajouter un Étudiant</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php if (isset($errorMessage)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong> <?php echo htmlspecialchars($errorMessage); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card bg-white">
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Utilisateur</label>
                                <select class="form-control" name="user_id" required>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?php echo htmlspecialchars($user['user_Id']); ?>">
                                            <?php echo htmlspecialchars($user['user_firstname'] . ' ' . $user['user_lastname']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Classe</label>
                                <select class="form-control" name="class_id" required>
                                    <?php foreach ($sectors as $sector): ?>
                                        <option value="<?php echo htmlspecialchars($sector['sectors_Id']); ?>">
                                            <?php echo htmlspecialchars($sector['sectors_Name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
