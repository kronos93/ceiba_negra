<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <title>
        Reiniciar contraseña
    </title>
    <link rel="icon" type="image/png" href="<?= base_url() ?>icon.png" />
    <!-- Viewport for Responsivity -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <!-- <link rel="stylesheet" href="<?= base_url() ?>assets/css/estilos.css?v=<?= date("Y-m-dH:i:s") ?>"> -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/estilos.min.css?v=<?= date(" Y-m-dH:i:s ") ?>">
</head>

<body>
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="<?= base_url() ?>" class="brand-ceiba">
                        <figure>
                            <img src="<?= base_url() ?>assets/img/logos/logo_blanco.png" alt="La ceiba">
                            <figcaption>La Ceiba</figcaption>
                        </figure>
                    </a>
                    <?php if ($this->ion_auth->logged_in()) : ?>
                    <div class="shopCart" id="shopCartSale">
                        <span class="fa fa-shopping-cart fa-2x" id="shopCartSaleCount" data-venta="<?= $this->cart->total_items() ?>"></span>
                        <nav class="my-dropdown">
                            <ul id="listaVenta"></ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <?php $this->load->view('./templates/menu/registros'); ?>
                    <?php $this->load->view('./templates/menu/perfil'); ?>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
    </header>
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
                                        <div id="infoMessage">
                                            <?php echo $message;?>
                                        </div>
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
    <footer>
        <div class="container-fluid container">
            <div class="row">
                <div class="col-xs-12">
                    <p>&#x24B8; Derechos Reservados Huertos La Ceiba 2017</p>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>


