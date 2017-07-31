<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
			<div class="col-xs-12">				
				<legend>
					Reservas
				</legend>
				<table id="reservas-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th data-visible="false">Id. reserva</th>
                            <th class="all">Lider</th>
                            <th>Descripción</th>
                            <th>Detalles</th>
							<th>Expira</th>
							<th <?= ($this->ion_auth->in_group(['administrador','lider'])) ? 'class="all"' : 'data-visible="false"' ?>>Opciones</th>	                
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</main>


<!-- Modal -->
<div class="modal fade" id="modalEditarReserva" tabindex="-1" role="dialog" aria-labelledby="modalEditarReservaLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	  	<form action="" method="" id="frm-extender-reserva">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Extender fecha de reserva</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
							<input type="hidden" name="id_reserva" id="id_reserva" />
						<div class="form-group col-xs-12 col-sm-12">
							<span for="expira">Fecha de expiración de la reserva</span>
							<input type="text" class="form-control" name="expira" id="expira" />
						</div>
					</div>
				</div>				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="submit" class="btn btn-primary">Guardar cambios</button>
			</div>
		</form>
    </div>
  </div>
</div>