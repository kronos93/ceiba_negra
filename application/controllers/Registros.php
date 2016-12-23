<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registros extends CI_Controller {
	function __construct() {
        parent::__construct();
        $this->load->model('Lote_model');
        $this->load->model('Manzana_model');
		$this->load->model('Precio_model');
    }
	public function manzanas() {		
		$data['title'] = "Manzanas"; //Titulo de la página -> require
		$data['body'] = "manzanas";	 //Nombre de la vista de cuerpo -> require
		$this->load->view('templates/template',$data);	//Combina header y footer con body
	}
	public function lotes()
	{		
		$data['title'] = "Lotes";
		$data['body'] = "lotes";
		$data['manzanas']= $this->Manzana_model->getAll('object');
		$data['precios']= $this->Precio_model->getAll();
		$this->load->view('templates/template',$data);
	}
}
