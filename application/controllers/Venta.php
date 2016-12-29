<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venta extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('Contrato_model');
    }
	public function index() {
        $data['title'] = "Venta";
		$data['body'] = "venta";
		$this->load->view('templates/template',$data);        
    }
}