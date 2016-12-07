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
        $respuesta->levels = [];
        foreach($mz_mapplic as $mz){
            $mz->show = "false";
            $id_manzana = substr($mz->id,2);
            $lev = new stdClass();
            $lev->id = "landmarks-{$mz->id}";
            $lev->title = "{$mz->title}";
         
            $lotes = $this->Lote_model->getAllM($id_manzana);
            
            $lev->locations = $lotes;
            $lev->map = base_url()."assets/mapas/{$mz->id}.svg"; 
            if($id_manzana >= 5 && $id_manzana <= 20)
                array_push($respuesta->levels,$lev);
        }
    
        

        echo json_encode($respuesta);
	}


}