<?php
$userName = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
?>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

        <div class="header">
			<div class="header-left">
				<a href="index.php" class="logo">
					<img src="assets/img/logo.png" width="35" height="35" alt=""> <span>GeSign</span>
				</a>
			</div>
			<a id="toggle_btn" href="javascript:void(0);"><img src="assets/img/icons/bar-icon.svg"  alt=""></a>
            <a id="mobile_btn" class="mobile_btn float-start" href="#sidebar"><img src="assets/img/icons/bar-icon.svg"  alt=""></a>
            <div class="top-nav-search mob-view">
                <form action="search.php" method="get">
                    <input type="text" name="query" id="search-input" class="form-control" placeholder="Rechercher">
                    <button type="submit" class="btn"><img src="assets/img/icons/search-normal.svg" alt=""></button>
                </form>
            </div>
            <script>
            $(function() {
                var availableTags = [
                    "Gérer les professeurs",
                    "Gérer les élèves",
                    "Gérer les cours",
                    "Gérer les écoles",
                    "Voir les absences",
                    "Voir les présences",
                    "Ajouter un professeur",
                    "Ajouter un élève",
                    "Ajouter un cours"
                ];

                $("#search-input").autocomplete({
                    source: availableTags,
                    select: function(event, ui) {
                        var selectedValue = ui.item.value;
                        var searchQueryLower = selectedValue.toLowerCase();

                        if (searchQueryLower.includes('prof')) {
                            window.location.href = 'professor_list.php';
                        } else if (searchQueryLower.includes('élève') || searchQueryLower.includes('etudiant')) {
                            window.location.href = 'student_list.php';
                        } else if (searchQueryLower.includes('cours')) {
                            window.location.href = 'subjects_list.php';
                        } else if (searchQueryLower.includes('ecole')) {
                            window.location.href = 'school_list.php';
                        } else if (searchQueryLower.includes('absence')) {
                            window.location.href = 'attendance_list.php';
                        } else if (searchQueryLower.includes('présence')) {
                            window.location.href = 'presence_list.php';
                        } else if (searchQueryLower.includes('ajouter un professeur')) {
                            window.location.href = 'add_professor.php';
                        } else if (searchQueryLower.includes('ajouter un élève')) {
                            window.location.href = 'add_student.php';
                        } else if (searchQueryLower.includes('ajouter un cours')) {
                            window.location.href = 'add_subject.php';
                        }
                    }
                });
            });
            </script>

            <ul class="nav user-menu float-end">
                <li class="nav-item dropdown d-none d-md-block">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown"><img src="assets/img/icons/note-icon-01.svg" alt=""><span class="pulse"></span> </a>
                    <div class="dropdown-menu notifications">
                        <div class="topnav-dropdown-header">
                            <span>Notifications</span>
                        </div>
                        <div class="drop-scroll">
                            <ul class="notification-list">
                            </ul>
                        </div>
                        <div class="topnav-dropdown-footer">
                            <a href="activities.html">Voir toutes les notifications</a>
                        </div>
                    </div>
                </li>
				<li class="nav-item dropdown has-arrow user-profile-list">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-bs-toggle="dropdown">
						<div class="user-names">
							<h5><?php echo htmlspecialchars($userName); ?></h5>
                        	<span><?php echo htmlspecialchars($role); ?></span>
						</div>
						<span class="user-img">
							<img  src="https://i.pinimg.com/236x/54/72/d1/5472d1b09d3d724228109d381d617326.jpg"  alt="Admin">
						</span>
                    </a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="profile.php">Mon profil</a>
						<a class="dropdown-item" href="edit-profile.php">Modifier mon profil</a>
						<a class="dropdown-item" href="script/logout.php">Me déconnecter</a>
					</div>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu float-end">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="profile.php">Mon profil</a>
					<a class="dropdown-item" href="edit-profile.php">Modifier mon profil</a>
					<a class="dropdown-item" href="script/logout.php">Me déconnecter</a>
                </div>
            </div>
        </div>