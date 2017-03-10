<?php if ($this->ion_auth->logged_in()) : ?>
<ul class="nav navbar-nav">
    <li><a href="<?= base_url() ?>"><span class="fa fa-map"></span> Plano</a></li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Registros <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <!-- Links para nuevos registros -->
            <li><a href="<?= base_url() ?>registros/manzanas">Mazanas</a></li>
            <li><a href="<?= base_url() ?>registros/huertos">Huertos</a></li>
            <?php if ($this->ion_auth->in_group('administrador')) : ?>
            <li><a href="<?= base_url() ?>registros/comisiones">Comisiones</a></li>
            <li><a href="<?= base_url() ?>registros/opciones_de_ingreso">Opciones de ingreso</a></li>            
            <?php endif;?>
            <?php if ($this->ion_auth->in_group(['administrador','miembro'])) : ?>
            <li role="separator" class="divider"></li>
            <li><a href="<?= base_url() ?>venta/historial_de_ventas">Historial de ventas</a></li>
            <?php endif;?>
        </ul>
    </li>   
</ul>
<?php endif; ?>