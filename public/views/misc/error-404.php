<?php include '../../header/entete.php'; ?>
<body class="error-pages">
    <div class="main-wrapper error-wrapper">
        <div class="error-box">
			<img class="img-fluid" src="../../assets/img/error-01.png" alt="Logo" >
            <h3><img class="img-fluid mb-0" src="../../assets/img/icons/danger.svg" alt="Logo">  Service indisponible</h3>
            <p>Vous avez peut-être mal saisi l'adresse ou la page a peut-être été déplacée (ou vous n'avez juste pas la permission? :o).</p>
            <button onclick="goBack()" class="btn btn-primary go-home">Retour</button>
        </div>
    </div>
    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>