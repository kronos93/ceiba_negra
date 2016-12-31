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
    public function prueba() {
        $fecha_contrato_txt = "el día jueves, 29 de diciembre del año 2016";
        $nombre_cliente = "Samuel Rojas Too";
        $manzana = 28;
        $data = compact(
                        'fecha_contrato_txt',
                        'nombre_cliente',
                        'manzana'
                        );
        $this->load->view("./templates/contrato/contrato",$data);
    }
}