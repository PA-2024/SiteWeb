<?php
// Auteur : Capdrake (Bastien LEUWERS)
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Student;
use GeSign\Subjects;
use GeSign\StudentSubjects;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$studentManager = new Student($token);
$students = $studentManager->fetchStudents();

$subjectManager = new Subjects($token);
$subjects = $subjectManager->fetchSubjects();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['student_Id'];
    $subjectId = $_POST['subject_Id'];
    
    try {
        $studentSubjectsManager = new StudentSubjects($token);
        $studentSubjectsManager->addStudentSubject($studentId, $subjectId);
        $message = "L'étudiant a été ajouté au cours avec succès.";
    } catch (Exception $e) {
        $error = $e->getMessage() . $_POST['student_Id'] . $_POST['subject_Id'];
    }
}
?>
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
                                <li class="breadcrumb-item"><a href="../lists/list_student_subjects.php">Étudiants et Cours</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Ajouter un Étudiant à un Cours</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Formulaire -->
                <div class="row">
                    <div class="col-md-12">
                        <?php if (isset($message)): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                        <?php elseif (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <form action="" method="post">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="student_Id">Étudiant</label>
                                        <select name="student_Id" id="student_Id" class="form-control">
                                            <?php foreach ($students as $student): ?>
                                                <option value="<?php echo htmlspecialchars($student['student_Id']); ?>"><?php echo htmlspecialchars($student['student_User']['user_firstname']) . "  " . htmlspecialchars($student['student_User']['user_lastname']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="subject_Id">Cours</label>
                                        <select name="subject_Id" id="subject_Id" class="form-control">
                                            <?php foreach ($subjects as $subject): ?>
                                                <option value="<?php echo htmlspecialchars($subject['subjects_Id']); ?>"><?php echo htmlspecialchars($subject['subjects_Name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
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

    <!-- Custom JS -->
    <script src="../../assets/js/app.js"></script>
</body>
</html>
