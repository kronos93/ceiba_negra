<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
			<div class="col-xs-12">
				<legend><?php echo lang('index_heading');?>
                    <button  data-toggle="modal" data-target="#add-user" data-remote="<?= base_url()?>auth/create_user/" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Nuevo Usuario</button>
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
                    <?php foreach ($users as $user) :?>
                        <tbody>
                            <tr>
                                <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8');?></td>
                                <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8');?></td>
                                <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8');?></td>
                                <td>
                                    <?php foreach ($user->groups as $group) :?>
                                        <?php echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8')) ;?><br />
                                    <?php endforeach?>
                                </td>
                                //Editar usuario de activo a Inactivo
                                <td><?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, lang('index_active_link'), ["class"=>"btn btn-success", "data-target"=>'#edit-user', 'data-toggle'=>"modal"]) : anchor("auth/activate/". $user->id, lang('index_inactive_link'), ["class"=>"btn btn-danger"]);?>
                                    
                                </td>
                                <td><?php echo anchor("auth/edit_user/".$user->id, 'Edit', ["class"=>"btn btn-info","data-target"=>'#edit-user','data-toggle'=>'modal']) ;?>
                                    
                                </td>
                            </tr>
                        </tbody>
                    <?php endforeach;?>
                </table>
            </div>
        </div>
    </div>
    <!-- <p><?php echo anchor('auth/create_user', lang('index_create_user_link'))?> | <?php echo anchor('auth/create_group', lang('index_create_group_link'))?></p> -->
</main>
<div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-labelledby="modalAddUser" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                  
        </div>
    </div>
</div>
<div class="modal fade" id="edit-user" tabindex="-1" role="dialog" aria-labelledby="modalEditUser" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
