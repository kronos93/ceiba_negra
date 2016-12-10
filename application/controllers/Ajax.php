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
            if($id_manzana >= 5 && $id_manzana <= 20)
                array_push($respuesta->levels,$lev);
        }      
        echo json_encode($respuesta);
	}

    public function get_manzanas(){
        header("Content-type: application/json; charset=utf-8");
        $manzanas = $this->Manzana_model->getAll('array');

        foreach($manzanas as $key => $manzana){
            if($manzanas[$key]['estado'] === 0){
                $manzanas[$key]['estado'] = "Invendible";
            }else{
                $manzanas[$key]['estado'] = "Vendible";   
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
}