
        <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="model-title"><?= lang('deactivate_heading')?></h4>	
        </div>
        
        <p><?php echo sprintf(lang('deactivate_subheading'), $user->username);?></p>
        <?php echo form_open("auth/deactivate/".$user->id);?>
          OLA KE ACE 
          <p>
            <?php echo lang('deactivate_confirm_y_label', 'confirm');?>
            <input type="radio" name="confirm" value="yes" checked="checked" />
            <?php echo lang('deactivate_confirm_n_label', 'confirm');?>
            <input type="radio" name="confirm" value="no" />
          </p>

          <?php echo form_hidden($csrf); ?>
          <?php echo form_hidden(array('id'=>$user->id)); ?>

          <p><?php echo form_submit('submit', lang('deactivate_submit_btn'));?></p>

        <?php echo form_close();?>
