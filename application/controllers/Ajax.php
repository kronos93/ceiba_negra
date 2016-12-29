<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {	
    function __construct() {
        parent::__construct();
        $this->load->model('Huerto_model');
        $this->load->model('Manzana_model');
    }
    //DATOS PARA EL MAPA
    public function get_mapa() {
        header("Content-type: application/json; charset=utf-8");
        $categories_mz = $this->Manzana_model->categories_mz();
        $respuesta = new stdClass();
        $respuesta->categories = $categories_mz;
        $respuesta->mapwidth = 800;
        $respuesta->mapheight = 600;
        $respuesta->levels = [];
        foreach($categories_mz as $mz){          
            $mz->show = "false";            
            
            $level = new stdClass();
            $level->id = "landmarks-{$mz->id}";
            $level->title = "{$mz->title}";
         
            $huerto = $this->Huerto_model->getLevel($mz->manzana);
            
            $level->locations = $huerto;
            $level->map = base_url()."assets/img/mapas/{$mz->id}.svg"; 
            array_push($respuesta->levels,$level);
        }      
        echo json_encode($respuesta);
	}
    //Herramienta para Max
    public function guardar_coordenadas(){
        $manzana = $this->input->post("manzana");
        $lote = $this->input->post("lote");
        $where = array('id_manzana' => $manzana, 'lote' => $lote);
        $update = array('x' => $this->input->post("x"), 'y' => $this->input->post("y"));
        $this->Lote_model->set_coordenadas($where,$update);
    }
    //Huertos
    public function add_huerto(){
            //Cabazera de respuesta JSON
            header("Content-type: application/json; charset=utf-8");   
            //Validación de form
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('id_manzana', 'Manzana', 'trim|required');
            $this->form_validation->set_rules('huerto', 'Huerto', 'trim|required|callback_mh_check[huerto,id_manzana]');
            $this->form_validation->set_rules('superficie', 'Superficie', 'trim|required');
            $this->form_validation->set_rules('id_precio', 'Precio', 'trim|required');
            if ($this->form_validation->run()) {
                $insert = [
                    'id_manzana' => $this->input->post('id_manzana'),
                    'huerto'   => $this->input->post('huerto'),
                    'superficie' => $this->input->post('superficie'),
                    'id_precio' => $this->input->post('id_precio'),
                    'col_norte' => $this->input->post('col_norte'),
                    'col_sur' => $this->input->post('col_sur'),
                    'col_este' => $this->input->post('col_este'),
                    'col_oeste' => $this->input->post('col_oeste'),                    
                ];
                $huerto = $this->Huerto_model->insert($insert);
                echo json_encode($huerto);
            } else {
               echo validation_errors();
            }
    
    }
    public function get_huertos_pmz(){
        header("Content-type: application/json; charset=utf-8");
        $response = new stdClass();
        $huertos = $this->Huerto_model->huertosPM();
        $response->data = $huertos;
        echo json_encode($response);
    }
    public function update_huerto(){
        header("Content-type: application/json; charset=utf-8");
         //Validación de form
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('id_manzana', 'Manzana', 'trim|required');
            $this->form_validation->set_rules('huerto', 'Huerto', 'trim|required|callback_mh_check[huerto,id_manzana]');
            $this->form_validation->set_rules('superficie', 'Superficie', 'trim|required');
            $this->form_validation->set_rules('id_precio', 'Precio', 'trim|required');
            if ($this->form_validation->run()) {
                
            } else {
               echo validation_errors();
            }
    }
    //MANZANAS
    public function add_manzana(){
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
                    'disponibilidad' => $this->input->post('disponibilidad'),
                    'col_norte' => $this->input->post('col_norte'),
                    'col_sur' => $this->input->post('col_sur'),
                    'col_este' => $this->input->post('col_este'),
                    'col_oeste' => $this->input->post('col_oeste'),   
                ];
                $manzana = $this->Manzana_model->insert($insert);                
                echo json_encode($manzana);
            } else {
               echo validation_errors();
            }
    
    }
    public function get_manzanas(){
        header("Content-type: application/json; charset=utf-8"); //Header generico
        $response = new stdClass(); //Clase generica
        $manzanas = $this->Manzana_model->getAll('array'); //<- Obtener datos
        $response->data = $manzanas; // Atributo de la clase generico para el dataTable
        echo json_encode($response); //Response JSON
    }
    public function update_manzana(){
        header("Content-type: application/json; charset=utf-8");
        $where = ['id_manzana' => $this->input->post("id_manzana")];
        $set = ['calle' => $this->input->post("calle"),
                'disponibilidad' => $this->input->post("disponibilidad"),
                'col_norte' => $this->input->post("col_norte"), 
                'col_sur' => $this->input->post("col_sur"), 
                'col_este' => $this->input->post("col_este"), 
                'col_oeste' => $this->input->post("col_oeste"), 
            ];
        $response = $this->Manzana_model->update($where,$set);

        if(count($response)){
            echo json_encode($response);
        }else{
            echo "No se detectó ningún cambio en los datos al actualizar.";
        }
        
    }
    public function add_cart(){        
        header("Content-type: application/json; charset=utf-8");
        if($this->input->post("id_huerto")){
            $where = [
                    'id_huerto' => $this->input->post("id_huerto"),
                    'vendido' => 0
            ];
            $huerto = $this->Huerto_model->huertosPM($where,"obj");        
            if(count($huerto)){        
                $data = array(
                    'id'      => 'huerto_'.$huerto[0]->id_huerto,
                    'qty'     => 1,
                    'price'   => (float) $huerto[0]->precio,
                    'abono'   => (float) $huerto[0]->abono,
                    'enganche'   => (float) $huerto[0]->enganche,
                    'name'    => "Manzana: {$huerto[0]->manzana}, Huerto: {$huerto[0]->huerto}"
                );
                $this->cart->insert($data);
            }
        }
        $this->show_cart();
    }
    public function delete_cart(){
        header("Content-type: application/json; charset=utf-8");
        if($this->input->post("rowid")){
            $this->cart->remove($this->input->post("rowid"));
        }
        $this->show_cart();
    }
    public function show_cart(){
        $respuesta = new stdClass();
        $respuesta->huertos = [];
        $respuesta->enganche = 0;
        $respuesta->abono = 0;        
        foreach ($this->cart->contents() as $items){   
            $obj = new stdClass();     
            $obj->descripcion = $items["name"];
            $rowid = $items["rowid"];
            $obj->btn = "<button class='btn btn-danger itemCartDelete' value='{$rowid}'><i class='fa fa-trash'></i></button>";
            array_push($respuesta->huertos,$obj);  
            $respuesta->enganche +=  $items["enganche"];
            $respuesta->abono +=  $items["abono"];
        }                
        $respuesta->enganche = $this->cart->format_number($respuesta->enganche);
        $respuesta->abono = $this->cart->format_number($respuesta->abono);
        $respuesta->total = $this->cart->format_number($this->cart->total());
        $respuesta->count = $this->cart->total_items();
        echo json_encode($respuesta);
    }
    //Utileria inutil xD.... Despreciar usando stdClass
    public function format_datatable($data){
        $response = array("data" => array());
        foreach($data as $row){
            $values = array_values($row);
            array_push($response['data'],$values);
        }
        return $response;
    }
    //Manzana y huerto combinados
    public function mh_check($value,$request){
        $data = explode(",",$request);
        $where = [];
        foreach($data as $input){
            $where['huertos.' . $input] = $this->input->post($input);
        }
        $huertos = $this->Huerto_model->huertosPM($where);
        if(count($huertos)){
             $this->form_validation->set_message('mh_check','Combinacion Manzana/Huerto repetida.'); // set your message
             return false;
        }else{
            return true;
        }
    }
}