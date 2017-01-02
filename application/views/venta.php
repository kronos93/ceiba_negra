<main class="wrap-main">
	<!-- <textarea id="contrato"></textarea> -->
	<div class="container-fluid container">
		<div class="row">
			<div class="col-xs-12">
				
				<div id="accordion" role="tablist" aria-multiselectable="true">
					<div class="card">
					    <div class="card-header" role="tab" id="headingOne">
					      	<h5 class="mb-0">
					        	<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					          	Resumen de Venta
					        	</a>
					     	 </h5>
					    </div>
					    <div id="collapseOne" class="collapse in" role="tabpanel" aria-labelledby="headingOne">
					      <div class="card-block">
					        	<div class="sale-resume">
									<div class="row">
										<div class="col-xs-12">
											<h4>Resumen de Venta</h4>
											<hr class="separator">
											<table>
												<thead>
													<th>Manzana No: </th>
													<th>Huerto Numero:</th>
													<th>Precio $</th>
												</thead>
												<tbody>
													<tr>
														<td>Manzana 23</td>
														<td>23,45,67</td>
														<td>$45,000.00</td>
													</tr>
													<tr>
														<td>Manzana 23</td>
														<td>23,45,67</td>
														<td>$45,000.00</td>
													</tr>
												</tbody>
												<tfoot>
													<tr>
												      <th id="total" colspan="2">Total :</th>
												      <td>$45,000.00</td>
												    </tr>
												</tfoot>
											</table>
										</div>
									</div>
								</div>
					      </div>
					    </div>
					</div>
				</div>
				<form id="example-basic">
					<h3>Datos del Cliente:</h3>
					<div>
						<div class="container-fluid">
							<div class="row">
								<div class="form-group col-xs-12  col-sm-offset-8 col-sm-4">
									<label class="required pull-right" for="manzana">Buscar Cliente</label>
									<div class="clearfix"></div>
									<div class="input-group">
										<input id="clientes_autocomplete" type="text" class="form-control" name=""/>
										<div class="input-group-addon"><span class="fa fa-search"></span></div>
									</div>
								</div>
								<legend>Datos Personales</legend>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="nombre">Nombre(s):</label>
									<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Alguien" required/>
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="apellidos">Apellidos</label>
									<input type="text" class="form-control" name="apellidos" id="apellidos" values="Alguien Alguien" required />
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="correo">Correo Electrónico</label>
									<input type="text" class="form-control" name="correo" id="correo" placeholder="alguien@dominio.com"  />
								</div>
								<legend>Domicilio</legend>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="calle">Calle</label>
									<input type="text" class="form-control" name="calle" id="calle" placeholder="" />
								</div>
								<div class="form-group col-xs-12 col-sm-3 col-lg-2">
									<label class="required" for="no_ext">No. Exterior:</label>
									<input type="text" class="form-control" name="no_ext" id="no_ext" placeholder=""  />
								</div>
								<div class="form-group col-xs-12 col-sm-3 col-lg-2">
									<label class="required" for="no_int">No. Interior:</label>
									<input type="text" class="form-control" name="no_int" id="no_int" placeholder=""/>
								</div>
								<div class="form-group col-xs-12 col-sm-6 col-lg-2">
									<label class="required" for="colonia">Colonia:</label>
									<input type="text" class="form-control" name="colonia" id="colonia" placeholder="" />
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="municipio">Municipio</label>
									<input type="text" class="form-control" name="municipio" id="municipio" placeholder="" />
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="estado">Estado</label>
									<input type="text" class="form-control" name="estado" id="estado" placeholder="" />
								</div>
								<div class="form-group col-xs-12 col-sm-3">
									<label class="required" for="pais">País</label>
									<input type="text" class="form-control" name="pais" id="pais" placeholder=""  />
								</div>
								<div class="form-group col-xs-12 col-sm-3">
									<label class="required" for="cp">C.P</label>
									<input type="text" class="form-control" name="cp" id="cp" placeholder="" />
								</div>
							</div>
						</div>
					</div>
					<h3>Datos de Venta</h3>
					<div>
						<div class="container-fluid">
							<div class="row">
								<!-- <legend>Fechas</legend> -->
								<div class="form-group col-xs-12 col-sm-3">
									<label class="required" for="manzana">Fecha:</label>
									<input type="date" class="form-control" name=""/>
								</div>
								<legend>Precios</legend>
								<div class="form-group col-xs-12 col-sm-4">
									<label class="required" for="manzana">Precio del Huerto</label>
									<input type="text" class="form-control currency" name="precio" id="precio" class="" value="" placeholder="" required />
								</div>
								<div class="form-group col-xs-12 col-sm-4">
									<label class="required" for="manzana">Enganche</label>
									<input type="text" class="form-control currency" name="enganche" id="enganche" class="" placeholder="" required />
								</div>
								<div class="form-group col-xs-12 col-sm-4">
									<label class="required" for="manzana">Abono</label>
									<input type="text" class="form-control currency" name="abono" id="abono" class="" placeholder="" required />
								</div>
								<legend>Contrato</legend>
								<div class="form-group col-xs-12 col-sm-3">
									<label class="required" for="manzana">Fecha del primer pago:</label>
									<input type="date" class="form-control" name=""/>
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="manzana">Lugar de Expedicion</label>
									<input type="text" class="form-control" name="manzana" placeholder=""  min="1" max="1000" required />
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="manzana">Testigo 1</label>
									<input type="text" class="form-control" name="manzana" placeholder=""  min="1" max="1000" required />
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="manzana">Testigo 2</label>
									<input type="text" class="form-control" name="manzana" placeholder=""  min="1" max="1000" required />
								</div>
							</div>
						</div>
					</div>
					<h3>Formas de pago:</h3>
					<div>
						<div class="container-fluid">
							<div class="row">
								<legend>Pagos</legend>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="manzana">Forma de pago:</label>
									<select name="#" id="" class="form-control">
										<option value="">Hola</option>
										<option value="">Hola</option>
										<option value="">Hola</option>
										<option value="">Hola</option>
									</select>
								</div>
								<div class="form-group col-xs-12 col-sm-3">
									<label class="required" for="manzana">Tipo de pago:</label>
									<select name="#" id="" class="form-control">
										<option value="">Hola</option>
										<option value="">Hola</option>
										<option value="">Hola</option>
										<option value="">Hola</option>
									</select>
								</div>
								<div class="form-group col-xs-12 col-sm-3">
									<label class="required" for="manzana">Selección de Banco</label>
									<select name="#" id="" class="form-control">
										<option value="">Hola</option>
										<option value="">Hola</option>
										<option value="">Hola</option>
										<option value="">Hola</option>
									</select>
								</div>
								<legend>Lider de Manzana</legend>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="manzana">Nombre:</label>
									<select name="#" id="" class="form-control">
										<option value="">Hola</option>
										<option value="">Hola</option>
										<option value="">Hola</option>
										<option value="">Hola</option>
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