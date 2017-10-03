<?php if ($this->ion_auth->logged_in()) : ?>
<ul class="nav navbar-nav">
    <li><a href="<?= base_url() ?>"><span class="fa fa-map"></span> Plano</a></li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Registros <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <!-- Links para nuevos registros -->
            <li><a href="<?= base_url() ?>registros/manzanas">Mazanas</a></li>
            <li><a href="<?= base_url() ?>registros/huertos">Huertos</a></li>
            <li><a href="<?= base_url() ?>registros/opciones_de_pago">Opciones de pago</a></li>
            <li><a href="<?= base_url() ?>registros/reservas_vencidas">Reservas vencidas</a></li>
        </ul>
    </li>
    <?php if ($this->ion_auth->in_group(['administrador','miembro'])) : ?>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ventas<span class="caret"></span></a>
        <ul class="dropdown-menu">
            <!-- Links para nuevos registros -->
            <?php if ($this->ion_auth->in_group(['administrador','miembro'])) : ?>
            <li><a href="<?= base_url() ?>venta/historial_de_ventas">Historial - Contratos</a></li>
            <li role="separator" class="divider"></li>
            <?php endif;?>
            <?php if ($this->ion_auth->in_group('administrador')) : ?>
            <li><a href="<?= base_url() ?>registros/ingresos">Ingresos</a></li>
            <li><a href="<?= base_url() ?>registros/comisiones">Comisiones</a></li>
            <?php endif;?>
        </ul>
    </li>
    <?php endif;?>
    <li><a href="<?= base_url() ?>registros/reservas/">Reservas<span id="reservas-badge" class="badge badge-info"><?= $this->reservas->total() ?></span></a></li>
    <li><a href="<?= base_url() ?>registros/celebraciones/"><i class="fa fa-birthday-cake" aria-hidden="true"></i> Cumpleaños <span id="reservas-badge" class="badge badge-info"><?= count($this->celebraciones->birthdays()) ?></span></a></li>
</ul>
<?php endif; ?>
