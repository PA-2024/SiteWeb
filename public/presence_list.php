<?php
// Auteur : Capdrake (Bastien LEUWERS)
include 'header/entete.php';
require_once '../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Presence;
use GeSign\SubjectsHour;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Professeur');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: login.php');
    exit;
}

$presenceManager = new Presence($token);
$subjectsHourManager = new SubjectsHour($token);

try {
    $subjectsHours = $subjectsHourManager->fetchAll();
    $presences = isset($_GET['subjectsHourId']) ? $presenceManager->fetchPresencesBySubjectsHourId($_GET['subjectsHourId']) : [];
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $presenceId = $_POST['presence_id'];
    $action = $_POST['action'];

    try {
        if ($action === 'delete') {
            $presenceManager->deletePresence($presenceId);
        } elseif ($action === 'validate') {
            $presenceManager->validatePresence($presenceId);
        }
        header('Location: presences_list.php?subjectsHourId=' . $_GET['subjectsHourId']);
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
    <title>Liste des Présences</title>
</head>
<body>
    <div class="main-wrapper">
        <?php include 'header/entete_dashboard.php'; ?>
        <?php include 'menu/menu_prof.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Liste des Présences</li>
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
                        <form method="get" class="form-inline mb-4">
                            <label for="subjectsHourId" class="mr-2">Sélectionner l'heure de cours:</label>
                            <select name="subjectsHourId" id="subjectsHourId" class="form-control mr-2" required>
                                <?php foreach ($subjectsHours as $subjectsHour): ?>
                                    <option value="<?php echo htmlspecialchars($subjectsHour['subjectsHour_Id']); ?>" <?php echo isset($_GET['subjectsHourId']) && $_GET['subjectsHourId'] == $subjectsHour['subjectsHour_Id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($subjectsHour['subjectsHour_Subjects']['subjects_Name'] . ' - ' . $subjectsHour['subjectsHour_DateStart']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary">Afficher</button>
                        </form>

                        <?php if (!empty($presences)): ?>
                            <div class="table-responsive">
                                <table class="table border-0 custom-table comman-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Étudiant</th>
                                            <th>Date de scan</th>
                                            <th>Informations de scan</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($presences as $presence): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($presence['presence_Id']); ?></td>
                                                <td><?php echo htmlspecialchars($presence['presence_Student']['student_User']['user_firstname'] . ' ' . $presence['presence_Student']['student_User']['user_lastname']); ?></td>
                                                <td><?php echo htmlspecialchars($presence['presence_ScanDate']); ?></td>
                                                <td><?php echo htmlspecialchars($presence['presence_ScanInfo']); ?></td>
                                                <td>
                                                    <form method="post" class="d-inline">
                                                        <input type="hidden" name="presence_id" value="<?php echo htmlspecialchars($presence['presence_Id']); ?>">
                                                        <button type="submit" name="action" value="validate" class="btn btn-success btn-sm">Valider</button>
                                                    </form>
                                                    <form method="post" class="d-inline">
                                                        <input type="hidden" name="presence_id" value="<?php echo htmlspecialchars($presence['presence_Id']); ?>">
                                                        <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm">Supprimer</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p>Aucune présence trouvée pour cette heure de cours.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
