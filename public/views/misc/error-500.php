<?php include '../../header/entete.php'; ?>
<body class="error-pages">
    <div class="main-wrapper error-wrapper">
        <div class="error-box">
            <img class="img-fluid" src="../../assets/img/error-02.png" alt="Logo">
            <h3><img class="img-fluid mb-0" src="../../assets/img/icons/danger.svg" alt="Logo"> Internal Server Error</h3>
            <p>Il semblerait qu'il y ait un souci dans la requête... Merci de réessayer plus tard !</p>
            <button onclick="goToHome()" class="btn btn-primary go-home">Retour</button>
        </div>
    </div>
    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function goToHome() {
            window.location.href = '../../index.php';
        }
    </script>
</body>

</html>
