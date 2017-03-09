<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
			<div class="col-xs-12">
				<legend>
                    Huertos
                    <?php if($this->ion_auth->in_group('administrador')): ?>					
                    <button  data-toggle="modal" data-target="#huertoModal" data-title="Añadir huerto" data-btn-type="add" class="btn btn-success pull-right"><i class="fa fa-plus"></i></button> <div class="clearfix"></div>
                    <?php endif ?>
                </legend>
				<table id="huertos-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
					<thead>
						<tr>
                            <th data-visible="false">Id. Huerto</th>
							<th>Manzana</th>
							<th>Huerto</th>
							<th>Superficie</th>	
                            <th>Valor del m<sup>^2</sup></th>
                            <th>Precio por m<sup>^2</sup></th>									
                            <th>Enganche</th> 
                            <th>Abono</th> 
							<th>Estado</th>
							<th>Col. Norte</th>
                            <th>Col. Noreste</th>
                            <th>Col. Este</th> 
                            <th>Col. Sureste</th>
							<th>Col. Sur</th>
							<th>Col. Suroeste</th>
							<th>Col. Oeste</th>
                            <th>Col. Noroeste</th>
							<th <?= ($this->ion_auth->in_group('administrador')) ? 'data-visible="true"' : 'data-visible="false"' ?>>Opciones</th>            
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</main>
<?php if($this->ion_auth->in_group('administrador')): ?>
<div class="modal fade" id="huertoModal" tabindex="-1" role="dialog" aria-labelledby="huertoModal" aria-hidden="true">
 	<div class="modal-dialog" role="document">
	    <div class="modal-content">
			<form action="" method="post" id="frm-huerto" autocomplete="off">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="model-title"></h4>	
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<div class="form-group col-xs-12 col-sm-4">
								<label class="required" for="manzana">No. de manzana:</label>
								<div class="input-group">
									<div class="input-group-addon">Mz.</div>
									<select class="form-control" name="id_manzana" id="id_manzana" required >
										<?php foreach ($manzanas as $manzana) { ?>
                                        <option value="<?= $manzana->id_manzana ?>"><?= $manzana->manzana ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-sm-4">
                                <label class="required" for="lote">No. huerto:</label>
								<div class="input-group">
									<div class="input-group-addon">Ht.</div>
								    <input type="text" class="form-control" name="huerto" id="huerto" pattern="[0-9]{1,3}[A-Za-z]{0,1}" placeholder="1" required/>
								</div>
                            </div>
							<div class="form-group col-xs-12 col-sm-4">
                                <label class="required" for="superficie">Superficie:</label>
                                <input type="text" class="form-control autoNumeric superficie multiplicar" name="superficie" id="superficie" placeholder="1" required/>
                            </div> 
                            <div class="form-group col-xs-12 col-sm-4">
                                <label class="required" for="precio_x_m2">Precio por m<sup>2</sup>:</label>
							    <input type="text" class="form-control autoNumeric currency multiplicar" name="precio_x_m2" id="precio_x_m2" placeholder="$ 352.00" value="" required/>							
                            </div>
                            <div class="form-group col-xs-12 col-sm-4">
                                <label class="required" for="">Precio</label>
							    <input type="text" class="form-control autoNumeric currency" id="precio" name="precio" placeholder="0" value="$ 0.00" readonly required/>							
                            </div>
							<div class="form-group col-xs-12 col-sm-12">
								<label class="required" for="precio">Precio:</label>
									<select class="form-control" name="id_precio" id="id_precio" required >
										<?php foreach ($precios as $precio) { ?>
										<option value="<?= $precio->id_precio ?>">Enganche: $<?= number_format($precio->enganche,2) ?> - Abono: $<?= number_format($precio->abono,2) ?></option>
                                        <?php } ?>
                                    </select>
                            </div>                         
                        </div>
                        <fieldset class="form-group row">   
                            <legend>Colindancias:</legend>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_norte">Colindancia al Norte:</label>
                                <input type="text" class="form-control" name="col_norte" id="col_norte" placeholder="46.012 Mts. con Mz. 26 huerto. 15 más 35.00 Mts. con Mz. 26 huerto. 10" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_norte">Colindancia al Noreste:</label>
                                <input type="text" class="form-control" name="col_noreste" id="col_noreste" placeholder="46.012 Mts. con Mz. 26 huerto. 15 más 35.00 Mts. con Mz. 26 huerto. 10" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_este">Colindancia al Este:</label>
                                <input type="text" class="form-control" name="col_este" id="col_este" placeholder="12.50 Mts. con Mz. 27 huerto. 12" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_sur">Colindancia al Sureste:</label>
                                <input type="text" class="form-control" name="col_sureste" id="col_sureste" placeholder="45.468 Mts. con Mz. 26 huerto. 13 más 35.00 Mts. con Mz. 26 huerto. 12" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_sur">Colindancia al Sur:</label>
                                <input type="text" class="form-control" name="col_sur" id="col_sur" placeholder="45.468 Mts. con Mz. 26 huerto. 13 más 35.00 Mts. con Mz. 26 huerto. 12" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_sur">Colindancia al Suroeste:</label>
                                <input type="text" class="form-control" name="col_suroeste" id="col_suroeste" placeholder="45.468 Mts. con Mz. 26 huerto. 13 más 35.00 Mts. con Mz. 26 huerto. 12" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_oeste">Colindancia al oeste:</label>
                                <input type="text" class="form-control" name="col_oeste" id="col_oeste" placeholder="12.50 Mts. con sendero La Ceiba" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_oeste">Colindancia al Noroeste:</label>
                                <input type="text" class="form-control" name="col_noroeste" id="col_noroeste" placeholder="12.50 Mts. con sendero La Ceiba" />
                            </div>
                        </fieldset>
                    </div>
                    <div class="container-icons" >
                        <i class="fa"></i>
                        <h4 class="message"></h4>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <div class="ajax-button">
                        <input type="submit" class="btn btn-success confirm" value="Guardar cambios"/>
                        <div class="loader-gif">
                            <div class="loader-gif-item"></div>
                            <div class="loader-gif-item"></div>
                            <div class="loader-gif-item"></div>
                        </div>
                    </div>
                </div>
            </form>         
        </div>
    </div>
</div>
<?php endif ?>