<!DOCTYPE html>
<html lang="es-MX">
    <head>
        <meta charset="UTF-8">
        <title><?= $title ?></title>
        <link rel="icon" type="image/png" href="<?= base_url() ?>icon.png" />
        <!-- Viewport for Responsivity -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery.dataTables.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/mapplic.css">
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/estilos.css">
    </head>
    <body>
    <header>
		<nav class="navbar navbar-default">
		  	<div class="container-fluid">
				<div class="navbar-header">
				 	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
				 	</button>
				  	<a href="<?= base_url() ?>" class="brand-ceiba">
				  		<figure>
				  			<img src="<?= base_url() ?>assets/img/logos/logo_blanco.png" alt="La ceiba">
				  			<figcaption>La Ceiba</figcaption>
				  		</figure>
				  	</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="dropdown">
						  	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Registros <span class="caret"></span></a>
						  	<ul class="dropdown-menu">
								<li><a href="<?= base_url() ?>registros/manzanas">Mazanas</a></li>
								<li><a href="<?= base_url() ?>registros/lotes">Lotes</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="#">Otra cosa</a></li>
						  	</ul>
						</li>
				  	</ul>
				  	<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
						 	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Bienvenido: <?= $this->session->userdata('first_name') ?> <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="<?= base_url()?>auth/logout">Cerrar sesión</a></li>
						  	</ul>
						</li>
				  	</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
	</header>