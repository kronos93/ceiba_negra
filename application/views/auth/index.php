<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
			<div class="col-xs-12">
				<legend><?php echo lang('index_heading');?>
					<button  data-toggle="modal" data-target="#add-user" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Nuevo Usuario</button>

					<button  data-toggle="modal" data-target="#add-group" class="btn btn-info pull-right"><i class="fa fa-plus"></i> Nuevo Grupo</button>

					<button  data-toggle="modal" data-target="#edit-user" class="btn btn-info pull-right"><i class="fa fa-plus"></i> editar usuario</button>
					<div class="clearfix"></div>
				</legend>
			</div>
			<div class="col-xs-12">
				<!-- <p><?php echo lang('index_subheading');?></p> -->
				<div id="infoMessage"><?php echo $message;?></div>
				<table id="tableUsers" class="table table-striped table-bordered responsive">
					<thead>
						<tr>
							<th><?php echo lang('index_fname_th');?></th>
							<th><?php echo lang('index_lname_th');?></th>
							<th><?php echo lang('index_email_th');?></th>
							<th><?php echo lang('index_groups_th');?></th>
							<th><?php echo lang('index_status_th');?></th>
							<th><?php echo lang('index_action_th');?></th>
						</tr>
					</thead>
					<?php foreach ($users as $user):?>
						<tbody>
							<tr>
					            <td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
					            <td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
					            <td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
								<td>
									<?php foreach ($user->groups as $group):?>
										<?php echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?><br />
					                <?php endforeach?>
								</td>
								<td><?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, lang('index_active_link')) : anchor("auth/activate/". $user->id, lang('index_inactive_link'));?></td>
								<td><?php echo anchor("auth/edit_user/".$user->id, 'Edit') ;?></td>
							</tr>
						</tbody>
					<?php endforeach;?>
				</table>
			</div>
		</div>
	</div>
		
	<p><?php echo anchor('auth/create_user', lang('index_create_user_link'))?> | <?php echo anchor('auth/create_group', lang('index_create_group_link'))?></p>
</main>
<div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-labelledby="modalAddUser" aria-hidden="true">
 	<div class="modal-dialog" role="document">
	    <div class="modal-content">
			<form action="" method="post" id="frm-add-manzanas" autocomplete="off">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="model-title">Añadir nuevo usuario</h4>	
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="calle">Nombre:</label>
								<input type="text" class="form-control" id="name" name="name" required />
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="calle">Apellidos:</label>
								<input type="text" class="form-control" id="name" name="name" required />
							</div>
							<div class="form-group col-xs-12 col-sm-5">
								<label class="required" for="calle">Compañia:</label>
								<input type="text" class="form-control" id="name" name="name" required />
							</div>
							<div class="form-group col-xs-12 col-sm-7">
								<label class="required" for="manzana">Telefono:</label>
								<div class="input-group">
									<div class="input-group-addon"><i class="fa fa-phone"></i></div>
									<input data-mask="(000)-000-00-00" type="text" class="form-control" id="manzana" name="manzana" required />
								</div>
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="calle">Contraseña:</label>
								<input type="password" class="form-control" id="name" name="name" required />
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="calle">Confirmar contraseña:</label>
								<input type="password" class="form-control" id="name" name="name" required />
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
<div class="modal fade" id="edit-user" tabindex="-1" role="dialog" aria-labelledby="modalEditUser" aria-hidden="true">
 	<div class="modal-dialog" role="document">
	    <div class="modal-content">
			<form action="" method="post" id="frm-add-manzanas" autocomplete="off">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="model-title">Editar usuario</h4>	
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="calle">Nombre:</label>
								<input type="text" class="form-control" id="name" name="name" required />
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="calle">Apellidos:</label>
								<input type="text" class="form-control" id="name" name="name" required />
							</div>
							<div class="form-group col-xs-12 col-sm-5">
								<label class="required" for="calle">Compañia:</label>
								<input type="text" class="form-control" id="name" name="name" required />
							</div>
							<div class="form-group col-xs-12 col-sm-7">
								<label class="required" for="manzana">Telefono:</label>
								<div class="input-group">
									<div class="input-group-addon"><i class="fa fa-phone"></i></div>
									<input data-mask="(000)-000-00-00" type="text" class="form-control" id="manzana" name="manzana" required />
								</div>
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="calle">Contraseña:</label>
								<input type="password" class="form-control" id="name" name="name" required />
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label class="required" for="calle">Confirmar contraseña:</label>
								<input type="password" class="form-control" id="name" name="name" required />
							</div>
							<legend>Miembros de Grupo</legend>
							<div class="form-group col-xs-12 col-sm-6">
								<div class="checkbox-custom">
									<input type="checkbox" id="miembro">
									<label for="miembro">Administrador</label>
								</div>
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<div class="checkbox-custom">
									<input type="checkbox" id="miembro2">
									<label for="miembro2">Capturistas</label>
								</div>
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
<div class="modal fade" id="add-group" tabindex="-1" role="dialog" aria-labelledby="modalAddGroup" aria-hidden="true">
 	<div class="modal-dialog" role="document">
	    <div class="modal-content">
			<form action="" method="post" id="frm-add-manzanas" autocomplete="off">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="model-title">Añadir nuevo Grupo</h4>	
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<div class="form-group col-xs-12 col-sm-12">
								<label class="required" for="calle">Nombre del grupo:</label>
								<input type="text" class="form-control" id="name" name="name" required />
							</div>
							<div class="form-group col-xs-12 col-sm-12">
								<label class="required" for="calle">Descripción:</label>
								<input type="text" class="form-control" id="name" name="name" required />
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