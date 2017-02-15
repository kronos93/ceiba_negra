<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registros extends CI_Controller {
	function __construct() {
        parent::__construct();
        
    }
	public function manzanas() {	
		//var_dump($this->Manzana_model->select("manzana")->where(['manzana'=>1])->_get());	
		$data['title'] = "Manzanas"; //Titulo de la página -> require
		$data['body'] = "manzanas";	 //Nombre de la vista de cuerpo -> require
		$this->load->view('templates/template',$data);	//Combina header y footer con body
	}
	public function huertos()
	{		
		$data['title'] = "Huertos";
		$data['body'] = "huertos";
		$data['manzanas']= $this->Manzana_model->getAll('object');
		$data['precios']= $this->Precio_model->getAll();
		$this->load->view('templates/template',$data);
	}
	public function opciones_de_ingreso(){
		$data['title'] = "Opciones de ingreso";
		$data['body'] = "opciones_ingreso";				
		$this->load->view('templates/template',$data);
	}
}
