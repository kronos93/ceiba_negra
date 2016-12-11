<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<!-- Viewport for Responsivity -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">	
	<title>Iniciar sesión</title>
	<link rel="icon" type="image/png" href="<?= base_url() ?>icon.png" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/estilos.css">
</head>
<body>
	<div class="wrap-login">
		<div class="wrap-login__header">
			<div class="container-fluid container">
				<div class="header-container">
					<div class="header-container__brand">
						<img class="img-responsive" src="<?= base_url() ?>assets/img/logos/logo.png" alt="">
					</div>
					<div class="header-container__name">
						<h1>Huertos la Ceiba</h1>
					</div>
				</div>
			</div>
		</div>
		<div class="wrap-login__body">
			<div class="login-form">
				<?= form_open("auth/login") ?>
          <div id="infoMessage"><?= $message ?></div>
					<div class="form-group">
					 	<?= lang('login_identity_label', 'identity');?>    
					 	<div class="input-group">
					  		<span class="input-group-addon" id="sizing-addon1">
					  			<i class="fa fa-user"></i>
					  		</span>
                <?php $identity['class'] = "form-control"; $identity['placeholder'] = "Usuario"; $identity['aria-describedby'] = "sizing-addon1"?>
                <?= form_input($identity);?>
						</div>
					</div>
					<div class="form-group">
					 	<?= lang('login_password_label', 'password') ?>
					 	<div class="input-group">
					  		<span class="input-group-addon" id="sizing-addon1">
					  			<i class="fa fa-lock"></i>
					  		</span>
                <?php $password['class'] = "form-control"; $password['placeholder'] = "Ingrese su contraseña"; $password['aria-describedby'] = "sizing-addon1";?>
                <?= form_input($password);?>
						</div>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-submit-login" value="Entrar">
					</div>
				<?=  form_close();?>
			</div>
		</div>
	</div>
	<footer>
		<div class="container-fluid container">
			<div class="row">
				<div class="col-xs-12">
					<p>Derechos Reservados Huertos La Ceiba <?= Date('Y') ?></p>
				</div>
			</div>
		</div>
	</footer>
<script src="https://code.jquery.com/jquery-2.2.4.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
<?php 
/*
<h1><?php echo lang('login_heading');?></h1>
<p><?php echo lang('login_subheading');?></p>



<?= form_open("auth/login");?>

  <p>
    <?php echo lang('login_identity_label', 'identity');?>
    <?php echo form_input($identity);?>
  </p>

  <p>
    <?php echo lang('login_password_label', 'password');?>
    <?php echo form_input($password);?>
  </p>

  <p>
    <?php echo lang('login_remember_label', 'remember');?>
    <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
  </p>


  <p><?php echo form_submit('submit', lang('login_submit_btn'));?></p>

<?php echo form_close();?>

<p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>*/
?>