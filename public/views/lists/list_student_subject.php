<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Subjects;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$subjectManager = new Subjects($token);
$subjects = $subjectManager->fetchSubjects();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Cours</title>
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
                                <li class="breadcrumb-item"><a href="subjects_list.php">Cours</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Gestion des Cours</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="alert-placeholder"></div>

                <div class="card bg-white">
                    <div class="card-body">
                        <h3>Liste des Cours</h3>
                        <select id="subjectSelect" class="form-select">
                            <option value="">Sélectionnez un cours</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo htmlspecialchars($subject['subjects_Id']); ?>"><?php echo htmlspecialchars($subject['subjects_Name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div id="studentsContainer" class="card bg-white mt-4" style="display: none;">
                    <div class="card-body">
                        <h3>Liste des Étudiants</h3>
                        <div class="table-responsive">
                            <table class="table border-0 custom-table comman-table datatable mb-0">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" value="">
                                            </div>
                                        </th>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="studentsTableBody">
                                    <!-- Les étudiants seront affichés ici -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="delete_student" class="modal fade delete-modal" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <img src="../../assets/img/sent.png" alt="" width="50" height="46">
                                <h3>Êtes-vous sûr de vouloir supprimer cet étudiant du cours ?</h3>
                                <div class="m-t-20">
                                    <a href="#" class="btn btn-white" data-bs-dismiss="modal">Fermer</a>
                                    <button type="button" class="btn btn-danger confirm-delete">Oui</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="sidebar-overlay" data-reff=""></div>

    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/feather.min.js"></script>
    <script src="../../assets/js/app.js"></script>

    <script>
        $(document).ready(function() {
            $('#subjectSelect').change(function() {
                var subjectId = $(this).val();
                if (subjectId) {
                    $.ajax({
                        url: '../../script/fetch_subject.php',
                        type: 'GET',
                        data: { id: subjectId },
                        dataType: 'json',
                        success: function(subject) {
                            var students = subject.students;
                            var studentsTableBody = $('#studentsTableBody');
                            studentsTableBody.empty();

                            if (students.length > 0) {
                                $('#studentsContainer').show();
                                $.each(students, function(index, student) {
                                    studentsTableBody.append(
                                        '<tr>' +
                                            '<td>' +
                                                '<div class="form-check check-tables">' +
                                                    '<input class="form-check-input" type="checkbox" value="' + student.student_Id + '">' +
                                                '</div>' +
                                            '</td>' +
                                            '<td>' + student.student_Id + '</td>' +
                                            '<td>' + student.student_User.user_firstname + ' ' + student.student_User.user_lastname + '</td>' +
                                            '<td>' + student.student_User.user_email + '</td>' +
                                            '<td class="text-end">' +
                                                '<div class="dropdown dropdown-action">' +
                                                    '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>' +
                                                    '<div class="dropdown-menu dropdown-menu-end">' +
                                                        '<a class="dropdown-item delete-student" href="#" data-student-id="' + student.student_Id + '" data-subject-id="' + subject.subjects_Id + '"><i class="fa fa-trash-alt m-r-5"></i> Supprimer</a>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</td>' +
                                        '</tr>'
                                    );
                                });
                            } else {
                                $('#studentsContainer').hide();
                            }
                        },
                        error: function() {
                            alert('Impossible de récupérer les étudiants.');
                        }
                    });
                } else {
                    $('#studentsContainer').hide();
                }
            });

            $(document).on('click', '.delete-student', function() {
                var studentId = $(this).data('student-id');
                var subjectId = $(this).data('subject-id');

                $('#delete_student').data('student-id', studentId).data('subject-id', subjectId).modal('show');
            });

            $('#delete_student .confirm-delete').click(function() {
                var studentId = $('#delete_student').data('student-id');
                var subjectId = $('#delete_student').data('subject-id');

                $.ajax({
                    url: '../../script/delete_student_subject.php',
                    type: 'POST',
                    data: { studentId: studentId, subjectId: subjectId },
                    success: function(response) {
                        $('#delete_student').modal('hide');
                        $('#subjectSelect').trigger('change');
                        $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Étudiant supprimé !</strong> L\'étudiant a bien été supprimé du cours.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                    },
                    error: function() {
                        $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Erreur !</strong> La suppression de l\'étudiant a échoué.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                    }
                });
            });
        });
    </script>
</body>
</html>
