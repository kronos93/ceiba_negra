<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
            <div class="col-xs-12">
                <table id="historial-ventas-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="all">Cliente</th>          
                            <th class="all">Líder</th>     
                            <th class="all">Precio</th>  
                            <th class="all">Comisión</th>                            
                            <th class="all">Contrato</th>
                            <th class="all">Pagarés/Recibos</th>
                            <th class="all">Registrar un pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($ventas as $venta):?>
                        <tr>
                            <td><?= $venta->nombre_cliente; ?></td>
                            <td><?= $venta->nombre_lider; ?></td>
                            <td>$<?= number_format($venta->precio,2); ?></td>
                            <td>$<?= number_format($venta->descuento,2); ?></td>
                            <td><a href="<?= base_url(); ?>reportes/contrato/<?= $venta->id_venta; ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Ver contrato</a></td>
                            <td><a href="<?= base_url(); ?>reportes/<?= ($venta->version == 1) ? 'pagares' : 'recibos'?>/<?= $venta->id_venta; ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> <?= ($venta->version == 1) ? 'Ver pagarés' : 'Ver recibos'?></a></td>
                            <td></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>