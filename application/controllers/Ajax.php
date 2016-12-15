<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {	
    function __construct() {
        parent::__construct();
        $this->load->model('Lote_model');
        $this->load->model('Manzana_model');
    }

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

    public function get_manzanas(){
        header("Content-type: application/json; charset=utf-8");
        $response = new stdClass();

        $manzanas = $this->Manzana_model->getAll('array');
        $response->data = $manzanas;
        //$response = $this->format_datatable($manzanas);
        echo json_encode($response);
    }
    public function get_lotes_pmz(){
        header("Content-type: application/json; charset=utf-8");
        $lotes = $this->Lote_model->lotesPM();
        $response = $this->format_datatable($lotes);
        echo json_encode($response);
    }

    public function format_datatable($data){
        $response = array("data" => array());
        foreach($data as $row){
            $values = array_values($row);
            array_push($response['data'],$values);
        }
        return $response;
    }
    //Manzanas
    public function add_manzana(){
            header("Content-type: application/json; charset=utf-8");   
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('manzana', 'Manzana', 'trim|required|is_unique[manzanas.manzana]');
            $this->form_validation->set_rules('calle', 'Calle', 'trim|required');
            $this->form_validation->set_rules('disponibilidad', 'Disponibilidad de la manzana', 'trim|required');
            if ($this->form_validation->run()) {
                $insert = [
                    'manzana' => $this->input->post('manzana'),
                    'calle'   => $this->input->post('calle'),
                    'disponibilidad' => $this->input->post('disponibilidad')
                ];
                $add_manzana = $this->Manzana_model->insert($insert);
                $response = ['id_manzana' => $add_manzana];
                echo json_encode($response);
            } else {
               echo validation_errors();
            }
    
    }
    //Herramienta para Max
    public function guardar_coordenadas(){
        $manzana = $this->input->post("manzana");
        $lote = $this->input->post("lote");
        $where = array('id_manzana' => $manzana, 'lote' => $lote);
        $update = array('x' => $this->input->post("x"), 'y' => $this->input->post("y"));
        $this->Lote_model->set_coordenadas($where,$update);
    }
}