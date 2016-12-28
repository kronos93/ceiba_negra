
<?php echo form_open(uri_string());?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title"><?php echo lang('edit_user_heading');?></h4>
		<p><?php echo lang('edit_user_subheading');?></p>
		<div id="infoMessage"><?php echo $message;?></div>
	</div>
	<div class="modal-body">
		<div class="container-fluid">
			<div class="row">
				<div class="form-group col-xs-12">
					<?php $first_name['class'] = 'form-control';?>
					<?php echo lang('edit_user_fname_label', 'first_name',['class'=>'required']);?>
					<?php echo form_input($first_name);?>
				</div>
				<div class="form-group col-xs-12 col-sm-6">
					<?php $last_name['class'] = 'form-control';?>
					<?php echo lang('edit_user_lname_label', 'last_name');?>
					<?php echo form_input($last_name);?>
				</div>
				<div class="form-group col-xs-12 col-sm-6">
					<?php $company['class'] = 'form-control';?>
					<?php echo lang('edit_user_company_label', 'company');?>
					<?php echo form_input($company);?>
				</div>
				<div class="form-group col-xs-12 col-sm-6">
					<?php $phone['class'] = 'form-control';?>
					<?php echo lang('edit_user_phone_label', 'phone');?>
					<?php echo form_input($phone);?>
				</div>
				<div class="form-group col-xs-12 col-sm-6">
					<?php $password['class'] = 'form-control';?>
					<?php echo lang('edit_user_password_label', 'password');?>
					<?php echo form_input($password);?>
				</div>
				<div class="form-group col-xs-12 col-sm-6">
					<?php $password_confirm['class'] = 'form-control';?>
					<?php echo lang('edit_user_password_confirm_label', 'password_confirm');?><br />
					<?php echo form_input($password_confirm);?>
				</div>
				<legend><?php echo lang('edit_user_groups_heading');?></legend>
					<?php if ($this->ion_auth->is_admin()): ?>
							<?php foreach ($groups as $group):?>
								<div class="form-group col-xs-12 col-sm-3">
									<div class="checkbox-custom">
										<?php
												$gID=$group['id'];
												$checked = null;
												$item = null;
												foreach($currentGroups as $grp) {
														if ($gID == $grp->id) {
																$checked= ' checked="checked"';
														break;
														}
												}
										?>
										<input  id="<?php echo $group['name'];?>" type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
										<label for="<?php echo $group['name'];?>" class="checkbox">
										<?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
										</label>
										
									</div>
								</div>
							<?php endforeach?>

					<?php endif ?>
			</div>
		</div>
	</div>
	<?php echo form_hidden('id', $user->id);?>
	<?php echo form_hidden($csrf); ?>
	<div class="modal-footer">
		<p><?php echo form_submit('submit', lang('edit_user_submit_btn'),['class'=>'btn btn-success']);?></p>
	</div>
	

<?php echo form_close();?>
