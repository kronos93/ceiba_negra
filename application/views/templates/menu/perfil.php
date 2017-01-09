<ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Bienvenido: <?= $this->session->userdata('first_name') ?> <span class="caret"></span></a>
        <ul class="dropdown-menu">
        	<?php if ($this->ion_auth->in_group('administrador')) { ?>
            <li><a href="<?= base_url()?>auth/">Usuarios</a></li>
            <li role="separator" class="divider"></li>
            <?php } ?>            
            <li><a href="<?= base_url()?>auth/logout">Cerrar sesión</a></li>
        </ul>
    </li>
</ul>