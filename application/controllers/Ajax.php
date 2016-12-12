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
        $manzanas = $this->Manzana_model->getAll('array');

        foreach($manzanas as $key => $manzana){
            if($manzanas[$key]['disponibilidad'] === 0){
                $manzanas[$key]['disponibilidad'] = "Invendible";
            }else{
                $manzanas[$key]['disponibilidad'] = "Vendible";   
            }
        }
        $response = $this->format_datatable($manzanas);
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
        if($this->input->post('manzana') && $this->input->post('calle')){ //&& $this->input->post('disponibilidad')
            $insert = [
                'manzana' => $this->input->post('manzana'),
                'calle'   => $this->input->post('calle'),
                'disponibilidad' => $this->input->post('disponibilidad')
            ];
            $this->Manzana_model->insert($insert);
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