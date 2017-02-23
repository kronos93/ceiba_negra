<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
            <div class="col-xs-12">
                <table id="historial-ventas-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            
                            <th class="all">Cliente</th>          
                            <th>Líder</th>     
                            <th>Precio</th>  
                            <th>Comisión</th>                            
                            <th>Generado por:</th>
                            <th>Contrato</th>
                            <th class="all">Pagarés/Recibos</th>
                            <th class="all">Pagos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($ventas as $venta):?>
                        <tr>
                            
                            <td><?= $venta->nombre_cliente; ?></td>
                            <td><?= $venta->nombre_lider; ?></td>
                            <td>$<?= number_format($venta->precio,2); ?></td>
                            <td>$<?= number_format($venta->precio * ($venta->porcentaje_comision/100),2); ?></td>
                            <td><?= $venta->nombre_user?></td>
                            <td><a href="<?= base_url(); ?>reportes/contrato/<?= $venta->id_venta; ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Ver contrato</a></td>
                            <td><a href="<?= base_url(); ?>reportes/<?= ($venta->version == 2) ? 'pagares' : 'recibos'?>/<?= $venta->id_venta; ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> <?= ($venta->version == 2) ? 'Ver pagarés' : 'Ver recibos'?></a></td>
                            <td><a href="<?= base_url() ?>registros/pagos/<?= $venta->id_venta; ?>" target="_blank" class="btn btn-success" ><i class="fa fa-fw fa-search"></i>Ver pagos</a></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>