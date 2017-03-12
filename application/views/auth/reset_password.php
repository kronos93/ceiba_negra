<main class="resetPassword">
    <div class="resetPassword--wrap">
        <section class="resetPassword__container">
            <article>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="resetPassword__header">
                                <h3>Cambiar Contraseña</h3>
                                <hr class="separator">
                            </div>
                            <div class="resetPassword__body">
                                <div class="row">
                                    <?php echo form_open('auth/reset_password/' . $code);?>
                                    <div class="form-group col-xs-12">
                                        <label for="new_password">
                                            <?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?>
                                        </label>
                                        <br />
                                        <?php $new_password['class'] = 'form-control';?>
                                        <?php echo form_input($new_password);?>
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="">
                                            <?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?>
                                        </label>
                                        <?php $new_password_confirm['class'] = 'form-control';?>
                                        <?php echo form_input($new_password_confirm);?>
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <?php echo form_input($user_id);?>
                                        <?php echo form_hidden($csrf); ?>
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <?php echo form_submit('submit', lang('reset_password_submit_btn'),['class'=>'btn btn-success pull-right']);?>
                                    </div>
                                    <?php echo form_close();?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </div>
</main>
<!-- <h1><?php echo lang('reset_password_heading');?></h1> -->
<div id="infoMessage">
    <?php echo $message;?>
</div>
