<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
            <div class="col-xs-12">
                <table id="historial-ventas-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th data-visible="false">Id. venta</th>
                            <th class="all">Cliente</th>     
                            <!--<th>Detalles</th> -->                                                     
                            <th>Pagos retrasados</th>                            
                            <th>Pagos realizados con retraso</th> 
                            <th>Pagos realizados adelantados</th> 
                            <th>Pagos realizados en tiempo</th>  
                            <th>Pagos realizados</th>  
                            <th>Descripción</th>      
                            <th>Precio</th>  
                            <th>Comisión</th> 
                            <th>Total abonado</th>  
                            <th>Pagado en comisiones</th>  
                            <th>Líder</th>                          
                            <th>Generado por:</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($ventas as $venta):?>
                        <tr>                 
                            <td><?= $venta->id_venta; ?></td>               
                            <td>
                                <?= $venta->nombre_cliente; ?>
                                <a  
                                    data-toggle="popover" 
                                    tabindex="10" 
                                    title="Detalles:"
                                    data-placement="top" 
                                    data-content="<p>Correo: <?= $venta->email ?></p><p>Telefono: <?=$venta->phone?></p>">...</a>
                            </td>        
                            <!--<td></td>-->                    
                            <td><?= $venta->retraso; ?></td>
                            <td><?= $venta->retrasados; ?></td>
                            <td><?= $venta->adelantados; ?></td>
                            <td><?= $venta->en_tiempo; ?></td>
                            <th><?= $venta->realizados; ?></th>
                            <td><?= $venta->descripcion; ?></td>
                            <td>$<?= number_format($venta->precio,2); ?></td>
                            <td>$<?= number_format($venta->precio * ($venta->porcentaje_comision/100),2); ?></td>
                            <td>$<?= number_format($venta->pagado,2); ?></td>                            
                            <td>$<?= number_format($venta->comisionado,2); ?></td>
                            <td><?= $venta->nombre_lider; ?></td>
                            <td><?= $venta->nombre_user?></td>
                            <td>     
                                <?php 
                                if($venta->estado == 0 || $venta->estado == 1 || $venta->estado == 2): 
                                ?>
                                <a href="<?= base_url(); ?>reportes/contrato/<?= $venta->id_venta; ?>" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> contrato</a>
                                <a href="<?= base_url(); ?>reportes/<?= ($venta->version == 2) ? 'pagares' : 'recibos'?>/<?= $venta->id_venta; ?>" class="btn btn-primary" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> <?= ($venta->version == 2) ? 'pagarés' : ' recibos'?></a>
                                <a href="<?= base_url() ?>registros/pagos/<?= $venta->id_venta; ?>" target="_blank" class="btn btn-success" ><i class="fa fa-fw fa-search"></i>pagos</a>
                                    <?php 
                                    if($venta->estado == 2): 
                                    ?>
                                    <button class="btn btn-warning activar-venta">Activar</button>
                                    <?php 
                                    else: 
                                    ?>
                                    <button class="btn btn-warning cancelar-venta"><span class="fa fa-ban fa-lg"></span> </button>
                                    <?php 
                                    endif; 
                                    ?> 
                                <button title="Eliminar Contrato" class="btn btn-danger eliminar-venta"><span class="fa fa-trash fa-lg"></span></button>    
                                <?php 
                                else: 
                                ?>
                                <button class="btn btn-danger recuperar-venta">Recuperar</button>
                                <?php 
                                endif; 
                                ?>                                                              
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>