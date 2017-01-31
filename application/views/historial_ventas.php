<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
            <div class="col-xs-12">
                <table id="historial-ventas-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="all">Cliente</th>          
                            <th class="all">Líder</th>                            
                            <th class="all">Contrato</th>
                            <th class="all">Pagares</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($ventas as $venta):?>
                        <tr>
                            <td><?= $venta->nombre_cliente; ?></td>
                            <td><?= $venta->nombre_lider; ?></td>
                            <td><a href="<?= base_url(); ?>reportes/contrato/<?= $venta->id_venta; ?>" class="btn btn-success" target="_blank">Contrato</a></td>
                            <td><a href="<?= base_url(); ?>reportes/pagares/<?= $venta->id_venta; ?>" class="btn btn-info" target="_blank">Pagares</a></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>