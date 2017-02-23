<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
            <div class="col-xs-12">
                <table id="pagos-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th data-visible="false">Id. Historial</th>
                            <th>Concepto</th>
                            <th>Adeudo</th>
                            <th>Fecha de pago</th>
                            <th>Estado</th>
                            <th>Detalles</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    foreach($pagos as $pago):
                ?>
                    
                        <tr>
                            <td><?= $pago->id_historial ?></td>
                            <td><?= $pago->concepto ?></td>
                            <td><?= $pago->abono ?></td>
                            <td><?= $pago->fecha ?></td>
                            <td><?= ($pago->estado == 0) ? 'Pendiente' : 'Pagado'?></td>
                            <td><?php 
                                        $btnPago = false;
                                        if ($pago->estado == 0) { 
                                            if($pago->daysAccumulated > 0) { 
                                                echo 'Tiene un retraso en pago de: '.$pago->daysAccumulated . ' días';
                                                $btnPago = true;
                                            } else if($pago->daysAccumulated == 0){
                                                echo 'Hoy es día de pago';
                                                $btnPago = true;
                                            }else{
                                                echo 'Aun no es fecha de pago';

                                            }
                                        } else if ($pago->estado == 1){
                                            if($pago->daysAccumulated > 0) { 
                                                echo 'Realizó el pago con un retrazo de: '.$pago->daysAccumulated.' días';                                            
                                            } else if($pago->daysAccumulated == 0) {
                                                echo 'Pagado en tiempo';
                                            }
                                            echo '<div>Pago: $' . number_format($pago->pago,2) .'</div>';
                                            echo '<div>Fecha: ' . $pago->fecha_pago .'</div>';
                                            echo '<div>Comisión: $' . number_format($pago->comision,2) .'</div>';
                                            echo '<div>Penalización: $' . number_format($pago->penalizacion,2) .'</div>';
                                            echo '<div>Total: $' . number_format($pago->total,2) .'</div>';
                                        }
                                ?>
                            </td>
                            <td><?php echo ($btnPago) ? '<button class="btn btn-success" data-toggle="modal" data-target="#pago">Registrar pago</button>' : '' ?> </td>
                        </tr>
                    
                <?php 
                    endforeach;
                ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>


<!-- Modal -->
<div class="modal fade" id="pago" tabindex="-1" role="dialog" aria-labelledby="modalPago">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>