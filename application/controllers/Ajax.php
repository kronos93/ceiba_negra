<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends CI_Controller
{
   
    function __construct()
    {
        parent::__construct();
        $this->load->model('Huerto_model');
        $this->load->model('Manzana_model');
        $this->load->model('Cliente_model');
    }
    public function index(){
        
    }
    //DATOS PARA EL MAPA
    public function get_mapa()
    {
        header("Content-type: application/json; charset=utf-8");
        $categories_mz = $this->Manzana_model->categories_mz();
        $respuesta = new stdClass();
        $respuesta->categories = $categories_mz;
        $respuesta->mapwidth = 800;
        $respuesta->mapheight = 600;
        $respuesta->levels = [];
        foreach ($categories_mz as $mz) {
            $mz->show = "false";
            
            $level = new stdClass();
            $level->id = "landmarks-{$mz->id}";
            $level->title = "{$mz->title}";
         
            $huerto = $this->Huerto_model->getLevel($mz->manzana);
            
            $level->locations = $huerto;
            $level->map = base_url()."assets/img/mapas/{$mz->id}.svg";
            array_push($respuesta->levels, $level);
        }
        echo json_encode($respuesta);
    }
    //Herramienta para Max
    public function guardar_coordenadas()
    {
        $manzana = $this->input->post("manzana");
        $lote = $this->input->post("lote");
        $where = array('id_manzana' => $manzana, 'huerto' => $lote);
        $update = array('x' => $this->input->post("x"), 'y' => $this->input->post("y"));
        $this->Huerto_model->set_coordenadas($where, $update);
    }
    //Huertos
    public function add_huerto()
    {
        //Cabazera de respuesta JSON
        header("Content-type: application/json; charset=utf-8");
        //Validación de form
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id_manzana', 'Manzana', 'trim|required');
        $this->form_validation->set_rules('huerto', 'Huerto', 'trim|required|callback_mhi_check[huerto,id_manzana]');
        $this->form_validation->set_rules('superficie', 'Superficie', 'trim|required');
        $this->form_validation->set_rules('precio_x_m2', 'Precio por metro cuadrado', 'trim|required');
        $this->form_validation->set_rules('id_precio', 'Precio', 'trim|required');
        if ($this->form_validation->run()) {
            $insert = [
            'id_manzana' => $this->input->post('id_manzana'),
            'huerto'   => $this->input->post('huerto'),
            'superficie' => $this->input->post('superficie'),
            'precio_x_m2' => $this->input->post('precio_x_m2'),
            'id_precio' => $this->input->post('id_precio'),
            'col_norte' => $this->input->post('col_norte'),
            'col_noreste' => $this->input->post('col_noreste'),
            'col_este' => $this->input->post('col_este'),
            'col_sureste' => $this->input->post('col_sureste'),
            'col_sur' => $this->input->post('col_sur'),
            'col_suroeste' => $this->input->post('col_suroeste'),
            'col_oeste' => $this->input->post('col_oeste'),
            'col_noroeste' => $this->input->post('col_noroeste'),
            ];
            $huerto = $this->Huerto_model->insert($insert);
            echo json_encode($huerto);
        } else {
            echo validation_errors();
        }
    }
    public function get_huertos_pmz()
    {
        header("Content-type: application/json; charset=utf-8");
        $response = new stdClass();
        $huertos = $this->Huerto_model->getHuertosPM();
        $response->data = $huertos;
        echo json_encode($response);
    }
    public function update_huerto()
    {
        header("Content-type: application/json; charset=utf-8");
         //Validación de form
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('id_manzana', 'Manzana', 'trim|required');
            $this->form_validation->set_rules('id_huerto', 'Huerto', 'trim|required|callback_mhu_check[id_huerto]');
            $this->form_validation->set_rules('superficie', 'Superficie', 'trim|required');
            $this->form_validation->set_rules('id_precio', 'Precio', 'trim|required');
        if ($this->form_validation->run()) {
            $set = [
                'id_manzana' => $this->input->post("id_manzana"),
                'huerto' => $this->input->post("huerto"),
                'superficie' => $this->input->post("superficie"),
                'id_precio' => $this->input->post("id_precio"),
                'col_norte' => $this->input->post('col_norte'),
                'col_noreste' => $this->input->post('col_noreste'),
                'col_este' => $this->input->post('col_este'),
                'col_sureste' => $this->input->post('col_sureste'),
                'col_sur' => $this->input->post('col_sur'),
                'col_suroeste' => $this->input->post('col_suroeste'),
                'col_oeste' => $this->input->post('col_oeste'),
                'col_noroeste' => $this->input->post('col_noroeste'),
            ];
            $where = ['id_huerto' => $this->input->post("id_huerto")];
            $response = $this->Huerto_model->update($set, $where);
            if (count($response)) {
                echo json_encode($response);
            } else {
                echo "No se detectó ningún cambio en los datos al actualizar.";
            }
        } else {
            echo validation_errors();
        }
    }
    //MANZANAS
    public function add_manzana()
    {
            //Cabazera de respuesta JSON
            header("Content-type: application/json; charset=utf-8");
            //Validación de form
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('manzana', 'Manzana', 'trim|required|is_unique[manzanas.manzana]');
            $this->form_validation->set_rules('calle', 'Calle', 'trim|required');
            $this->form_validation->set_rules('disponibilidad', 'Disponibilidad de la manzana', 'trim|required');
        if ($this->form_validation->run()) {
            $insert = [
            'manzana' => $this->input->post('manzana'),
            'calle'   => $this->input->post('calle'),
            'superficie' => $this->input->post('superficie'),
            'disponibilidad' => $this->input->post('disponibilidad'),
            'col_norte' => $this->input->post('col_norte'),
            'col_noreste' => $this->input->post('col_noreste'),
            'col_este' => $this->input->post('col_este'),
            'col_sureste' => $this->input->post('col_sureste'),
            'col_sur' => $this->input->post('col_sur'),
            'col_suroeste' => $this->input->post('col_suroeste'),
            'col_oeste' => $this->input->post('col_oeste'),
            'col_noroeste' => $this->input->post('col_noroeste'),
            ];
            $manzana = $this->Manzana_model->insert($insert);
            echo json_encode($manzana);
        } else {
            echo validation_errors();
        }
    }
    public function get_manzanas()
    {
        header("Content-type: application/json; charset=utf-8"); //Header generico
        $response = new stdClass(); //Clase generica
        $manzanas = $this->Manzana_model->getAll('array'); //<- Obtener datos
        $response->data = $manzanas; // Atributo de la clase generico para el dataTable
        echo json_encode($response); //Response JSON
    }
    public function update_manzana()
    {
        header("Content-type: application/json; charset=utf-8");
        $where = ['id_manzana' => $this->input->post("id_manzana")];
        $set = [
                'calle' => $this->input->post("calle"),
                'superficie' => $this->input->post("superficie"),
                'disponibilidad' => $this->input->post("disponibilidad"),
                'col_norte' => $this->input->post("col_norte"),
                'col_noreste' => $this->input->post("col_noreste"),
                'col_este' => $this->input->post("col_este"),
                'col_sureste' => $this->input->post("col_sureste"),
                'col_sur' => $this->input->post("col_sur"),
                'col_suroeste' => $this->input->post("col_suroeste"),
                'col_oeste' => $this->input->post("col_oeste"),
                'col_noroeste' => $this->input->post("col_noroeste"),
        ];
        $response = $this->Manzana_model->update($set, $where);

        if (count($response)) {
            echo json_encode($response);
        } else {
            echo "No se detectó ningún cambio en los datos al actualizar.";
        }
    }

    public function autocomplete_clientes()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->get('query')) {
            $like = $this->input->get('query');
            $full_name = "CONCAT(users.first_name,' ',users.last_name)";
            $select = 'users.first_name, users.last_name, users.email, users.phone, users.calle, users.no_ext, users.no_int, users.phone, users.colonia, users.municipio, users.estado,users.ciudad,users.cp';            
            $clientes = $this->ion_auth->select("{$select},{$full_name} AS data, {$full_name} AS value")->where(["{$full_name} LIKE" => "%{$like}%"])->users('cliente')->result();
            $response = new stdClass();
            $response->suggestions = $clientes;
            echo json_encode($response);
        }
    }
    public function autocomplete_lideres(){
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->get('query')) {
            $like = $this->input->get('query');
            $full_name = "CONCAT(users.first_name,' ',users.last_name)";
            $lideres = $this->ion_auth->select("users.id, {$full_name} AS data, {$full_name} AS value")->where(["{$full_name} LIKE" => "%{$like}%"])->users('lider')->result();
            $response = new stdClass();
            $response->suggestions = $lideres;
            echo json_encode($response);
        }
        
			
    }
    public function add_cart()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->post("id_huerto")) {
            $where = [
                    'id_huerto' => $this->input->post("id_huerto"),
                    'vendido' => false
            ];
            $huertos = $this->Huerto_model->getHuertosPM($where, "object");
            if (count($huertos)) {
                foreach($huertos as $huerto){
                    $data = array(
                        'id'      => 'huerto_'.$huerto->id_huerto,
                        'qty'     => 1,
                        'price'   => (float) $huerto->precio,
                        'abono'   => (float) $huerto->abono,
                        'enganche'   => (float) $huerto->enganche,
                        'name'    => "Manzana: {$huerto->manzana}, Huerto: {$huerto->huerto}",
                        'id_huerto' => $huerto->id_huerto,
                        'id_manzana' => $huerto->id_manzana,
                        'manzana' => $huerto->manzana,
                        'huerto' => $huerto->huerto,
                    );
                    $this->cart->insert($data);
                }
            }
        }
        $this->show_cart();
    }
    public function delete_cart()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->post("rowid")) {
            $this->cart->remove($this->input->post("rowid"));            
        }
        $this->show_cart();
    }
    public function show_cart()
    {
        $respuesta = new stdClass();
        $respuesta->huertos = [];
        $respuesta->enganche = 0;
        $respuesta->abono = 0;
        foreach ($this->cart->contents() as $items) {
            $obj = new stdClass();
            $obj->descripcion = $items["name"];
            $rowid = $items["rowid"];
            $obj->btn = "<button class='btn btn-danger itemCartDelete' value='{$rowid}'><i class='fa fa-trash'></i></button>";
            array_push($respuesta->huertos, $obj);
            $respuesta->enganche +=  $items["enganche"];
            $respuesta->abono +=  $items["abono"];
        }
        $respuesta->enganche = ($respuesta->enganche);
        $respuesta->abono = ($respuesta->abono);
        $respuesta->total = ($this->cart->total());
        $respuesta->count = $this->cart->total_items();
        if ($this->ion_auth->in_group('administrador') || $this->ion_auth->in_group('miembro'))
        {
            $respuesta->link = ($this->cart->total_items()>0) ? '<a href="'.base_url().'venta" class="btn btn-success center-block">Vender</a>' : '';
        }else if ($this->ion_auth->in_group('lider')){
            $respuesta->link = ($this->cart->total_items()>0) ? '<a href="'.base_url().'venta" class="btn btn-warning center-block">Reservar</a>' : '';
        }

        echo json_encode($respuesta);
    }
    public function create_user(){
        $tables = $this->config->item('tables','ion_auth');

        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        if($identity_column!=='email')
        {

            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else
        {
        	
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        //$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		//$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length["3"]|max_length["3"]|matches[password_confirm]');

        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true)
        {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($identity, $password, $email, $additional_data))
        {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth", 'refresh');
        }
        else
        {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            var_dump( $this->data['message'] );
        }
    }
    //Utileria inutil xD.... Despreciar usando stdClass
    public function format_datatable($data)
    {
        $response = array("data" => array());
        foreach ($data as $row) {
            $values = array_values($row);
            array_push($response['data'], $values);
        }
        return $response;
    }
    //Manzana y huerto combinados en insert
    public function mhi_check($value, $request)
    {
        $data = explode(",", $request);
        $where = [];
        foreach ($data as $input) {
            $where['huertos.' . $input] = $this->input->post($input);
        }
        $huertos = $this->Huerto_model->getHuertosPM($where);
        if (count($huertos)) {
             $this->form_validation->set_message('mhi_check', 'Combinacion Manzana/Huerto repetida.'); // set your message
             return false;
        } else {
            return true;
        }
    }
    //Manzana y huerto combinados en update
    public function mhu_check($value, $request)
    {
        $data = explode(",", $request);
        $where = [];
        foreach ($data as $input) {
            $where['huertos.' . $input] = $this->input->post($input);
        }
        
        $huertos = $this->Huerto_model->getHuertosPM($where, 'object');
        if (count($huertos)) { //Si el huerto por alguna razón deja de existir, un delete
            if ($huertos[0]->huerto == $this->input->post('huerto')) { //Si el huerto no ha cambiado
                return true;
            } else { //Si se ha cambiado el número de huerto verificar que no exista en otro lado
                $where = [];
                $where['huertos.id_manzana'] = $this->input->post('id_manzana');
                $where['huertos.huerto'] = $this->input->post('huerto');
                $huertos = $this->Huerto_model->getHuertosPM($where, 'obj');
                if (count($huertos)) {
                    $this->form_validation->set_message('mhu_check', 'Combinacion Manzana/Huerto repetida'); // set your message
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            $this->form_validation->set_message('mhu_check', 'Intenta actualizar un huerto no existente.'); // set your message
            return false;
        }
    }
}
