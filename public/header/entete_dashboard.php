        <div class="header">
			<div class="header-left">
				<a href="index.php" class="logo">
					<img src="assets/img/logo.png" width="35" height="35" alt=""> <span>GeSign</span>
				</a>
			</div>
			<a id="toggle_btn" href="javascript:void(0);"><img src="assets/img/icons/bar-icon.svg"  alt=""></a>
            <a id="mobile_btn" class="mobile_btn float-start" href="#sidebar"><img src="assets/img/icons/bar-icon.svg"  alt=""></a>
			<div class="top-nav-search mob-view">
				<form>
					<input type="text" class="form-control" placeholder="Rechercher">
					<a class="btn" ><img src="assets/img/icons/search-normal.svg" alt=""></a>
				</form>
			</div>
            <ul class="nav user-menu float-end">
                <li class="nav-item dropdown d-none d-md-block">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown"><img src="assets/img/icons/note-icon-02.svg" alt=""><span class="pulse"></span> </a>
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
                <li class="nav-item dropdown d-none d-md-block">
                    <a href="javascript:void(0);" id="open_msg_box" class="hasnotifications nav-link"><img src="assets/img/icons/note-icon-01.svg" alt=""><span class="pulse"></span> </a>
                </li>
				<li class="nav-item dropdown has-arrow user-profile-list">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-bs-toggle="dropdown">
						<div class="user-names">
							<h5>Bastien LEUWERS </h5>
                        	<span>Admin</span>
						</div>
						<span class="user-img">
							<img  src="assets/img/user-06.jpg"  alt="Admin">
						</span>
                    </a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="profile.php">Mon profil</a>
						<a class="dropdown-item" href="edit-profile.php">Modifier mon profil</a>
						<a class="dropdown-item" href="settings.php">Paramètres</a>
						<a class="dropdown-item" href="script/logout.php">Me déconnecter</a>
					</div>
                </li>
				<li class="nav-item ">
                    <a href="settings.html"  class="hasnotifications nav-link"><img src="assets/img/icons/setting-icon-01.svg" alt=""> </a>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu float-end">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="profile.php">Mon profil</a>
					<a class="dropdown-item" href="edit-profile.php">Modifier mon profil</a>
					<a class="dropdown-item" href="settings.php">Paramètres</a>
					<a class="dropdown-item" href="script/logout.php">Me déconnecter</a>
                </div>
            </div>
        </div>