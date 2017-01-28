<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
			<div class="col-xs-12">
				<legend><?php echo lang('index_heading');?>
                    <button  data-toggle="modal" data-target="#userModal" data-btn-type="add" data-remote="<?= base_url()?>auth/create_user/" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Nuevo Usuario</button>
                    <div class="clearfix"></div>
                </legend>
            </div>
            <div class="col-xs-12">
                <!-- <p><?php echo lang('index_subheading');?></p> -->
                <div id="infoMessage"><?= $message;?></div>
                <table id="users-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="all"><?= lang('index_fname_th'); ?></th>
                            <th><?= lang('index_lname_th');?></th>
                            <th><?= lang('index_email_th');?></th>
                            <th><?= lang('index_groups_th');?></th>
                            <th class="all"><?= lang('index_status_th');?></th>
                            <th class="all"><?= lang('index_action_th');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user) :?>
                        <tr>
                            <td><?= htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8');?></td>
                            <td><?= htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8');?></td>
                            <td><?= htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8');?></td>
                            <td>
                                <ul>
                                <?php foreach ($user->groups as $group) :?>
                                    <li><?= htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8') ?></li>
                                <?php endforeach?>
                                </ul>
                            </td>
                            <td><?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, lang('index_active_link'), ["class"=>"btn btn-success", "data-target"=>'#form-user', 'data-toggle'=>"modal"]) : anchor("auth/activate/". $user->id, lang('index_inactive_link'), ["class"=>"btn btn-danger"]);?>
                            </td>
                            <td><?php echo anchor("auth/edit_user/".$user->id, lang('edit_user_heading'), ["class"=>"btn btn-info","data-target"=>'#userModal','data-toggle'=>'modal','data-btn-type'=>"add"]) ;?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- <p><?php echo anchor('auth/create_user', lang('index_create_user_link'))?> | <?php echo anchor('auth/create_group', lang('index_create_group_link'))?></p> -->
</main>
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">                  
        </div>
    </div>
</div>
