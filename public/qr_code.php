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
$subjectsHours = $subjectsHourManager->fetchAll();

$selectedHour = isset($_GET['subjectsHourId']) ? $_GET['subjectsHourId'] : null;
$qrText = "Capdrake est magnifique";
$qrCode = new QrCode($qrText);
$qrCode->setSize(300);
$writer = new PngWriter();
$qrImage = $writer->write($qrCode)->getDataUri();

// Fonction pour formater la date
function formatDate($dateString) {
    setlocale(LC_TIME, 'fr_FR.UTF-8');
    $date = new DateTime($dateString);
    $formattedDate = strftime('%d %B %Y', $date->getTimestamp());
    $formattedTime = $date->format('H:i');

    return "$formattedDate à $formattedTime";
}

?>
<!DOCTYPE html>
<html lang="en">
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
            qrCodeImage.src = 'script/generate_qr.php?text=Capdrake+est+magnifique&_=' + new Date().getTime();
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
                                <li class="breadcrumb-item"><a href="dashboard.php">Tableau de bord</a></li>
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
                                        <?php echo htmlspecialchars($subjectsHour['subjectsHour_Subjects']['subjects_Name'] . ' - ' . formatDate($subjectsHour['subjectsHour_DateStart'])); ?>
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
</body>
</html>
