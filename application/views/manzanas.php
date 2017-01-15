<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
			<div class="col-xs-12">
				<legend>Manzanas <button  data-toggle="modal" data-target="#add-manzana" class="btn btn-success pull-right"><i class="fa fa-plus"></i></button> <div class="clearfix"></div></legend>
				<table id="manzanas-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Id. Manzana</th>							
							<th>Manzana</th>
							<th>Calle</th>
							<th>Superficie</th>
							<th>Estado</th>
							<th>Col. Norte</th>
                            <th>Col. Noreste</th>
                            <th>Col. Este</th> 
                            <th>Col. Sureste</th>
							<th>Col. Sur</th>
							<th>Col. Suroeste</th>
							<th>Col. Oeste</th>
                            <th>Col. Noroeste</th>
							<th class="all">Opciones</th>	                
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</main>
<!-- Modal para insertar -->
<!-- Los inputs no llevan ID -->
<div class="modal fade" id="add-manzana" tabindex="-1" role="dialog" aria-labelledby="modalAddManzana" aria-hidden="true">
 	<div class="modal-dialog" role="document">
	    <div class="modal-content">
			<form action="" method="post" id="frm-add-manzanas" autocomplete="off">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="model-title">Añadir manzana</h4>	
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="manzana">No. de manzana:</label>
								<div class="input-group">
									<div class="input-group-addon">Mz.</div>
									<input type="number" class="form-control" name="manzana" placeholder="100" step="1" min="1" max="1000" required />
								</div>
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="calle">Calle:</label>
								<input type="text" class="form-control"name="calle" placeholder="Sendero oculto" required />
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="superficie">superficie:</label>
								<div class="input-group">
									<input type="text" class="form-control superficie" name="superficie" placeholder="10,0000" required />
									<div class="input-group-addon">Mt<sup>2</sup>.</div>
								</div>
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="disponibilidad">Disponibilidad:</label>
								<select class="form-control" name="disponibilidad" required >
									<option value="1">Disponible para su venta</option>
									<option value="0">No disponible para su venta</option>
								</select>
							</div>				  			
						</div>
						<fieldset class="form-group row">	
							<legend>Colindancias:</legend>
							<div class="form-group col-xs-12 col-sm-6">
                                <label for="col_norte">Colindancia al Norte:</label>
                                <input type="text" class="form-control" name="col_norte" placeholder="46.012 Mts. con Mz. 26 huerto. 15 más 35.00 Mts. con Mz. 26 huerto. 10" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_noreste">Colindancia al Noreste:</label>
                                <input type="text" class="form-control" name="col_noreste" placeholder="46.012 Mts. con Mz. 26 huerto. 15 más 35.00 Mts. con Mz. 26 huerto. 10" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_este">Colindancia al Este:</label>
                                <input type="text" class="form-control" name="col_este" placeholder="12.50 Mts. con Mz. 27 huerto. 12" />
                            </div>                            
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_sureste">Colindancia al Sureste:</label>
                                <input type="text" class="form-control" name="col_sureste" placeholder="45.468 Mts. con Mz. 26 huerto. 13 más 35.00 Mts. con Mz. 26 huerto. 12" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_sur">Colindancia al Sur:</label>
                                <input type="text" class="form-control" name="col_sur" placeholder="45.468 Mts. con Mz. 26 huerto. 13 más 35.00 Mts. con Mz. 26 huerto. 12" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_suroeste">Colindancia al Suroeste:</label>
                                <input type="text" class="form-control" name="col_suroeste" placeholder="45.468 Mts. con Mz. 26 huerto. 13 más 35.00 Mts. con Mz. 26 huerto. 12" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_oeste">Colindancia al Oeste:</label>
                                <input type="text" class="form-control" name="col_oeste" placeholder="12.50 Mts. con sendero La Ceiba" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="col_noroeste">Colindancia al Noreste:</label>
                                <input type="text" class="form-control" name="col_noroeste" placeholder="12.50 Mts. con sendero La Ceiba" />
                            </div>
						</fieldset>
					</div>
					<div class="container-icons" >
				      	<i></i>
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
<!-- Modal para editar -->
<!-- Los inputs si llevan ID nombrados como en la base de datos-->
<div class="modal fade" id="edit-manzana" tabindex="-1" role="dialog" aria-labelledby="modalEditManzana" aria-hidden="true">
 	<div class="modal-dialog" role="document">
	    <div class="modal-content">
			<form action="" method="post" id="frm-edit-manzanas" autcomplete="off">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="model-title">Editar manzana</h4>	
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="manzana">No. de manzana:</label>
								<div class="input-group">
									<div class="input-group-addon">Mz.</div>
									<input type="number" class="form-control" id="manzana" name="manzana" readonly="readonly" required />
								</div>
								</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="calle">Calle:</label>
								<input type="text" class="form-control" id="calle" name="calle" placeholder="Sendero oculto" required />
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="disponibilidad">Disponibilidad:</label>
								<select class="form-control" name="disponibilidad" id="disponibilidad" required >
									<option value="1">Disponible para su venta</option>
									<option value="0">No disponible para su venta</option>
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
				      	<i></i>
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