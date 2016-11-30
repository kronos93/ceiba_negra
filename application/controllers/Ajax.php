<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {	
    function __construct() {
        parent::__construct();
        $this->load->model('Lote_model');
    }

    public function get_lotes() {
        header("Content-type: application/json; charset=utf-8");
		$lotes = $this->Lote_model->getAll();
        echo json_encode($lotes);
	}
}