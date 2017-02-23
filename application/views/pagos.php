<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
            <div class="col-xs-12">
                <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                    <tr>
                        <th>Concepto</th>
                        <th>Adeudo</th>
                        <th>Fecha de pago</th>
                        <th>Estado</th>
                        <th>Retraso</th>
                    </tr>
                <?php
                    foreach($pagos as $pago):
                ?>
                    <tr>
                        <td><?=$pago->concepto ?></td>
                        <td><?=$pago->abono ?></td>
                        <td><?=$pago->fecha ?></td>
                        <td><?= ($pago->estado == 0) ? 'Pendiente de pago' : 'Pagado' ?></td>
                        <td></td>
                    </tr>
                <?php 
                    endforeach;
                ?>
                </table>
            </div>
        </div>
    </div>
</main>