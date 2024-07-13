<?php
// Auteur : Capdrake (Bastien LEUWERS)
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Subjects;
use GeSign\Buildings;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$subjectManager = new Subjects($token);
$subjects = $subjectManager->fetchSubjects();

$buildingManager = new Buildings($token);
$buildings = $buildingManager->fetchBuildings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Heure de Cours</title>
</head>
<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_gestion.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../lists/list_subject_hours.php">Heures de Cours</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Ajouter une Heure de Cours</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Formulaire -->
                <div class="row">
                    <div class="col-md-12">
                        <form action="../../script/subject_hour_scripts.php?action=add" method="post">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="subjectsHour_Subjects_Id">Cours</label>
                                        <select name="subjectsHour_Subjects_Id" id="subjectsHour_Subjects_Id" class="form-control">
                                            <?php foreach ($subjects as $subject): ?>
                                                <option value="<?php echo htmlspecialchars($subject['subjects_Id']); ?>"><?php echo htmlspecialchars($subject['subjects_Name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="subjectsHour_Building_Id">Bâtiment</label>
                                        <select name="subjectsHour_Building_Id" id="subjectsHour_Building_Id" class="form-control">
                                            <?php foreach ($buildings as $building): ?>
                                                <option value="<?php echo htmlspecialchars($building['bulding_Id']); ?>"><?php echo htmlspecialchars($building['bulding_Name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="subjectsHour_Room">Salle</label>
                                        <input type="text" name="subjectsHour_Room" id="subjectsHour_Room" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="subjectsHour_DateStart">Date de Début</label>
                                        <input type="datetime-local" name="subjectsHour_DateStart" id="subjectsHour_DateStart" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="subjectsHour_DateEnd">Date de Fin</label>
                                        <input type="datetime-local" name="subjectsHour_DateEnd" id="subjectsHour_DateEnd" class="form-control">
                                    </div>
                                    <div class="form-group text-end">
                                        <button type="submit" class="btn btn-primary">Ajouter</button>
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
