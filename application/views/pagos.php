<main class="wrap-main">
    <div class="container-fluid container">
        <div class="row">
            <div class="col-xs-12">
                <table id="pagos-table" class="table responsive table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th data-visible="false">Id. Historial</th>
                            <th>Cliente</th>
                            <th>Lider</th>
                            <th>Concepto</th>
                            <th>Adeudo</th>
                            <th>Fecha de pago</th>
                            <th>Estado</th>
                            <th>Detalles</th>
                            <th class="all">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    foreach($pagos as $pago):
                ?>
                            <tr>
                                <td>
                                    <?= $pago->id_historial ?>
                                </td>
                                <td>
                                    <?= $pago->nombre_cliente ?>
                                </td>
                                <td>
                                    <?= $pago->nombre_lider ?>
                                </td>
                                <td>
                                    <?= $pago->concepto ?>
                                </td>
                                <td>$
                                    <?= number_format($pago->abono,2) ?>
                                </td>
                                <td>
                                    <?= $pago->fecha ?>
                                </td>
                                <td>
                                    <?= ($pago->estado == 0) ? 'Pendiente' : 'Pagado'?>
                                </td>
                                <td>
                                    <?php

                                        if ($pago->estado == 0) {
                                            if($pago->daysAccumulated > 0) {
                                                echo '<span class="label label-danger">Tiene un retraso en pago de:</span>' . $pago->diff->format('%y año(s) %m mes(es) y %d día(s).');

                                            } else if($pago->daysAccumulated == 0){
                                                echo '<span class="label label-warning">Hoy es día de pago.</span>';

                                            }else{
                                                echo '<span class="label label-success">Aun no es fecha de pago</span>. Faltan: '. $pago->diff->format('%y año(s) %m mes(es) y %d día(s).');

                                            }
                                        } else if ($pago->estado == 1){
                                            if($pago->daysAccumulated > 0) {
                                                echo 'Realizó el pago con un retraso de: ' . $pago->diff->format('%y año(s) %m mes(es) y %d día(s).');
                                            } else if($pago->daysAccumulated == 0) {
                                                echo 'Pagado en tiempo.';
                                            } else if($pago->daysAccumulated < 0) {
                                                echo 'Pagado por adelantado. Con: ' . $pago->diff->format('%y año(s) %m mes(es) y %d día(s).');
                                            }
                                            echo '<div>Pago: $' . number_format($pago->pago,2) .'</div>';
                                            echo '<div>Deposito en: ' . $pago->nombre .'</div>';
                                            echo '<div>Fecha: ' . $pago->fecha_pago .'</div>';
                                            echo '<div>Comisión: $' . number_format($pago->comision,2) .'</div>';
                                            echo '<div>Penalización: $' . number_format($pago->penalizacion,2) .'</div>';
                                            echo '<div>Total: $' . number_format($pago->total,2) .'</div>';
                                            echo '<div>Registrado por: ' . $pago->nombre_usuario .'</div>';
                                        }
                                ?>
                                </td>
                                <td>
                                    <?php
                                if($pago->estado_venta == 0 || $pago->estado_venta == 1){
                                    if ($pago->estado == 0) {
                                        echo '<button class="btn btn-success" data-toggle="modal" data-target="#pagoModal">Registrar pago</button> ' ;
                                    }else{
                                        if ($this->ion_auth->in_group('administrador')) {
                                            echo '<button class="btn btn-danger removerPago">Remover pago</button> ';
                                        }
                                        if($pago->comision == 0 ) {
                                            echo '<button class="btn btn-warning" data-toggle="modal" data-target="#pagoComisionModal">Registrar comisión</button> ';
                                        }
                                    }
                                }
                                ?>
                                </td>
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
<div class="modal fade" id="pagoModal" tabindex="-1" role="dialog" aria-labelledby="modalPago">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="" id="frm-pago">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Realizar un pago</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-6">
                                <label class="">Fecha:<?php if ($this->ion_auth->in_group('administrador')) : ?> <span class="btn btn-warning" id="btn-mod-date">Modificar limite de fecha</span><?php endif; ?></label>
                                <input type="text" id="fecha_pago" name="fecha_pago" data-min-date="" class="form-control datepicker" readonly/>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>Monto:</label>
                                <input type="text" id="pago" name="pago" class="form-control currency" readonly/>
                            </div>
                            <div class="form-group col-xs-12 col-sm-12">
                                <label class="" for="manzana">Seleccione el ingreso</label>
                                <select name="id_ingreso" id="id_ingreso" class="form-control">
                                    <?php foreach ($ingresos as $ingreso) : ?>
                                    <option value="<?= $ingreso->id_opcion_ingreso?>">
                                        <?= $ingreso->nombre?>
                                            <?= !empty($ingreso->cuenta) ? ' - '.$ingreso->cuenta : ''; ?>
                                    </option>
                                    <?php endforeach?>
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-sm-12" id="cnt-folio" style="display:none;">
                                <label class="" for="folio">Folio bancario</label>
                                <input type="text" class="form-control" name="folio" id="folio" />
                            </div>
                            <div class="clearfix"></div>
                            <legend>Comisión</legend>
                            <div class="form-group col-xs-12 col-sm-12">
                                <label>Lider:</label>
                                <select name="id_lider" id="id_lider" class="form-control">
                                    <?php foreach($lideres as $lider): ?>
                                    <option value="<?= $lider->id ?>">
                                        <?= $lider->first_name . ' ' . $lider->last_name ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-sm-3">
                                <label>Comision:</label>
                                <input type="text" id="comision" name="comision" class="form-control currency" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-3">
                                <label>% comisión:</label>
                                <input type="number" min="1.00" max="100.00" step="0.01" id="porcentaje_comision" name="porcentaje_comision" class="form-control" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label class="" for="lider" aria-required="true">Contemplar comisión</label>
                                <div class="radiobutton-custom">
                                    <input id="comisionTrue" type="radio" name="confirm_comision" value="true">
                                    <label for="comisionTrue">Sí:</label>
                                    <input id="comisionFalse" type="radio" name="confirm_comision" value="false" checked="checked">
                                    <label for="comisionFalse">No:</label>
                                </div>
                            </div>
                            <div id="cnt-estado-comision" style="display:none;">
                                <legend>Estado de la comisión</legend>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label>Pagado:</label>
                                    <input type="text" id="pagado" name="" class="form-control currency" readonly/>
                                </div>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label>Pagado + Monto a pagar:</label>
                                    <input type="text" id="virtual" name="" class="form-control currency" readonly/>
                                </div>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label>Limite:</label>
                                    <input type="text" id="limite" name="" class="form-control currency" readonly/>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <legend>Penalización</legend>
                            <div class="form-group col-xs-12 col-sm-2">
                                <label>Días:</label>
                                <input type="text" id="daysAccumulated" name="daysAccumulated" class="form-control" readonly/>
                            </div>
                            <div class="form-group col-xs-12 col-sm-3">
                                <label>Penalización:</label>
                                <input type="text" id="penalizacion" name="penalizacion" class="form-control currency" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-2">
                                <label>%</label>
                                <input type="number" step="1" min="1" max="100" id="porcentaje_penalizacion" name="porcentaje_penalizacion" class="form-control" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-5">
                                <label class="required" for="lider" aria-required="true">Contemplar Penalización</label>
                                <div>
                                    <div class="radiobutton-custom">
                                        <input id="penalizacionTrue" type="radio" name="confirm_penalizacion" value="true">
                                        <label for="penalizacionTrue">Sí:</label>
                                        <input id="penalizacionFalse" type="radio" name="confirm_penalizacion" value="false" checked="checked">
                                        <label for="penalizacionFalse">No:</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <input type="submit" class="btn btn-primary" value="Guardar">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="pagoComisionModal" tabindex="-1" role="dialog" aria-labelledby="modalPagoComision">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="" id="frm-pago-comision">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Realizar un pago de comision</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-12">
                                <label>Lider:</label>
                                <select name="id_lider" id="id_lider2" class="form-control">
                                    <?php foreach($lideres as $lider): ?>
                                    <option value="<?= $lider->id ?>">
                                        <?= $lider->first_name . ' ' . $lider->last_name ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>Monto:</label>
                                <input type="text" id="pago2" name="pago" class="form-control currency" readonly/>
                            </div>
                            <div class="form-group col-xs-12 col-sm-3">
                                <label>Comision:</label>
                                <input type="text" id="comision2" name="comision" class="form-control currency" required/>
                            </div>
                            <div class="form-group col-xs-12 col-sm-3">
                                <label>% comisión:</label>
                                <input type="number" id="porcentaje_comision2" name="porcentaje_comision" min="1.00" max="100.00" step="0.01" class="form-control" required/>
                            </div>
                            <legend>Estado de la comisión</legend>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>Pagado:</label>
                                <input type="text" id="pagado2" name="" class="form-control currency" readonly/>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>Virtual:</label>
                                <input type="text" id="virtual2" name="" class="form-control currency" readonly/>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>Limite:</label>
                                <input type="text" id="limite2" name="" class="form-control currency" readonly/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <input type="submit" class="btn btn-primary" value="Guardar">
                </div>
            </form>
        </div>
    </div>
</div>
