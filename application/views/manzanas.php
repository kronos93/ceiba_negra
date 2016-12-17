<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
			<div class="col-xs-12">
				<legend>Manzanas <button  data-toggle="modal" data-target="#add-manzana" class="btn btn-success pull-right"><i class="fa fa-plus"></i></button> <div class="clearfix"></div></legend>
				<table id="manzanas" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Manzana</th>
							<th>calle</th>
							<th>Estado</th>	
							<th>Opciones</th>	                
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</main>
<!-- Modal para insertar -->
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
									<input type="number" class="form-control" id="manzana" name="manzana" placeholder="100" step="1" min="1" max="1000" required />
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
					</div>
					<div class="container-icons" >
				      	<i></i>
					    <h4 class="message">Hubo algun error</h4>
					</div>
					<!-- <div class="container-icons success" >
						<i class="fa fa-check-circle-o fa-4x"></i>
					    <h4 class="message">Algo salio bien :v</h4>
					</div>
					<div class="container-icons warning" >
						<i class="fa fa-exclamation-circle fa-4x"></i>
						<h4 class="message">Un mensaje :v</h4>
					</div> -->
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