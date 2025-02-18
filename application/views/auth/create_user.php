<?= form_open("auth/create_user",['id'=>'frm-ion-user']);?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title"><?= lang('create_user_heading'); ?></h4>
		<p><?= lang('create_user_subheading'); ?></p>
	</div>
	<div class="modal-body">
		<div class="container-fluid">
			<div class="row">
				<div class="form-group col-xs-12 col-sm-12">
                    <?php $first_name['class'] = 'form-control';?>
					<?php echo lang('create_user_fname_label', 'first_name',['class'=>'required']);?>
					<?php echo form_input($first_name);?>
                </div>
                <div class="form-group col-xs-12 col-sm-6">
                 	<?php $last_name['class'] = 'form-control';?>
                	<?php echo lang('create_user_lname_label', 'last_name',['class'=>'required']);?>
					<?php echo form_input($last_name);?>
                </div>
                <div class="form-group col-xs-12 col-sm-6">
                	<?php
						if($identity_column!=='email') {
								echo '<p>';
								echo lang('create_user_identity_label', 'identity');
								echo '<br />';
								echo form_error('identity');
								echo form_input($identity);
								echo '</p>';
						}
					?>
					<?php echo lang('create_user_company_label', 'company');?>
					<?php $company['class'] = 'form-control';?>
					<?php echo form_input($company);?>
                </div>
                <div class="form-group col-xs-12 col-sm-6">
                	<?php $email['class'] = 'form-control';?>
                	<?php echo lang('create_user_email_label', 'email');?>
					<?php echo form_input($email);?>
                </div>
                <div class="form-group col-xs-12 col-sm-6">
               		<?php $phone['class'] = 'form-control';?>
                	<?php echo lang('create_user_phone_label', 'phone');?>
					<?php echo form_input($phone);?>
                </div>
                <div class="form-group col-xs-12 col-sm-6">
                	<?php $password['class'] = 'form-control';?>
                	<?php echo lang('create_user_password_label', 'password',['class'=>'required']);?>
					<?php echo form_input($password);?>
                </div>
                <div class="form-group col-xs-12 col-sm-6">
                	<?php $password_confirm['class'] = 'form-control';?>
                	<?php echo lang('create_user_password_confirm_label', 'password_confirm',['class'=>'required']);?>
					<?php echo form_input($password_confirm);?>
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
			<?php echo form_submit('submit', lang('create_user_submit_btn'),['class'=>'btn btn-success confirm']);?>
			<div class="loader-gif">
				<div class="loader-gif-item"></div>
				<div class="loader-gif-item"></div>
				<div class="loader-gif-item"></div>
			</div>
		</div>
	</div>
<?php echo form_close();?>
