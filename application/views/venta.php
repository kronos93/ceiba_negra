<main class="wrap-main">
    <!-- <textarea id="contrato"></textarea> -->
    <div class="container-fluid container">
        <div class="row">
            <div class="col-xs-12">
                <form id="frm-venta">
                    <h3>Datos del Cliente:</h3>
                    <div>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="form-group col-xs-12  col-sm-offset-8 col-sm-4">
                                    <label class="pull-right" for="clientes_autocomplete">Buscar Cliente</label>
                                    <div class="clearfix"></div>
                                    <div class="input-group">
                                        <input id="clientes_autocomplete" type="text" class="form-control" name="" />
                                        <div class="input-group-addon"><span class="fa fa-search"></span></div>
                                    </div>
                                </div>
                                <legend>Datos personales</legend>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label class="required" for="first_name">Nombre(s):</label>
                                    <input type="text" class="form-control required" name="first_name" id="first_name" placeholder="Nombre" />
                                </div>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label class="required" for="last_name">Apellidos</label>
                                    <input type="text" class="form-control required" name="last_name" id="last_name" placeholder="Apellido Apellido" />
                                </div>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label class="required" for="email">Correo electrónico</label>
                                    <input type="text" class="form-control required email" name="email" id="email" placeholder="alguien@dominio.com" />
                                </div>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label class="" for="phone">Celular</label>
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="1234908978" />
                                </div>
                                <legend>Domicilio</legend>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label class="required" for="calle">Calle</label>
                                    <input type="text" class="form-control required" name="calle" id="calle" placeholder="Av. siempre viva" />
                                </div>
                                <div class="form-group col-xs-12 col-sm-3 col-lg-2">
                                    <label class="required" for="no_ext">No. Exterior:</label>
                                    <input type="text" class="form-control required" name="no_ext" id="no_ext" placeholder="Lt. 8" />
                                </div>
                                <div class="form-group col-xs-12 col-sm-3 col-lg-2">
                                    <label class="" for="no_int">No. Interior:</label>
                                    <input type="text" class="form-control" name="no_int" id="no_int" placeholder="S/N" />
                                </div>
                                <div class="form-group col-xs-12 col-sm-6 col-lg-2">
                                    <label class="required" for="colonia">Colonia:</label>
                                    <input type="text" class="form-control required" name="colonia" id="colonia" placeholder="Reg. 233" />
                                </div>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label class="required" for="municipio">Municipio:</label>
                                    <input type="text" class="form-control required" name="municipio" id="municipio" placeholder="Solidaridad" />
                                </div>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label class="required" for="estado">Estado:</label>
                                    <input type="text" class="form-control required" name="estado" id="estado" placeholder="Quintana Roo" />
                                </div>
                                <div class="form-group col-xs-12 col-sm-3">
                                    <label class="required" for="ciudad">Ciudad</label>
                                    <input type="text" class="form-control required" name="ciudad" id="ciudad" placeholder="Cancún" />
                                </div>
                                <div class="form-group col-xs-12 col-sm-3">
                                    <label class="required" for="cp">C.P</label>
                                    <input type="text" class="form-control required" name="cp" id="cp" placeholder="77510" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3>Datos de Venta</h3>
                    <div>
                        <div class="container-fluid">
                            <div class="row">
                                <legend>Contrato</legend>
                                <div class="form-group col-xs-12 col-sm-3">
                                    <label class="required" for="fecha_init">Fecha del contrato:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control required" id="fecha_init" placeholder="dd-mm-yyyy" value="<?= $fecha ?>" name="fecha_init" />
                                        <span class="input-group-addon" id="sizing-addon1">
											<i class="fa fa-calendar"></i>
										</span>
                                    </div>
                                </div>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label class="required" for="ciudad">Ciudad de expedición</label>
                                    <input type="text" class="form-control required" name="ciudad" placeholder="Cancún" required />
                                </div>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label class="required" for="testigo_1">Nombre completo de testigo 1</label>
                                    <input type="text" class="form-control required" id="testigo_1" name="testigo_1" placeholder="Nombre Apellido Apellido" required />
                                </div>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label class="required" for="testigo_2">Nombre completo de testigo 1</label>
                                    <input type="text" class="form-control required" id="testigo_2" name="testigo_2" placeholder="Nombre Apellido Apellido" required />
                                </div>
                                <legend>Precios</legend>
                                <div class="form-group col-xs-12 col-sm-4">
                                    <label class="required" for="precio">Precio del huerto</label>
                                    <input type="text" class="form-control required currency" name="precio" id="precio" value="" placeholder="" required />
                                </div>
                                <div class="form-group col-xs-12 col-sm-4">
                                    <label class="required" for="manzana">Enganche</label>
                                    <input type="text" class="form-control required currency" name="enganche" id="enganche" placeholder="" required />
                                </div>
                                <div class="form-group col-xs-12 col-sm-4">
                                    <label class="required" for="manzana">Abono</label>
                                    <input type="text" class="form-control required currency" name="abono" id="abono" placeholder="" required />
                                </div>
                                <legend>Periodos de abono</legend>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label class="required" for="manzana">Forma de pago:</label>
                                    <select name="#" id="" class="form-control">
                                        <option selected value="15-f">Quincenal (15 a fin de mes)</option>
                                        <option value="1-16">Quincenal (01 al 16 de mes)</option>
                                        <option value="15-1">Quincenal (15 al 01 del siguiente mes)</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <legend>Lider de manzana</legend>
                                    </div>
                                </div>
                                <div class="form-group col-xs-12  col-sm-8 col-sm-4">
                                    <label class="required" for="lider">Nombre</label>
                                    <div class="clearfix"></div>
                                    <div class="input-group">
                                        <input id="lideres_autocomplete" type="text" class="form-control required" name="lider" />
                                        <div class="input-group-addon"><span class="fa fa-search"></span></div>
                                    </div>
                                </div>
                                <div class="form-group col-xs-12  col-sm-8 col-sm-4">
                                    <label class="required" for="lider">Contemplar comisión</label>
                                    <div class="radiobutton-custom">
                                        <input id="confirmyes" type="radio" name="confirm" value="yes" checked="checked">
                                        <label for="confirmyes">Sí:</label>
                                        <input id="confirmno" type="radio" name="confirm" value="no">
                                        <label for="confirmno">No:</label>
                                        <input type="hidden" name="JOBCoG4S" value="CuUQ0HlbcOEhokFiY6Bz">
                                        <input type="hidden" name="id" value="6">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <legend>Penalizaciones</legend>
                                    </div>
                                </div>
                                <div class="form-group col-xs-12 col-sm-4">
                                    <label class="required" for="manzana">Porcentaje de penalización:</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control required" id="porcentaje_penalizacion" placeholder="1" value="1" name="porcentaje_penalizacion" min="0" max="100" step="1" />
                                        <span class="input-group-addon" id="sizing-addon1">%</span>
                                    </div>
                                </div>
                                <div class="form-group col-xs-12 col-sm-4">
                                    <label class="required" for="maximo_retrasos">Máximo de retrasos permitidos en pagos:</label>
                                    <input type="number" class="form-control required" id="maximo_retrasos_permitidos" placeholder="1" value="3" name="maximo_retrasos_permitidos" min="0" max="100" step="1" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3>Contrato</h3>
                    <div>
                        <textarea name="contrato_html" id="contrato_html"></textarea>
                    </div>
                    <h3>Formas de pago:</h3>
                    <div>
                        <div class="container-fluid">
                            <div class="row">
                                <legend>Pago</legend>
                                <div class="form-group col-xs-12 col-sm-3">
                                    <label class="required" for="manzana">Seleccione el ingreso</label>
                                    <select name="#" id="" class="form-control">
                                        <?php foreach ($ingresos as $ingreso) : ?>
                                        <option value="<?= $ingreso->id_ingreso?>">
                                            <?= $ingreso->nombre?>
                                                <?= !empty($ingreso->cuenta) ? ' - '.$ingreso->cuenta : ''; ?>
                                        </option>
                                        <?php endforeach?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
