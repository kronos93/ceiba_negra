<?php echo form_open("auth/deactivate/".$user->id);?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="model-title"><?= lang('deactivate_heading')?></h4>
		<p class="text-left"><?php echo sprintf(lang('deactivate_subheading'), $user->username);?></p>
	</div>
	<div class="modal-body">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12">
					<?php echo lang('deactivate_confirm_y_label', 'confirm');?>
					<input type="radio" name="confirm" value="yes" checked="checked" />
					<?php echo lang('deactivate_confirm_n_label', 'confirm');?>
					<input type="radio" name="confirm" value="no" />
					<?php echo form_hidden($csrf); ?>
					<?php echo form_hidden(array('id'=>$user->id)); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<?php echo form_submit('submit', lang('deactivate_submit_btn'),['class'=>'btn btn-info pull-right']);?>
	</div>
<?php echo form_close();?>
	
