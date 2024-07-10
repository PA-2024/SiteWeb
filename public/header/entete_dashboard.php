<?php
$userName = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
?>

<div class="header">
    <div class="header-left">
        <a href="../../index.php" class="logo">
            <img src="../../assets/img/logo.png" width="35" height="35" alt=""> <span>GeSign</span>
        </a>
    </div>
    <a id="toggle_btn" href="javascript:void(0);"><img src="../../assets/img/icons/bar-icon.svg" alt=""></a>
    <a id="mobile_btn" class="mobile_btn float-start" href="#sidebar"><img src="../../assets/img/icons/bar-icon.svg" alt=""></a>
    <div class="top-nav-search mob-view">
        <form action="search.php" method="get">
            <input type="text" name="query" id="search-input" class="form-control" placeholder="Rechercher">
            <button type="submit" class="btn"><img src="../../assets/img/icons/search-normal.svg" alt=""></button>
        </form>
    </div>

    <ul class="nav user-menu float-end">
        <li class="nav-item dropdown d-none d-md-block">
            <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown"><img src="../../assets/img/icons/note-icon-01.svg" alt=""><span class="pulse"></span></a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span>Notifications</span>
                </div>
                <div class="drop-scroll">
                    <ul class="notification-list" id="notification-list">
                        <!-- Notifications will be fetched using AJAX -->
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="../misc/activities.php">Voir toutes les notifications</a>
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
                    <img src="https://i.pinimg.com/236x/54/72/d1/5472d1b09d3d724228109d381d617326.jpg" alt="Admin">
                </span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="../misc/profile.php">Mon profil</a>
                <a class="dropdown-item" href="../forms/edit-profile.php">Modifier mon profil</a>
                <a class="dropdown-item" href="../../script/logout.php">Me déconnecter</a>
            </div>
        </li>
    </ul>
    <div class="dropdown mobile-user-menu float-end">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></a>
        <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item" href="../misc/profile.php">Mon profil</a>
            <a class="dropdown-item" href="../forms/edit-profile.php">Modifier mon profil</a>
            <a class="dropdown-item" href="../../script/logout.php">Me déconnecter</a>
        </div>
    </div>
</div>

<script src="../../assets/js/jquery-3.7.1.min.js"></script>

<script>
    $(document).ready(function() {
        function fetchNotifications() {
            $.ajax({
                url: '../../script/fetch_notifications.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }
                    var notificationList = $('#notification-list');
                    notificationList.empty();
                    data.forEach(function(notification) {
                        var notificationItem = `
                            <li class="notification-message">
                                <a href="javascript:void(0);">
                                    <div class="media">
                                        <span class="avatar"><img alt="Notification" src="../../assets/img/icons/note-icon-01.svg" class="img-fluid"></span>
                                        <div class="media-body">
                                            <p class="noti-details"><span class="noti-title">${notification.message}</span></p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        `;
                        notificationList.append(notificationItem);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching notifications: ', textStatus, errorThrown);
                    console.log(jqXHR.responseText);
                }
            });
        }

        fetchNotifications();
    });
</script>
