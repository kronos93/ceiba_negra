<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
			<div class="col-xs-12">				
				<legend>
					Opciones de ingreso 
					<?php if($this->ion_auth->in_group('administrador')): ?>
					<button  data-toggle="modal" data-target="#opcionDeIngresoModal" data-title="Añadir opción de ingreso" data-btn-type="add" class="btn btn-success pull-right"><i class="fa fa-plus"></i></button> <div class="clearfix"></div>
					<?php endif ?>
				</legend>
				<ul class="nav nav-tabs">
					 <li role="presentation" class="active"><a href="#">Total: <span class="badge" id="total-op-ingresos">0</span></a></li>
				</ul>
				<table id="opciones-de-ingreso-table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th data-visible="false">Id. opción de ingreso</th>					
							<th class="all">Nombre</th>
							<th>Cuenta</th>
							<th>Tarjeta</th>		
							<th>Ingreso</th>					
							<th <?= ($this->ion_auth->in_group('administrador')) ? 'class="all"' : 'data-visible="false"' ?>>Opciones</th>
						</tr>
					</thead>	
					<tbody></tbody>				
				</table>
			</div>
		</div>
	</div>
	<div id="total-op-pago"></div>
</main>
<?php if($this->ion_auth->in_group('administrador')): ?>
<div class="modal fade" id="opcionDeIngresoModal" tabindex="-1" role="dialog" aria-labelledby="opcionDeIngresoModal" aria-hidden="true">
 	<div class="modal-dialog" role="document">
	    <div class="modal-content">
			<form action="" id="frm-opcion-ingreso" method="post" autcomplete="off">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="model-title"></h4>	
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="nombre">Nombre:</label>	
								<input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre de banco" required />	
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="cuenta">Cuenta:</label>
								<input type="text" class="form-control cuenta-banco" id="cuenta" name="cuenta" min="1" placeholder="000-0000-0000" required />
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="tarjeta">Tarjeta:</label>
								<input type="text" class="form-control tarjeta" id="tarjeta" min="1" name="tarjeta" placeholder="0000-0000-0000-0000" required />
							</div>													  			
						</div>						
					</div>	
					<div class="container-icons" >
				      	<i class="fa"></i>
					    <h4 class="message"></h4>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<div class="ajax-button">
						<input type="submit" class="btn btn-success confirm" id="submitFrm" value="Guardar cambios"/>
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