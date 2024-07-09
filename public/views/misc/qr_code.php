<?php
// Auteur : Capdrake (Bastien LEUWERS)
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\SubjectsHour;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Professeur');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$subjectsHourManager = new SubjectsHour($token);

$today = new DateTime();
$startDate = $today->format('Y-m-d') . 'T00:00:00';
$endDate = (new DateTime('+1 year'))->format('Y-m-d') . 'T23:59:59';
$subjectsHours = $subjectsHourManager->fetchSubjectsHoursByDateRange($startDate, $endDate);

$selectedHour = isset($_GET['subjectsHourId']) ? $_GET['subjectsHourId'] : null;

// Fonction pour formater la date en français
function formatDateInFrench($dateString) {
    $date = new DateTime($dateString);
    $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
    $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    $dayOfWeek = $days[$date->format('w')];
    $day = $date->format('d');
    $month = $months[$date->format('n') - 1];
    $year = $date->format('Y');
    $time = $date->format('H:i');

    return "$dayOfWeek $day $month $year à $time";
}

$token = str_replace('Bearer ', '', $token);
?>
<head>
    <meta charset="UTF-8">
    <title>QR Code de Présence</title>
    <style>
        #qr-code {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 500px;
            width: 500px;
            margin: auto;
        }
        #qr-code canvas {
            width: 100%;
            height: 100%;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script>
        let socket;
        let selectedHour = "<?php echo $selectedHour; ?>";
        let token = "<?php echo $token; ?>";

        function initializeWebSocket() {
            socket = new WebSocket('wss://apigessignrecette-c5e974013fbd.herokuapp.com/ws');

            socket.onopen = function() {
                if (selectedHour) {
                    socket.send('createRoom ' + token + ' ' + selectedHour);
                }
            };

            socket.onmessage = function(event) {
                let qrText = event.data;
                updateQRCode(qrText);
            };

            socket.onclose = function(event) {
                console.log('WebSocket closed. Reconnecting in 5 seconds...');
                setTimeout(initializeWebSocket, 5000);
            };

            socket.onerror = function(error) {
                console.error('WebSocket error:', error);
            };
        }

        function updateQRCode(qrText) {
            const qr = new QRious({
                element: document.getElementById('qr-code-img'),
                size: 300,
                value: qrText
            });
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            if (selectedHour) {
                initializeWebSocket();
            }
        });
    </script>
</head>
<body>
    <div class="main-wrapper">
        <?php include '../../header/entete_dashboard.php'; ?>
        <?php include '../../menu/menu_prof.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard/professor_dashboard.php">Tableau de bord</a></li>
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
                            <br>
                            <button type="submit" class="btn btn-primary">Afficher</button>
                        </form>

                        <?php if ($selectedHour): ?>
                            <div id="qr-code">
                                <canvas id="qr-code-img"></canvas>
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
    <script src="../../assets/js/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>

    <!-- Feather Js -->
    <script src="../../assets/js/feather.min.js"></script>

    <!-- Slimscroll -->
    <script src="../../assets/js/jquery.slimscroll.js"></script>

    <!-- Select2 Js -->
    <script src="../../assets/js/select2.min.js"></script>

    <!-- Custom JS -->
    <script src="../../assets/js/app.js"></script>
</body>
</html>
