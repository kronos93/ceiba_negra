<main class="wrap-main">
    <div class="container-fluid container">
        <div class="row">
            <div class="col-xs-12">
                <table id="historial-ventas-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th data-visible="false">Id. venta</th>
                            <th class="all">Cliente</th>
                            <th>Descripción</th>
                            <th>Pagos retrasados</th>
                            <th>Detalles</th> 
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
                        <?php foreach ($ventas as $venta) :?>
                        <tr>
                            <td><?= $venta->id_venta; ?></td>
                            <td><?= $venta->nombre_cliente; ?><a data-toggle="popover" title="Detalles:" data-placement="top" data-content="<p>Correo: <a href='mailto:<?= $venta->email ?>'> <?= $venta->email ?></a></p><p>Telefono: <a href='tel:<?=$venta->phone?>'><span class='phone'><?=$venta->phone?></span></a></p>"><span class="fa fa-info-circle fa-lg fa-fw"></span></a></td>
                            <td><?= $venta->descripcion; ?></td>
                            <td><?= $venta->retraso; ?></td>
                            <td>
                                Pagado en tiempo: <?= $venta->en_tiempo; ?>
                                Pagado con retraso: <?= $venta->retrasados; ?>
                                Adelantado: <?= $venta->adelantados; ?>
                                Realizados: <?= $venta->realizados; ?>
                            </td>
                            <td><?= $venta->precio ?></td>
                            <td><?= $venta->comision ?></td>
                            <td><?= $venta->pagado ?></td>
                            <td><?= $venta->comisionado?></td>
                            <td><?= $venta->nombre_lider; ?></td>
                            <td><?= $venta->nombre_user?></td>
                            <td>
                                <?php
                                if ($venta->estado == 0 || $venta->estado == 1|| $venta->estado== 2) :
                                ?>
                                    <a href="<?= base_url(); ?>reportes/contrato/<?= $venta->id_venta; ?>" class="btn btn-default" target=""><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Contrato</a>
                                    <a href="<?= base_url(); ?>reportes/<?= ($venta->version == 2) ? 'pagares' : 'recibos'?>/<?= $venta->id_venta; ?>" class="btn btn-primary" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i> <?= ($venta->version == 2) ? ' Pagarés' : ' Recibos'?></a>
                                    <a href="<?= base_url() ?>registros/pagos/<?= $venta->id_venta; ?>" target="_blank" class="btn btn-info"><i class="fa fa-fw fa-eye"></i> Pagos</a>
                                    <?php
                                    if ($venta->estado == 0) : //Activo 0
                                    ?>
                                    <button title="Cancelar Contrato" class="btn btn-warning cancelar-venta"><span class="fa fa-ban fa-lg"></span> Cancelar</button>
                                    <button title="Eliminar Contrato" class="btn btn-danger eliminar-venta"><span class="fa fa-trash fa-lg"></span> Eliminar</button>
                                    
                                    <?php
                                    endif;
                                    ?>
                                    <?php
                                    if ($venta->estado == 1) ://Saldado 1
                                    ?>
        
                                    <?php
                                    endif;
                                    if ($venta->estado == 2) : // Cancelado 2
                                    ?>
                                    <button class="btn btn-success activar-venta"> <span class="fa fa-check"></span>Restablecer</button>
                                    <button title="Eliminar Contrato" class="btn btn-danger eliminar-venta"><span class="fa fa-trash fa-lg"></span> Eliminar</button>
                                    <?php
                                    endif;
                                    ?>
                                <?php
                                else : //Si es 3 es eliminado
                                ?>
                                    <button class="btn recuperar-venta"> <span class="fa fa-undo"></span> Recuperar</button>
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
