<?php
// Auteur : Capdrake (Bastien LEUWERS)
include 'header/entete.php';
require_once '../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Student;

$sessionManager = new SessionManager();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: login.php');
    exit;
}

$studentManager = new Student($token);
$students = $studentManager->fetchStudents();
?>
<body>
    <div class="main-wrapper">
        <?php include 'header/entete_dashboard.php'; ?>
        <?php include 'menu/menu_gestion.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="students_list.php">Étudiants</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Liste des Étudiants</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="alert-placeholder"></div>
                <?php if (isset($_GET['message'])): ?>
                    <div class="card bg-white">
                        <div class="card-body">
                            <?php if ($_GET['message'] == 'success'): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Étudiant bien ajouté !</strong> Cet étudiant a bien été ajouté.
                            <?php elseif ($_GET['message'] == 'error'): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Erreur d'ajout !</strong> Cet étudiant n'a pas pu être ajouté...
                            <?php endif; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="page-table-header mb-2">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="student-table-blk">
                                <h3>Liste des Étudiants</h3>
                                <div class="student-search-blk">
                                    <div class="top-nav-search table-search-blk">
                                        <form id="dataTableSearchForm">
                                            <input type="text" class="form-control" placeholder="Rechercher" id="dataTableSearchInput">
                                            <button type="submit" class="btn"><img src="assets/img/icons/search-normal.svg" alt="Search"></button>
                                        </form>
                                    </div>
                                    <div class="add-group">
                                        <a href="add_student.php" class="btn btn-primary add-pluss ms-2"><img src="assets/img/icons/plus.svg" alt=""></a>
                                        <a href="javascript:;" id="refreshTableBtn" class="btn btn-primary student-refresh ms-2"><img src="assets/img/icons/re-fresh.svg" alt="Refresh"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end float-end ms-auto download-grp">
                            <a href="javascript:;" id="export-csv" class="me-2"><img src="assets/img/icons/pdf-icon-03.svg" alt="Export to CSV"></a>
                            <a href="javascript:;" id="export-xlsx"><img src="assets/img/icons/pdf-icon-04.svg" alt="Export to XLSX"></a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table show-entire">
                            <div class="card-body">
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
                                                <th>Prénom</th>
                                                <th>Email</th>
                                                <th>Numéro</th>
                                                <th>Secteur</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($students as $student): ?>
                                                <tr>
                                                    <td>
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input" type="checkbox" value="<?php echo htmlspecialchars($student['student_Id']); ?>">
                                                        </div>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($student['student_Id']); ?></td>
                                                    <td><?php echo htmlspecialchars($student['student_User']['user_lastname']); ?></td>
                                                    <td><?php echo htmlspecialchars($student['student_User']['user_firstname']); ?></td>
                                                    <td><?php echo htmlspecialchars($student['student_User']['user_email']); ?></td>
                                                    <td><?php echo htmlspecialchars($student['student_User']['user_num']); ?></td>
                                                    <td><?php echo htmlspecialchars($student['student_Sectors']['sectors_Name']); ?></td>
                                                    <td class="text-end">
                                                        <div class="dropdown dropdown-action">
                                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="edit_student.php?id=<?php echo htmlspecialchars($student['student_Id']); ?>"><i class="fa-solid fa-pen-to-square m-r-5"></i> Éditer</a>
                                                                <a class="dropdown-item delete-link" href="#" data-bs-toggle="modal" data-bs-target="#delete_student" data-id="<?php echo htmlspecialchars($student['student_Id']); ?>"><i class="fa fa-trash-alt m-r-5"></i> Supprimer</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>							
                    </div>					
                </div>
            </div>
        </div>

        <div id="delete_student" class="modal fade delete-modal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img src="assets/img/sent.png" alt="" width="50" height="46">
                        <h3>Êtes-vous sûr de vouloir supprimer cet étudiant ?</h3>
                        <div class="m-t-20">
                            <a href="#" class="btn btn-white" data-bs-dismiss="modal">Fermer</a>
                            <button type="button" class="btn btn-danger confirm-delete">Oui</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sidebar-overlay" data-reff=""></div>

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

    <!-- Datatables JS -->
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/datatables.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/app.js"></script>

    <!-- Script pour gérer le tableau et la suppression -->
    <script>
    $(document).ready(function() {
        var dataTable = $('.datatable').DataTable({
            "searching": true,
            "bDestroy": true,
            "dom": 'lrtip',
            select: 'multi',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    title: 'Student Data',
                    exportOptions: {
                        columns: ':visible'
                    },
                    customize: function(doc) {
                        doc.content.splice(0, 1, {
                            text: 'Report for Student Data',
                            fontSize: 16,
                            alignment: 'center'
                        });
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    title: 'Student Data Report',
                    exportOptions: {
                        modifier: {
                            selected: true
                        },
                        columns: [1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    title: 'Student Data Report',
                    exportOptions: {
                        modifier: {
                            selected: true
                        },
                        columns: [1, 2, 3, 4, 5, 6]
                    }
                }
            ],
            select: true
        });

        $('#export-csv').on('click', function() {
            dataTable.button('.buttons-csv').trigger();
        });
        $('#export-xlsx').on('click', function() {
            dataTable.button('.buttons-excel').trigger();
        });

        $(document).on('submit', '#dataTableSearchForm', function(event) {
            event.preventDefault();
            var searchTerm = $('#dataTableSearchInput').val();
            dataTable.search(searchTerm).draw();
        });

        $('#dataTableSearchInput').on('keyup change', function() {
            dataTable.search(this.value).draw();
        });

        $('#refreshTableBtn').click(function() {
            refreshTable();
        });

        function refreshTable() {
            $.ajax({
                url: 'script/fetch_students_ajax.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    updateTable(data);
                },
                error: function() {
                    alert('Impossible de recharger les données.');
                }
            });
        }

        function updateTable(data) {
            dataTable.clear();
            $.each(data, function(index, student) {
                dataTable.row.add([
                    '<input type="checkbox" class="form-check-input" value="' + student.student_Id + '">',
                    student.student_Id,
                    student.student_User.user_lastname,
                    student.student_User.user_firstname,
                    student.student_User.user_email,
                    student.student_User.user_num,
                    student.student_Sectors.sectors_Name,
                    '<div class="dropdown dropdown-action"><a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu"><a class="dropdown-item" href="edit_student.php?id=' + student.student_Id + '">Éditer</a><a class="dropdown-item delete-link" href="#" data-id=' + student.student_Id + '>Supprimer</a></div></div>'
                ]).draw(false);
            });
            rebindEvents();
        }

        function rebindEvents() {
            $(document).on('click', '.delete-link', function() {
                var studentId = $(this).data('id');
                $('#delete_student').data('id', studentId);
                $('#delete_student').modal('show');
            });

            $('#delete_student').on('shown.bs.modal', function() {
                $(this).find('.btn-danger').off('click').on('click', function() {
                    var idToDelete = $('#delete_student').data('id');
                    $.ajax({
                        url: 'script/student_scripts.php?action=delete',
                        type: 'POST',
                        data: { studentId: idToDelete },
                        success: function(response) {
                            $('#delete_student').modal('hide');
                            refreshTable();
                            $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Étudiant supprimé !</strong> Cet étudiant a bien été supprimé.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                        },
                        error: function() {
                            $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Erreur !</strong> La suppression de l'étudiant a échoué.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                        }
                    });
                });
            });
        }
        rebindEvents();
    });
    </script>
</body>
</html>
