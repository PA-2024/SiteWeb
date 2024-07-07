<?php
// Auteur : Capdrake
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\SubjectsHour;
use GeSign\QCM;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Professeur');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    error_log("Erreur : Token non trouvé.");
    header('Location: ../auth/login.php');
    exit;
}

$subjectsHourManager = new SubjectsHour($token);
$today = new DateTime();
$startDate = (new DateTime('-1 year'))->format('Y-m-d') . 'T00:00:00';
$endDate = (new DateTime('+1 year'))->format('Y-m-d') . 'T23:59:59';
$subjectsHours = $subjectsHourManager->fetchSubjectsHoursByDateRange($startDate, $endDate);

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Requête POST reçue.");
    $title = $_POST['title'];
    $subjectHourId = $_POST['subjectHour_id'];
    $questions = [];

    foreach ($_POST['questions'] as $question) {
        $questionText = $question['text'];
        $options = [];
        foreach ($question['options'] as $option) {
            $options[] = [
                'text' => $option['text'],
                'isCorrect' => isset($option['isCorrect'])
            ];
        }
        $questions[] = [
            'text' => $questionText,
            'options' => $options
        ];
    }

    error_log("Données du formulaire: " . print_r([
        'title' => $title,
        'subjectHour_id' => $subjectHourId,
        'questions' => $questions
    ], true));

    $qcmManager = new QCM($token);

    try {
        $result = $qcmManager->createQCM($title, $subjectHourId, $questions);
        error_log("Résultat de la création du QCM: " . print_r($result, true));
        $successMessage = "Le QCM a été créé avec succès.";
    } catch (Exception $e) {
        error_log('Erreur lors de la création du QCM: ' . $e->getMessage());
        $errorMessage = 'Erreur : ' . $e->getMessage();
    }
}
?>
<body>
    <div class="main-wrapper">
        <!-- On ajoute notre header ici -->
        <?php include '../../header/entete_dashboard.php'; ?>
        <!-- On ajoute notre menu à gauche ici -->
        <?php include '../../menu/menu_prof.php'; ?>

        <div class="page-wrapper">
            <div class="content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../qcm/list_qcm.php">QCM</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Ajouter un QCM</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php if (isset($successMessage)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
                <?php endif; ?>
                <?php if (isset($errorMessage)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
                <?php endif; ?>

                <!-- Main Content -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="add_qcm.php" method="post">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-heading">
                                                <h4>Ajouter un QCM</h4>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="input-block">
                                                <label>Titre du QCM <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="title" required>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="input-block">
                                                <label>Heure de cours <span class="login-danger">*</span></label>
                                                <select class="form-control" name="subjectHour_id" required>
                                                    <option value="">Sélectionner l'heure de cours</option>
                                                    <?php foreach ($subjectsHours as $subjectsHour): ?>
                                                        <option value="<?php echo htmlspecialchars($subjectsHour['subjectsHour_Id']); ?>" <?php echo isset($_GET['subjectsHourId']) && $_GET['subjectsHourId'] == $subjectsHour['subjectsHour_Id'] ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($subjectsHour['subjectsHour_Subject']['subjects_Name'] . ' - ' . formatDateInFrench($subjectsHour['subjectsHour_DateStart'])); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="questions-container">
                                                <div class="question">
                                                    <div class="input-block">
                                                        <label>Question <span class="login-danger">*</span></label>
                                                        <input class="form-control" type="text" name="questions[0][text]" required>
                                                    </div>
                                                    <div class="options-container">
                                                        <div class="input-block">
                                                            <label>Option 1 <span class="login-danger">*</span></label>
                                                            <input class="form-control" type="text" name="questions[0][options][0][text]" required>
                                                            <label>Correct <input type="checkbox" name="questions[0][options][0][isCorrect]"></label>
                                                        </div>
                                                        <div class="input-block">
                                                            <label>Option 2 <span class="login-danger">*</span></label>
                                                            <input class="form-control" type="text" name="questions[0][options][1][text]" required>
                                                            <label>Correct <input type="checkbox" name="questions[0][options][1][isCorrect]"></label>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-secondary add-option">Ajouter une option</button>
                                                    <button type="button" class="btn btn-danger remove-question">Supprimer la question</button>
                                                </div>
                                            </div>
                                            <br>
                                            <button type="button" class="btn btn-primary add-question">Ajouter une question</button>
                                        </div>
                                        <div class="col-12">
                                            <div class="doctor-submit text-end">
                                                <button type="submit" class="btn btn-primary submit-form me-2">Créer le QCM</button>
                                                <button type="reset" class="btn btn-primary cancel-form">Annuler</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>                          
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

    <!-- Custom JS -->
    <script src="../../assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            var questionCount = 1;

            function updateQuestionNames() {
                $('.questions-container .question').each(function(index, question) {
                    $(question).find('input, select').each(function() {
                        var name = $(this).attr('name');
                        if (name) {
                            var newName = name.replace(/\[questions\]\[\d+\]/, '[questions][' + index + ']');
                            $(this).attr('name', newName);
                        }
                    });

                    $(question).find('.options-container .input-block').each(function(optionIndex, option) {
                        $(option).find('input, select').each(function() {
                            var name = $(this).attr('name');
                            if (name) {
                                var newName = name.replace(/\[options\]\[\d+\]/, '[options][' + optionIndex + ']');
                                $(this).attr('name', newName);
                            }
                        });
                    });
                });
            }

            $('.add-question').on('click', function() {
                var questionHtml = `
                    <div class="question">
                        <div class="input-block">
                            <label>Question <span class="login-danger">*</span></label>
                            <input class="form-control" type="text" name="questions[${questionCount}][text]" required>
                        </div>
                        <div class="options-container">
                            <div class="input-block">
                                <label>Option 1 <span class="login-danger">*</span></label>
                                <input class="form-control" type="text" name="questions[${questionCount}][options][0][text]" required>
                                <label>Correct <input type="checkbox" name="questions[${questionCount}][options][0][isCorrect]"></label>
                            </div>
                            <div class="input-block">
                                <label>Option 2 <span class="login-danger">*</span></label>
                                <input class="form-control" type="text" name="questions[${questionCount}][options][1][text]" required>
                                <label>Correct <input type="checkbox" name="questions[${questionCount}][options][1][isCorrect]"></label>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary add-option">Ajouter une option</button>
                        <button type="button" class="btn btn-danger remove-question">Supprimer la question</button>
                    </div>`;
                $('.questions-container').append(questionHtml);
                questionCount++;
                updateQuestionNames();
            });

            $(document).on('click', '.remove-question', function() {
                $(this).closest('.question').remove();
                updateQuestionNames();
            });

            $(document).on('click', '.add-option', function() {
                var question = $(this).closest('.question');
                var optionCount = question.find('.options-container .input-block').length;
                if (optionCount >= 4) {
                    alert('Vous ne pouvez pas ajouter plus de 4 options.');
                    return;
                }
                var optionHtml = `
                    <div class="input-block">
                        <label>Option ${optionCount + 1} <span class="login-danger">*</span></label>
                        <input class="form-control" type="text" name="questions[${questionCount - 1}][options][${optionCount}][text]" required>
                        <label>Correct <input type="checkbox" name="questions[${questionCount - 1}][options][${optionCount}][isCorrect]"></label>
                        <button type="button" class="btn btn-danger remove-option">Supprimer l'option</button>
                    </div>`;
                question.find('.options-container').append(optionHtml);
                updateQuestionNames();
            });

            $(document).on('click', '.remove-option', function() {
                $(this).closest('.input-block').remove();
                updateQuestionNames();
            });
        });
    </script>
</body>
</html>
