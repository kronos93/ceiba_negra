<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Carbon\Carbon;
class Registros extends CI_Controller {
	function __construct() {
        parent::__construct();
		$this->load->model('Manzana_model');
		$this->load->model('Precio_model');
        $this->load->model('Historial_model');
		$this->load->model('Opciones_ingreso_model');
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

		$data['manzanas'] = $this->Manzana_model->select('id_manzana,manzana')
									    		->get_();
		$data['precios'] = $this->Precio_model->select('id_precio,enganche,abono')
									    		->get();
		$this->load->view('templates/template',$data);
	}
	public function opciones_de_ingreso(){
		if (!$this->ion_auth->logged_in()) {
        // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else {
			$data['title'] = "Opciones de ingreso";
			$data['body'] = "opciones_ingreso";					
			$this->load->view('templates/template',$data);
		}
	}
	public function ingresos($id) {
		if (!$this->ion_auth->logged_in()) {
        // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else {
			$data['title'] = "Ingresos";
			$data['body'] = "ingresos";				
			
			if ($this->input->get('init_date') && $this->input->get('end_date')) {
				$data['init_date'] = Carbon::createFromFormat('d-m-Y',$this->input->get('init_date'));
				$data['end_date'] = Carbon::createFromFormat('d-m-Y',$this->input->get('end_date'));
				$data['ingresos'] = $this->Opciones_ingreso_model->join('historial','historial.id_ingreso = opciones_ingreso.id_opcion_ingreso','left')
																->where("id_opcion_ingreso = {$id} AND fecha BETWEEN '{$data['init_date']->format('Y-m-d')}' AND '{$data['end_date']->format('Y-m-d')}'")
																->get();
			} else {
				$data['ingresos'] = $this->Opciones_ingreso_model->join('historial','historial.id_ingreso = opciones_ingreso.id_opcion_ingreso','left')
																->where(['id_opcion_ingreso' => $id])
																->get();
				$data['init_date'] = $this->Historial_model->select('fecha')
														->order_by('fecha ASC')
														->limit(1)
														->get(); 		
				$data['init_date'] = Carbon::createFromFormat('Y-m-d',$data['init_date'][0]->fecha);
				$data['end_date'] = Carbon::today();
			}												
			$data['id'] = $id;	
			$this->load->view('templates/template',$data);
		}
	}
	public function pagos($id_venta) {
		$data['title'] = "Pagos";
		$data['body'] = "pagos";		
		$data['pagos'] = $this->Historial_model->select('historial.id_historial,
														 CONCAT(cliente.first_name," ",cliente.last_name) AS nombre_cliente,
														 CONCAT(lider.first_name," ",lider.last_name) AS nombre_lider,
														 IF( opciones_ingreso.id_opcion_ingreso != 1, 
															 CONCAT(opciones_ingreso.nombre, " - " ,opciones_ingreso.cuenta),
															 opciones_ingreso.nombre) as nombre,
														 historial.concepto,
														 historial.abono,
														 historial.fecha,
														 historial.estado,
														 historial.fecha_pago, 
														 IF( historial.estado = 0 , DATEDIFF( CURRENT_DATE() , historial.fecha ) , DATEDIFF( historial.fecha_pago ,historial.fecha ) ) as daysAccumulated,
														 historial.pago,
														 historial.comision,
														 historial.penalizacion,
														 (historial.pago + historial.penalizacion - historial.comision) as total')
												->join('ventas','historial.id_venta = ventas.id_venta','left')
												->join('users AS cliente','ventas.id_cliente = cliente.id','left')
												->join('users AS lider','historial.id_lider = lider.id','left')
												->join('opciones_ingreso','historial.id_ingreso = opciones_ingreso.id_opcion_ingreso','left')
												->where(['historial.id_venta' => $id_venta])
												->get();
		foreach($data['pagos'] as $key => $ingreso){
			if($ingreso->estado == 0) {				
				$fecha = Carbon::createFromFormat('Y-m-d',$ingreso->fecha);
				$today = Carbon::createFromFormat('Y-m-d', Carbon::today()->format('Y-m-d'));
				$data['pagos'][$key]->diff = $fecha->diff($today);
				
			}else{
				$fecha = Carbon::createFromFormat('Y-m-d',$ingreso->fecha);
				$fecha_pago = Carbon::createFromFormat('Y-m-d',$ingreso->fecha_pago);
				$data['pagos'][$key]->diff = $fecha->diff($fecha_pago);
			}
			
		}										
		$data['lideres'] = $this->ion_auth->users(3)->result();							
		$data['ingresos'] = $this->Opciones_ingreso_model->get();
		$this->load->view('templates/template',$data);
	}
	public function comisiones () {
		$data['title'] = "Comisiones";
		$data['body'] = "comisiones";				
		$this->load->view('templates/template',$data);
	}
}
