<?php
include '../../header/entete.php';
require_once '../../../vendor/autoload.php';

use GeSign\SessionManager;
use GeSign\Student;
use GeSign\Sectors;

$sessionManager = SessionManager::getInstance();
$sessionManager->restrictAccessToLoginUsers();
$sessionManager->checkUserRole('Gestion Ecole');

// Récupération du token de l'utilisateur connecté
$token = $_SESSION['token'] ?? $_COOKIE['token'];

if (!$token) {
    header('Location: ../auth/login.php');
    exit;
}

$studentManager = new Student($token);
$sectorsManager = new Sectors();
$classes = $sectorsManager->fetchSectors(); // Récupération des classes disponibles

// Traitement de la sélection de la classe
$className = $_GET['class'] ?? null;
$students = [];

if ($className) {
    try {
        $students = $studentManager->fetchStudentsByClass($className);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
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
                                <li class="breadcrumb-item"><a href="../dashboard/director_dashboard.php">Tableau de bord</a></li>
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

                <!-- Formulaire pour sélectionner une classe -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <form method="GET" action="">
                            <div class="form-group">
                                <label for="class">Sélectionnez une classe :</label>
                                <select name="class" id="class" class="form-control">
                                    <option value="">-- Sélectionnez une classe --</option>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?php echo htmlspecialchars($class['sectors_Name']); ?>" <?php echo ($className === $class['sectors_Name']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($class['sectors_Name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Afficher les étudiants</button>
                        </form>
                    </div>
                </div>
                <div class="page-table-header mb-2">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="doctor-table-blk">
                                <h3>Liste des Étudiants</h3>
                                <div class="doctor-search-blk">
                                    <div class="top-nav-search table-search-blk">
                                        <form id="dataTableSearchForm">
                                            <input type="text" class="form-control" placeholder="Rechercher" id="dataTableSearchInput">
                                            <button type="submit" class="btn"><img src="../../assets/img/icons/search-normal.svg" alt="Search"></button>
                                        </form>
                                    </div>
                                    <div class="add-group">
                                        <a href="../forms/add_student.php" class="btn btn-primary add-pluss ms-2"><img src="../../assets/img/icons/plus.svg" alt=""></a>
                                        <a href="javascript:;" id="refreshTableBtn" class="btn btn-primary student-refresh ms-2"><img src="../../assets/img/icons/re-fresh.svg" alt="Refresh"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end float-end ms-auto download-grp">
                            <a href="javascript:;" id="export-csv" class="me-2"><img src="../../assets/img/icons/pdf-icon-03.svg" alt="Export to CSV"></a>
                            <a href="javascript:;" id="export-xlsx"><img src="../../assets/img/icons/pdf-icon-04.svg" alt="Export to XLSX"></a>
                        </div>
                    </div>
                </div>

                <!-- Table des étudiants -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table show-entire">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <th>Email</th>
                                                <th>Numéro</th>
                                                <th>Secteur</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($className && !empty($students)): ?>
                                                <?php foreach ($students as $student): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($student['student_User']['user_lastname']); ?></td>
                                                        <td><?php echo htmlspecialchars($student['student_User']['user_firstname']); ?></td>
                                                        <td><?php echo htmlspecialchars($student['student_User']['user_email']); ?></td>
                                                        <td><?php echo htmlspecialchars($student['student_User']['user_num']); ?></td>
                                                        <td><?php echo htmlspecialchars($student['student_Sectors']['sectors_Name']); ?></td>
                                                        <td class="text-end">
                                                            <div class="dropdown dropdown-action">
                                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="../forms/edit_student.php?id=<?php echo htmlspecialchars($student['student_Id']); ?>"><i class="fa-solid fa-pen-to-square m-r-5"></i> Éditer</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php elseif ($className): ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">Aucun étudiant trouvé pour la classe <?php echo htmlspecialchars($className); ?>.</td>
                                                </tr>
                                            <?php endif; ?>
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
                        <img src="../../assets/img/sent.png" alt="" width="50" height="46">
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

    <!-- Datatables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.36/build/pdfmake.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.1.36/vfs_fonts.js"></script>
    
    <!-- Custom JS -->
    <script src="../../assets/js/app.js"></script>

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
                        columns: [0, 1, 2, 3, 4] // Mettez à jour les index de colonne
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
                        columns: [0, 1, 2, 3, 4] // Mettez à jour les index de colonne
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
            var className = $('#class').val();
            $.ajax({
                url: '../../script/fetch_students_sectors_ajax.php',
                type: 'GET',
                data: { class: className },
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        updateTable(data);
                    }
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
                    student.student_User.user_lastname,
                    student.student_User.user_firstname,
                    student.student_User.user_email,
                    student.student_User.user_num,
                    student.student_Sectors.sectors_Name,
                    '<div class="dropdown dropdown-action"><a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a><div class="dropdown-menu dropdown-menu-end"><a class="dropdown-item" href="edit_student.php?id=' + student.student_Id + '">Éditer</a><a class="dropdown-item delete-link" href="#" data-id=' + student.student_Id + '>Supprimer</a></div></div>'
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
                        url: '../../script/delete_student_sectors.php',
                        type: 'POST',
                        data: { studentId: idToDelete },
                        success: function(response) {
                            response = JSON.parse(response);
                            if (response.success) {
                                $('#delete_student').modal('hide');
                                refreshTable();
                                $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Étudiant supprimé !</strong> Cet étudiant a bien été supprimé.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                            } else {
                                $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Erreur !</strong> ' + response.error + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
                            }
                        },
                        error: function() {
                            $('#alert-placeholder').html('<div class="card bg-white"><div class="card-body"><div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Erreur !</strong> La suppression de cet étudiant a échoué.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>');
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
