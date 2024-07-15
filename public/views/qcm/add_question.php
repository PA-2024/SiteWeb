<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\QCM;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Professeur');

$token = $_SESSION['token'] ?? $_COOKIE['token'];
$qcmId = $_GET['id'] ?? null;

if (!$token || !$qcmId) {
    header('Location: ../auth/login.php');
    exit;
}

$qcmManager = new QCM($token);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $questionText = $_POST['question_text'];
    $options = [];

    foreach ($_POST['options'] as $option) {
        $options[] = [
            'text' => $option['text'],
            'isCorrect' => isset($option['isCorrect'])
        ];
    }

    try {
        $qcmManager->addQuestion($qcmId, $questionText, $options);
        $successMessage = "La question a été ajoutée avec succès.";
    } catch (Exception $e) {
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
                                <li class="breadcrumb-item"><a href="list_qcm.php">QCM</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Ajouter une Question</li>
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
                                <form action="add_question.php?id=<?php echo htmlspecialchars($qcmId); ?>" method="post">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-heading">
                                                <h4>Ajouter une Question au QCM</h4>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-block">
                                                <label>Question <span class="login-danger">*</span></label>
                                                <input class="form-control" type="text" name="question_text" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="options-container">
                                                <div class="input-block">
                                                    <label>Option 1 <span class="login-danger">*</span></label>
                                                    <input class="form-control" type="text" name="options[0][text]" required>
                                                    <label>Correct <input type="checkbox" name="options[0][isCorrect]"></label>
                                                </div>
                                                <div class="input-block">
                                                    <label>Option 2 <span class="login-danger">*</span></label>
                                                    <input class="form-control" type="text" name="options[1][text]" required>
                                                    <label>Correct <input type="checkbox" name="options[1][isCorrect]"></label>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-secondary add-option">Ajouter une option</button>
                                        </div>
                                        <div class="col-12">
                                            <div class="doctor-submit text-end">
                                                <button type="submit" class="btn btn-primary submit-form me-2">Ajouter la Question</button>
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
            var optionCount = 2;

            $(document).on('click', '.add-option', function() {
                if (optionCount >= 4) {
                    alert('Vous ne pouvez pas ajouter plus de 4 options.');
                    return;
                }
                var optionHtml = `
                    <div class="input-block">
                        <label>Option ${optionCount + 1} <span class="login-danger">*</span></label>
                        <input class="form-control" type="text" name="options[${optionCount}][text]" required>
                        <label>Correct <input type="checkbox" name="options[${optionCount}][isCorrect]"></label>
                        <button type="button" class="btn btn-danger remove-option">Supprimer l'option</button>
                    </div>`;
                $('.options-container').append(optionHtml);
                optionCount++;
            });

            $(document).on('click', '.remove-option', function() {
                $(this).closest('.input-block').remove();
                optionCount--;
            });
        });
    </script>
</body>
</html>
