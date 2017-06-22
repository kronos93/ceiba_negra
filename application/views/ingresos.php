<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
			<div class="col-xs-12">				
				<legend>
					Ingresos
				</legend>
                <form id="frm-filter-ingreso" action="<?= base_url(); ?>registros/ingresos/<?= $name ?>/<?= $id ?>" method="get">
                    <div class="row row-equal">
                        <div class="form-group col-xs-12 col-sm-3">
                            <label class="" >Fecha inical:</label>
                            <input type="text" name="init_date" id="init_date" class="form-control datepicker" value="<?= $init_date->format('d-m-Y'); ?>" required/>
                        </div>
                        <div class="form-group col-xs-12 col-sm-3">
                            <label class="" >Fecha final:</label>
                            <input type="text" name="end_date" id="end_date" class="form-control datepicker" value="<?= isset($end_date) ? $end_date->format('d-m-Y') : '' ?>" required/>
                       </div>
                       <div class="form-group col-xs-12 col-sm-1">
                            <input type="submit" value="Filtrar" class="btn btn-primary"/>
                       </div>
                       <div class="form-group col-xs-12 col-sm-2">
                            <a class="btn btn-success" href="<?= base_url(); ?>registros/ingresos/<?= $name ?>/<?= $id ?>/<?= $init_date->format('d-m-Y'); ?>/<?= $end_date->format('d-m-Y') ?>/download_excel" target="_blank">Descargar Excel</a>
                       </div>
                    </div>
                   <div class="clearfix"></div>
                </form>
                
				<table id="historial-ingresos-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
					<thead>
						<tr>							
							<th class="all">Nombre </th>	
                            <th>Fecha de ingreso / pago</th>
                            <th>Ingreso</th>
                            <th>Comision</th>
                            <th>Penalizacion</th>	
                            <th class="all">Total</th>													
                        </tr>
					</thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        $pagos = 0;
                        $total_comision = 0;
                        $total_penalizacion = 0;
                        foreach($ingresos as $ingreso){ 
                        ?>
                        <tr>
                            <!--<td><?= $ingreso->id_historial ?> </td>  -->                     
                            <td><?= $ingreso->nombre ?> <a data-toggle="popover" title="Cliente:" data-placement="top" data-content="<p><?= $ingreso->nombre_cliente ?></p>"><span class="fa fa-info-circle fa-lg fa-fw"></span></a></td>
                            <td><?= $ingreso->fecha_pago ?> </td>
                            <td>$<?= number_format($ingreso->pago,2) ?> </td>                            
                            <td>$<?= number_format($ingreso->comision,2) ?> </td>
                            <td>$<?= number_format($ingreso->penalizacion,2) ?> </td>
                            <td>$<?= number_format($ingreso->pago + $ingreso->penalizacion - $ingreso->comision,2)?> </td>                            
                        </tr>
                        <?php 
                            $pagos += $ingreso->pago;
                            $total += $ingreso->pago + $ingreso->penalizacion - $ingreso->comision;
                            $total_comision += $ingreso->comision;
                            $total_penalizacion += $ingreso->penalizacion;
                        }
                        ?>
                    </tbody>
                    <tfoot>                    
                        <tr>
                            <td></td>
                            <td></td>
                            <td>$<?= number_format($pagos,2);?></td>
                            <td>$<?= number_format($total_comision,2);?></td>
                            <td>$<?= number_format($total_penalizacion,2);?></td>
                            <td>$<?= number_format($total,2);?></td>
                        </tr>
                    </tfoot>
				</table>
			</div>
		</div>
	</div>
</main>