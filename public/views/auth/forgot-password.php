<!-- Auteur : Capdrake (Bastien LEUWERS) -->
<?php include '../../header/entete_login.php'; ?>
    <body>
	
		<!-- Main Wrapper -->
		<div class="main-wrapper login-body">
			<div class="container-fluid px-0">
				<div class="row">
				
					<!-- Login logo -->
					<div class="col-lg-6 login-wrap d-flex justify-content-center align-items-center">
						<div>
							<div class="log-img">
								<img class="img-fluid" src="../../assets/img/logo.png" alt="Logo" style="width: 600px; height: auto;">
							</div>
						</div>
					</div>
					<!-- /Login logo -->
					
					<!-- Login Content -->
					<div class="col-lg-6 login-wrap-bg">
						<div class="login-wrapper">
							<div class="loginbox">								
								<div class="login-right">
									<div class="login-right-wrap">
										<div class="account-logo">
											<a href="index.php"><img src="../../assets/img/banner.png" alt="" style="width: 150px; height: auto;"></a>
										</div>
										<h2>Réinitialiser votre mot de passe</h2>
										<!-- Form -->
										<form action="forgot-password.php">
											<div class="input-block">
												<label >Email <span class="login-danger">*</span></label>
												<input class="form-control" type="text" >
											</div>
											<div class="input-block login-btn">
												<button class="btn btn-primary btn-block" type="submit">Envoyer</button>
											</div>
										</form>
										<!-- /Form -->
										  
										<div class="next-sign">
											<p class="account-subtitle">Vous voulez vous connecter?  <a href="login.php">Connexion</a></p>											
										</div>
									</div>
								</div>
							</div>
							
						</div>
					</div>
					<!-- /Login Content -->
					
				</div>
			</div>
		</div>
		<!-- /Main Wrapper -->
		
		<!-- jQuery -->
		<script src="../../assets/js/jquery-3.7.1.min.js"></script>
		
		<!-- Bootstrap Core JS -->
		<script src="../../assets/js/bootstrap.bundle.min.js"></script>
		
		<!-- Feather Js -->
		<script src="../../assets/js/feather.min.js"></script>
		
		<!-- Custom JS -->
		<script src="../../assets/js/app.js"></script>
		
    </body>
</html>