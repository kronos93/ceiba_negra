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
        $mz_mapplic = $this->Manzana_model->mz_mapplic();
        $respuesta = new stdClass();
        $respuesta->categories = $mz_mapplic;
        $respuesta->mapwidth = 800;
        $respuesta->mapheight = 600;
        $respuesta->levels = [];
        foreach($mz_mapplic as $mz){
            $mz->show = "false";
            $id_manzana = substr($mz->id,2);
            $lev = new stdClass();
            $lev->id = "landmarks-{$mz->id}";
            $lev->title = "{$mz->title}";
         
            $lotes = $this->Lote_model->getAllM($id_manzana);
            
            $lev->locations = $lotes;
            $lev->map = base_url()."assets/img/mapas/{$mz->id}.svg"; 
            array_push($respuesta->levels,$lev);
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
    //LOTES
    public function add_huerto(){
            //Cabazera de respuesta JSON
            header("Content-type: application/json; charset=utf-8");   
            //Validación de form
            $this->form_validation->set_error_delimiters('', '');
            //$this->form_validation->set_rules('id_manzana', 'Manzana', 'trim|required');
            //$this->form_validation->set_rules('huerto', 'Huerto', 'trim|required');
            $this->form_validation->set_rules('huerto', 'Combinación', 'callback_mh_check[huerto,id_manzana]');
            //$this->form_validation->set_rules('superficie', 'Superficie', 'trim|required');
            //$this->form_validation->set_rules('id_precio', 'Precio', 'trim|required');
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