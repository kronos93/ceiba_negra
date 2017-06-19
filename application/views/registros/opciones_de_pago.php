<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
			<div class="col-xs-12">				
				<legend>
					<?= $title ?> 
					<?php if($this->ion_auth->in_group('administrador')): ?>
					<button  data-toggle="modal" data-target="#opPagoModal" data-title="Añadir opción de pago" data-btn-type="add" class="btn btn-success pull-right"><i class="fa fa-plus"></i></button> <div class="clearfix"></div>
					<?php endif ?>
				</legend>
				<table id="opciones-pago-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th data-visible="false">Id. pago</th>					
							<th>Enganche</th>
							<th>Abono (mensual)</th>          
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</main>
<?php if($this->ion_auth->in_group('administrador')): ?>
<div class="modal fade" id="opPagoModal" tabindex="-1" role="dialog" aria-labelledby="opPagoModal" aria-hidden="true">
 	<div class="modal-dialog" role="document">
	    <div class="modal-content">
			<form action="" id="frm-op-pago" method="post" autcomplete="off">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="model-title"></h4>	
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="enganche">Enganche*:</label>
                                <input type="text" class="form-control currency" id="enganche" name="enganche" placeholder="$10000" required />
                            </div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="abono">Abono (mensual)*: </label>
								<input type="text" class="form-control currency" id="abono" name="abono" placeholder="$100" required />
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