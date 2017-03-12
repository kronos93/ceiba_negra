<main class="forgotPassword">
    <div class="forgotPassword--wrap">
        <section class="forgotPassword__container">
            <article class="forgotPassword__header">
                <h3>Recuperacion de Cuenta</h3>
            </article>
            <article class="forgotPassword__body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-12">
                                    <p class="form-group">
                                        <!-- <?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?> -->
                                        Porfavor, introduce tu correo electronico para la recuperacion de tu contraseña.
                                    </p>
                                </div>
                            </div>
                            <div id="infoMessage">
                                <?php echo $message;?>
                            </div>
                            <?= form_open("auth/forgot_password");?>
                            <div class="col-xs-12 form-group">
                                <label for="identity">
                                    <?php echo (($type=='email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label));?>
                                </label>
                                <?php $identity['class'] = 'form-control';?>
                                <?php echo form_input($identity);?>
                            </div>
                            <p>
                                <?= form_submit('submit', lang('forgot_password_submit_btn'),['class'=>'btn btn-success pull-right']);?>
                            </p>
                            <?= form_close();?>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </div>
</main>
