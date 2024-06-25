<?php
// Auteur : Capdrake (Bastien LEUWERS)
include 'header/entete.php';
require_once '../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\SubjectsHour;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Professeur');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: login.php');
    exit;
}

$subjectsHourManager = new SubjectsHour($token);

$today = new DateTime();
$startDate = $today->format('Y-m-d') . 'T00:00:00';
$endDate = (new DateTime('+1 year'))->format('Y-m-d') . 'T23:59:59';
$subjectsHours = $subjectsHourManager->fetchSubjectsHoursByDateRange($startDate, $endDate);

$selectedHour = isset($_GET['subjectsHourId']) ? $_GET['subjectsHourId'] : null;
$qrText = "Capdrake est magnifique";
$qrCode = new QrCode($qrText);
$qrCode->setSize(300);
$writer = new PngWriter();
$qrImage = $writer->write($qrCode)->getDataUri();

// Fonction pour formater la date en français
function formatDateInFrench($dateString) {
    setlocale(LC_TIME, 'fr_FR.UTF-8');
    $date = new DateTime($dateString);
    $formattedDate = strftime('%d %B %Y', $date->getTimestamp());
    $formattedTime = $date->format('H:i');

    return "$formattedDate à $formattedTime";
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>QR Code de Présence</title>
    <style>
        #qr-code {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 300px;
            width: 300px;
            margin: auto;
        }
    </style>
    <script>
        function refreshQRCode() {
            const qrCodeImage = document.getElementById('qr-code-img');
            qrCodeImage.src = 'generate_qr.php?text=Capdrake+est+magnifique&_=' + new Date().getTime();
        }

        setInterval(refreshQRCode, 10000);
    </script>
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
                                <li class="breadcrumb-item"><a href="professor_dashboard.php">Tableau de bord</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">QR Code de Présence</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card bg-white">
                    <div class="card-body">
                        <form method="get" class="form-inline mb-4">
                            <label for="subjectsHourId" class="mr-2">Sélectionner l'heure de cours:</label>
                            <select name="subjectsHourId" id="subjectsHourId" class="form-control mr-2" required>
                                <?php foreach ($subjectsHours as $subjectsHour): ?>
                                    <option value="<?php echo htmlspecialchars($subjectsHour['subjectsHour_Id']); ?>" <?php echo isset($_GET['subjectsHourId']) && $_GET['subjectsHourId'] == $subjectsHour['subjectsHour_Id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($subjectsHour['subjectsHour_Subject']['subjects_Name'] . ' - ' . formatDateInFrench($subjectsHour['subjectsHour_DateStart'])); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary">Afficher</button>
                        </form>

                        <?php if ($selectedHour): ?>
                            <div id="qr-code">
                                <img id="qr-code-img" src="<?php echo $qrImage; ?>" alt="QR Code">
                            </div>
                        <?php else: ?>
                            <p>Veuillez sélectionner une heure de cours pour générer le QR code.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <!-- Feather Js -->
    <script src="assets/js/feather.min.js"></script>

    <!-- Slimscroll -->
    <script src="assets/js/jquery.slimscroll.js"></script>

    <!-- Select2 Js -->
    <script src="assets/js/select2.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/app.js"></script>
</body>
</html>
