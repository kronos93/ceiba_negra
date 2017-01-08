<main class="wrap-main">
	<!-- <textarea id="contrato"></textarea> -->
	<div class="container-fluid container">
		<div class="row">
			<div class="col-xs-12">
				<form id="example-basic">
					<h3>Datos del Cliente:</h3>
					<div>
						<div class="container-fluid">
							<div class="row">
								<div class="form-group col-xs-12  col-sm-offset-8 col-sm-4">
									<label class="pull-right" for="clientes_autocomplete">Buscar Cliente</label>
									<div class="clearfix"></div>
									<div class="input-group">
										<input id="clientes_autocomplete" type="text" class="form-control" name=""/>
										<div class="input-group-addon"><span class="fa fa-search"></span></div>
									</div>
								</div>
								<legend>Datos Personales</legend>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="nombre">Nombre(s):</label>
									<input type="text" class="form-control required" name="nombre" id="nombre" placeholder="Nombre"/>
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="apellidos">Apellidos</label>
									<input type="text" class="form-control required" name="apellidos" id="apellidos" placeholder="Apellido Apellido"/>
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="correo">Correo electrónico</label>
									<input type="text" class="form-control required email" name="correo" id="correo" placeholder="alguien@dominio.com"  />
								</div>
								<legend>Domicilio</legend>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="calle">Calle</label>
									<input type="text" class="form-control required" name="calle" id="calle" placeholder="Av. siempre viva" />
								</div>
								<div class="form-group col-xs-12 col-sm-3 col-lg-2">
									<label class="required" for="no_ext">No. Exterior:</label>
									<input type="text" class="form-control required" name="no_ext" id="no_ext" placeholder="Lt. 8"  />
								</div>
								<div class="form-group col-xs-12 col-sm-3 col-lg-2">
									<label class="required" for="no_int">No. Interior:</label>
									<input type="text" class="form-control required" name="no_int" id="no_int" placeholder="S/N"/>
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
									<label class="required" for="pais">Ciudad</label>
									<input type="text" class="form-control required" name="ciudad" id="ciudad" placeholder="Cancún"  />
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
									<label class="required" for="manzana">Fecha del contrato:</label>
									<div class="input-group">
										<input type="text" class="form-control" id="fecha_contrato" placeholder="dd-mm-yyyy" value="<?= $fecha ?>" name=""/>
										<span class="input-group-addon" id="sizing-addon1">
											<i class="fa fa-calendar"></i>
										</span>
									</div>
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="manzana">Ciudad de expedición</label>
									<input type="text" class="form-control" name="manzana" placeholder="Cancún" required />
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="manzana">Nombre completo de testigo 1</label>
									<input type="text" class="form-control" name="manzana" placeholder="Nombre Apellido Apellido" required />
								</div>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="manzana">Nombre completo de testigo 1</label>
									<input type="text" class="form-control" name="manzana" placeholder="Nombre Apellido Apellido"  required />
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
								<legend>Periodos de abono</legend>
								<div class="form-group col-xs-12 col-sm-6">
									<label class="required" for="manzana">Forma de pago:</label>
									<select name="#" id="" class="form-control">
										<option selected value="15-f">Quincenal (15 a fin de mes)</option>
										<option value="1-16">Quincenal (01 al 16 de mes)</option>
										<option value="15-1">Quincenal (15 al 01 del siguiente mes)</option>
									</select>
								</div>
								<legend>Lider de manzana</legend>
								<div class="form-group col-xs-12  col-sm-8 col-sm-4">
									<label class="" for="lider">Nombre</label>
									<div class="clearfix"></div>
									<div class="input-group">
										<input id="" type="text" class="form-control" name=""/>
										<div class="input-group-addon"><span class="fa fa-search"></span></div>
									</div>
								</div>
								<legend>Penalizaciones</legend>
								<div class="form-group col-xs-12 col-sm-4">
									<label class="required" for="manzana">Porcentaje de penalización:</label>
									<div class="input-group">
										<input type="number" class="form-control" id="penalizacion" placeholder="1" value="1" name="penalizacion" min="0" max="100" step="1"/>
										<span class="input-group-addon" id="sizing-addon1">%</span>
									</div>
								</div>
								<div class="form-group col-xs-12 col-sm-4">
									<label class="required" for="manzana">Máximo de retrasos permitidos en pagos:</label>
									<input type="number" class="form-control" id="penalizacion" placeholder="1" value="3" name="penalizacion" min="0" max="100" step="1"/>	
								</div>
							</div>
						</div>
					</div>
					<h3>Formas de pago:</h3>
					<div>
						<div class="container-fluid">
							<div class="row">
								<legend>Pagos</legend>
								
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
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>