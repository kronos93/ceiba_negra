<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registros extends CI_Controller {
	function __construct() {
        parent::__construct();
        $this->load->model('Historial_model');
    }
	public function manzanas() {	
		$data['title'] = "Manzanas"; //Titulo de la página -> require
		$data['body'] = "manzanas";	 //Nombre de la vista de cuerpo -> require
		$this->load->view('templates/template',$data);	//Combina header y footer con body
	}
	public function huertos()
	{		
		$data['title'] = "Huertos";
		$data['body'] = "huertos";
		$this->load->view('templates/template',$data);
	}
	public function opciones_de_ingreso(){
		$data['title'] = "Opciones de ingreso";
		$data['body'] = "opciones_ingreso";				
		$this->load->view('templates/template',$data);
	}
	public function pagos($id_venta) {
		$data['title'] = "Pagos";
		$data['body'] = "pagos";		
		$data['pagos'] = $this->Historial_model->select('	id_historial,
															concepto,
															abono,
															fecha,
															estado,
															fecha_pago, 
															IF( estado = 0 , DATEDIFF( CURRENT_DATE() , fecha ) , DATEDIFF( fecha_pago ,fecha ) ) as daysAccumulated,
															pago,
															comision,
															penalizacion,
															(pago + penalizacion - comision) as total')
											   ->where( ['id_venta'=>$id_venta])
											   ->get();		
		$this->load->view('templates/template',$data);
	}
}
