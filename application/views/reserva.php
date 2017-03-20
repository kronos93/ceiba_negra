<main class="wrap-main">
    <div class="container-fluid container">
        <div class="row">
            <div class="col-xs-12">
                <form action="" id="frm-reserva" class="" autocomplete="off">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-6">
                                <label class="required" for="first_name">Nombre(s):</label>
                                <input type="text" class="form-control required" name="first_name" id="first_name" placeholder="Nombre" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label class="required" for="last_name">Apellidos</label>
                                <input type="text" class="form-control required" name="last_name" id="last_name" placeholder="Apellido Apellido" />
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label class="required" for="email">Correo electrónico</label>
                                <input type="text" class="form-control email" name="email" id="email" placeholder="alguien@dominio.com" />
                            </div>
                        
                            <div class="form-group col-xs-12 col-sm-6">
                                <label class="" for="phone">Celular</label>
                                <input type="text" class="form-control phone" name="phone" id="phone" placeholder="9983129864" />
                            </div>
                    
                            <div class="form-group col-xs-12 col-sm-4">
                                <label class="required" for="precio">Precio del huerto</label>
                                <input type="text" class="form-control required currency" name="precio" id="precio" value="" placeholder="" required />
                            </div>
                            <div class="form-group col-xs-12 col-sm-4">
                                <label class="required" for="manzana">Enganche</label>
                                <input type="text" class="form-control required currency" name="enganche" id="enganche" placeholder="" required />
                            </div>
                            <div class="form-group col-xs-12 col-sm-4">
                                <label class="required" for="manzana">Abono</label>
                                <input type="text" class="form-control required currency" name="abono" id="abono" placeholder="" required />
                            </div>
                            <div class="form-group col-xs-12 col-sm-12">
                                <label class="required" for="comentario">Comentario</label>
                                <textarea name="comment" rows="10" class="form-control"></textarea>
                            </div>
                            <div class="form-group col-xs-12">
                                <input type="submit" class="btn btn-primary" id="" value="Reservar huertos"/>
                            </div>                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>