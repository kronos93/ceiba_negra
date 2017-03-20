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
                            <th>Lider</th>
                            <th>Descripción</th>
                            <th>Detalles</th>
							<th <?= ($this->ion_auth->in_group('administrador')) ? 'class="all"' : 'data-visible="false"' ?>>Opciones</th>	                
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</main>