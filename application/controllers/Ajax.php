<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Carbon\Carbon;
class Ajax extends CI_Controller
{
   
    function __construct()
    {
        parent::__construct();
        $this->load->model('Huerto_model');
        $this->load->model('Manzana_model');
        $this->load->model('Cliente_model');
        $this->load->model('Opciones_ingreso_model');
        $this->load->model('Venta_model');
        $this->load->model('Historial_model');
        $this->load->model('Trans_model');
    }
    public function index()
    {
    }
    //DATOS PARA EL MAPA
    public function get_mapa()
    {
        header("Content-type: application/json; charset=utf-8");
        $categories_mz = $this->Manzana_model->categories_mz();
        $respuesta = new stdClass();
        $respuesta->categories = $categories_mz;
        $respuesta->mapwidth = 800;
        $respuesta->mapheight = 600;
        $respuesta->levels = [];
        foreach ($categories_mz as $mz) {
            $mz->show = "false";
            
            $level = new stdClass();
            $level->id = "landmarks-{$mz->id}";
            $level->title = "{$mz->title}";
         
            $huerto = $this->Huerto_model->getLevel($mz->manzana);
            
            $level->locations = $huerto;
            $level->map = base_url()."assets/img/mapas/{$mz->id}.svg";
            array_push($respuesta->levels, $level);
        }
        echo json_encode($respuesta);
    }
    //Herramienta para Max
    public function guardar_coordenadas()
    {
        $manzana = $this->input->post("manzana");
        $lote = $this->input->post("lote");
        $where = array('id_manzana' => $manzana, 'huerto' => $lote);
        $update = array('x' => $this->input->post("x"), 'y' => $this->input->post("y"));
        $this->Huerto_model->set_coordenadas($where, $update);
    }
    //HUERTOS
    public function get_huertos_pmz()
    {
        header("Content-type: application/json; charset=utf-8");
        $response = new stdClass();
        $huertos = $this->Huerto_model->getHuertosPM();
        $response->data = $huertos;
        echo json_encode($response);
    }
    public function add_huerto()
    {
        //Cabazera de respuesta JSON
        header("Content-type: application/json; charset=utf-8");
        //Validación de form
        $config = [
            [
                'field' => 'id_manzana',
                'label' => 'Referencia manzana',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'huerto',
                'label' => 'Huerto',
                'rules' => 'trim|required|callback_mhi_check[huerto,id_manzana]',
            ],
            [
                'field' => 'superficie',
                'label' => 'Superficie',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'precio_x_m2',
                'label' => 'Precio por metro cuadrado',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'id_precio',
                'label' => 'Recomendación de pagos',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'col_norte',
                'label' => 'Colindancia al norte',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_noreste',
                'label' => 'Colindancia al noreste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_este',
                'label' => 'Colindancia al este',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_sureste',
                'label' => 'Colindancia al sureste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_sur',
                'label' => 'Colindancia al sur',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_suroeste',
                'label' => 'Colindancia al suroeste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_oeste',
                'label' => 'Colindancia al oeste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_noroeste',
                'label' => 'Colindancia al noroeste',
                'rules' => 'trim',
            ],
        ];
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run()) {
            $data = [
            'id_manzana' => $this->input->post('id_manzana'),
            'huerto'   => $this->input->post('huerto'),
            'superficie' => $this->input->post('superficie'),
            'precio_x_m2' => $this->input->post('precio_x_m2'),
            'id_precio' => $this->input->post('id_precio'),
            'col_norte' => $this->input->post('col_norte'),
            'col_noreste' => $this->input->post('col_noreste'),
            'col_este' => $this->input->post('col_este'),
            'col_sureste' => $this->input->post('col_sureste'),
            'col_sur' => $this->input->post('col_sur'),
            'col_suroeste' => $this->input->post('col_suroeste'),
            'col_oeste' => $this->input->post('col_oeste'),
            'col_noroeste' => $this->input->post('col_noroeste'),
            ];
            $huerto = $this->Huerto_model->insert($data);
            echo json_encode($huerto);
        } else {
            echo validation_errors();
        }
    }
    public function update_huerto()
    {
        header("Content-type: application/json; charset=utf-8");
         //Validación de form
        $config = [
            [
                'field' => 'id_manzana',
                'label' => 'Referencia manzana',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'id_huerto',
                'label' => 'Referencia huerto',
                'rules' => 'trim|required|callback_mhu_check[id_huerto]',
            ],
            [
                'field' => 'huerto',
                'label' => 'Huerto',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'superficie',
                'label' => 'Superficie',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'precio_x_m2',
                'label' => 'Precio por metro cuadrado',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'id_precio',
                'label' => 'Recomendación de pagos',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'col_norte',
                'label' => 'Colindancia al norte',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_noreste',
                'label' => 'Colindancia al noreste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_este',
                'label' => 'Colindancia al este',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_sureste',
                'label' => 'Colindancia al sureste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_sur',
                'label' => 'Colindancia al sur',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_suroeste',
                'label' => 'Colindancia al suroeste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_oeste',
                'label' => 'Colindancia al oeste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_noroeste',
                'label' => 'Colindancia al noroeste',
                'rules' => 'trim',
            ],
        ];
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run()) {
            $where = ['id_huerto' => $this->input->post("id_huerto")];
            $set_data = [
                'id_manzana' => $this->input->post("id_manzana"),
                'huerto' => $this->input->post("huerto"),
                'superficie' => $this->input->post("superficie"),
                'id_precio' => $this->input->post("id_precio"),
                'col_norte' => $this->input->post('col_norte'),
                'col_noreste' => $this->input->post('col_noreste'),
                'col_este' => $this->input->post('col_este'),
                'col_sureste' => $this->input->post('col_sureste'),
                'col_sur' => $this->input->post('col_sur'),
                'col_suroeste' => $this->input->post('col_suroeste'),
                'col_oeste' => $this->input->post('col_oeste'),
                'col_noroeste' => $this->input->post('col_noroeste'),
            ];
            
            $response = $this->Huerto_model->update($set_data, $where);
            if (count($response)) {
                echo json_encode($response);
            } else {
                echo "No se detectó ningún cambio en los datos al actualizar.";
            }
        } else {
            echo validation_errors();
        }
    }
    //MANZANAS
    public function get_manzanas()
    {
        header("Content-type: application/json; charset=utf-8"); //Header generico
        $response = new stdClass(); //Clase generica
        $manzanas = $this->Manzana_model->getAll('array'); //<- Obtener datos
        $response->data = $manzanas; // Atributo de la clase generico para el dataTable
        echo json_encode($response); //Response JSON
    }
    public function add_manzana()
    {
        //Cabazera de respuesta JSON
        header("Content-type: application/json; charset=utf-8");
        //Validación de form
        $config = [
            [
                'field' => 'manzana',
                'label' => 'número de manzana',
                'rules' => 'trim|required|numeric|is_unique[manzanas.manzana]',
            ],
            [
                'field' => 'calle',
                'label' => 'Calle',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'superficie',
                'label' => 'superficie',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'disponibilidad',
                'label' => 'Disponibilidad de la manzana',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'col_norte',
                'label' => 'Colindancia al norte',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_noreste',
                'label' => 'Colindancia al noreste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_este',
                'label' => 'Colindancia al este',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_sureste',
                'label' => 'Colindancia al sureste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_sur',
                'label' => 'Colindancia al sur',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_suroeste',
                'label' => 'Colindancia al suroeste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_oeste',
                'label' => 'Colindancia al oeste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_noroeste',
                'label' => 'Colindancia al noroeste',
                'rules' => 'trim',
            ],
        ];
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run()) {
            $data = [
                        'manzana' => $this->input->post('manzana'),
                        'calle'   => $this->input->post('calle'),
                        'superficie' => $this->input->post('superficie'),
                        'disponibilidad' => $this->input->post('disponibilidad'),
                        'col_norte' => $this->input->post('col_norte'),
                        'col_noreste' => $this->input->post('col_noreste'),
                        'col_este' => $this->input->post('col_este'),
                        'col_sureste' => $this->input->post('col_sureste'),
                        'col_sur' => $this->input->post('col_sur'),
                        'col_suroeste' => $this->input->post('col_suroeste'),
                        'col_oeste' => $this->input->post('col_oeste'),
                        'col_noroeste' => $this->input->post('col_noroeste'),
            ];
            
            $manzana = $this->Manzana_model->insert($data);
            echo json_encode($manzana);
        } else {
            echo validation_errors();
        }
    }
    public function update_manzana()
    {
        header("Content-type: application/json; charset=utf-8");
        $config = [
            [
                'field' => 'id_manzana',
                'label' => 'Referencia de manzana',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'calle',
                'label' => 'Calle',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'superficie',
                'label' => 'superficie',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'disponibilidad',
                'label' => 'Disponibilidad de la manzana',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'col_norte',
                'label' => 'Colindancia al norte',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_noreste',
                'label' => 'Colindancia al noreste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_este',
                'label' => 'Colindancia al este',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_sureste',
                'label' => 'Colindancia al sureste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_sur',
                'label' => 'Colindancia al sur',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_suroeste',
                'label' => 'Colindancia al suroeste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_oeste',
                'label' => 'Colindancia al oeste',
                'rules' => 'trim',
            ],
            [
                'field' => 'col_noroeste',
                'label' => 'Colindancia al noroeste',
                'rules' => 'trim',
            ],
        ];
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run()) {
            $where = ['id_manzana' => $this->input->post("id_manzana")];
            $set_data = [
                    'calle' => $this->input->post("calle"),
                    'superficie' => $this->input->post("superficie"),
                    'disponibilidad' => $this->input->post("disponibilidad"),
                    'col_norte' => $this->input->post("col_norte"),
                    'col_noreste' => $this->input->post("col_noreste"),
                    'col_este' => $this->input->post("col_este"),
                    'col_sureste' => $this->input->post("col_sureste"),
                    'col_sur' => $this->input->post("col_sur"),
                    'col_suroeste' => $this->input->post("col_suroeste"),
                    'col_oeste' => $this->input->post("col_oeste"),
                    'col_noroeste' => $this->input->post("col_noroeste"),
            ];
            $response = $this->Manzana_model->update($set_data, $where);
            if (count($response)) {
                echo json_encode($response);
            } else {
                echo "No se detectó ningún cambio en los datos al actualizar.";
            }
        } else {
            echo validation_errors();
        }
    }
    //OPCIONES DE INGRESO
    public function get_opciones_de_ingreso(){
        header("Content-type: application/json; charset=utf-8"); //Header generico
        $response = new stdClass(); //Clase generica
        $opciones_ingreso = $this->Opciones_ingreso_model->get(); //<- Obtener datos
        $response->data = $opciones_ingreso; // Atributo de la clase generico para el dataTable
        echo json_encode($response); //Response JSON
    }    
    public function add_opcion_ingreso(){
        header("Content-type: application/json; charset=utf-8");
        //Validación de form
        $config = [
            [
                'field' => 'nombre',
                'label' => 'Nombre de la opción de ingreso',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'cuenta',
                'label' => 'Cuenta',
                'rules' => 'trim|required|numeric|is_unique[opciones_ingreso.cuenta]',
            ],
            [
                'field' => 'tarjeta',
                'label' => 'Tarjeta',
                'rules' => 'trim|required|numeric|is_unique[opciones_ingreso.tarjeta]',
            ],
        ];
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run()) {
            $data = [
                        'nombre' => $this->input->post('nombre'),
                        'cuenta'   => $this->input->post('cuenta'),
                        'tarjeta' => $this->input->post('tarjeta'),                        
            ];            
            $new_id_opcion_ingreso = $this->Opciones_ingreso_model->insert($data)->insert_id();
            $new_opcion_ingreso = $this->Opciones_ingreso_model->where(['id_opcion_ingreso' => $new_id_opcion_ingreso])->get();
            echo json_encode($new_opcion_ingreso);
        } else {
            echo validation_errors();
        }
    }
    public function update_opcion_ingreso(){
        header("Content-type: application/json; charset=utf-8");
        //Validación de form
        $config = [
            [
                'field' => 'id_opcion_ingreso',
                'label' => 'Opción de ingreso',
                'rules' => 'trim|required|callback_opingreso_check[id_opcion_ingreso]',
            ],
            [
                'field' => 'nombre',
                'label' => 'Nombre de la opción de ingreso',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'cuenta',
                'label' => 'Cuenta',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'tarjeta',
                'label' => 'Tarjeta',
                'rules' => 'trim|required|numeric',
            ],
        ];
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run()) {
            $data = [
                        'nombre' => $this->input->post('nombre'),
                        'cuenta'   => $this->input->post('cuenta'),
                        'tarjeta' => $this->input->post('tarjeta'),                        
            ];            

            $updated_data = $this->Opciones_ingreso_model
                                                    ->where(['id_opcion_ingreso' => $this->input->post('id_opcion_ingreso')])
                                                    ->update($data)
                                                    ->affected_rows();

            if ($updated_data) {
                $response = $this->Opciones_ingreso_model
                                                    ->where(['id_opcion_ingreso' => $this->input->post('id_opcion_ingreso')])
                                                    ->get();
                echo json_encode($response);
            } else {
                echo "No se detectó ningún cambio en los datos al actualizar.";
            }


        } else {
            echo validation_errors();
        }
    }
    public function get_pagos(){
        header("Content-type: application/json; charset=utf-8");
        if($this->input->post('id_historial')){
            $historial = $this->Historial_model ->select('  historial.abono,
                                                            historial.id_lider, 
                                                            ventas.porcentaje_comision, 
                                                            ventas.porcentaje_penalizacion,
                                                            IF( DATEDIFF( CURRENT_DATE() , historial.fecha ) > 0,  
                                                                IF( historial.estado = 0 , 
                                                                    DATEDIFF( CURRENT_DATE() , historial.fecha ), 
                                                                    0 ) , 
                                                                0) AS daysAccumulated,
                                                            TRUNCATE( (historial.abono * (ventas.porcentaje_comision / 100)), 2) AS comision,
                                                            TRUNCATE( (historial.abono * (ventas.porcentaje_penalizacion / 100)) * ( IF( DATEDIFF( CURRENT_DATE() , historial.fecha ) > 0,  
                                                                                                                                         IF( historial.estado = 0 , 
                                                                                                                                             DATEDIFF( CURRENT_DATE() , historial.fecha ), 
                                                                                                                                             0 ) , 
                                                                                                                                         0)), 2) AS penalizacion')
                                                ->join('ventas','ventas.id_venta = historial.id_venta','left')
                                                ->where(['id_historial' => $this->input->post('id_historial')])
                                                ->get();
            echo json_encode($historial[0]);
        }        
    }
    public function get_comision() {
        header("Content-type: application/json; charset=utf-8");
        if($this->input->post('id_historial')){
            $historial = $this->Historial_model ->select('  historial.abono, 
                                                            historial.id_lider, 
                                                            ventas.porcentaje_comision, 
                                                            TRUNCATE( (historial.abono * (ventas.porcentaje_comision / 100)), 2) AS comision')
                                                ->join('ventas','ventas.id_venta = historial.id_venta','left')
                                                ->where(['id_historial' => $this->input->post('id_historial')])
                                                ->get();
            echo json_encode($historial[0]);
        }        
    }
    public function pagar(){
        header("Content-type: application/json; charset=utf-8");
        if($this->input->post('id_historial') &&
           $this->input->post('id_ingreso')){
            $fecha = Carbon::createFromFormat('d-m-Y', $this->input->post('fecha_pago'));
            $set_data = [
                'fecha_pago' => $fecha->format('Y-m-d'),
                'pago' => $this->input->post('pago'),
                'id_ingreso' => $this->input->post('id_ingreso'),
                'estado' => 1,
            ];
            if($this->input->post('confirm_comision') == 'true'){
                $set_data['comision'] = $this->input->post('comision');
                $set_data['id_lider'] = $this->input->post('id_lider');
            }
            if($this->input->post('confirm_penalizacion') == 'true'){
                $set_data['penalizacion'] = $this->input->post('penalizacion');
            }
            $n_updated_pago = $this->Historial_model->where(['id_historial' => $this->input->post('id_historial')])
                                  ->update($set_data)
                                  ->affected_rows();
           if($n_updated_pago == 1){
                $updated_pago = $this->Historial_model->select('historial.id_historial,
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
                                                      ->where(['id_historial' => $this->input->post('id_historial')])
                                                      ->get();
                $detalles = "";
                foreach($updated_pago as $pago){
                   if($pago->daysAccumulated > 0) { 
                        $detalles .= 'Realizó el pago con un retraso de: '.$pago->daysAccumulated.' días.';                                            
                    } else if($pago->daysAccumulated == 0) {
                        $detalles .= 'Pagado en tiempo.';
                    } else if($pago->daysAccumulated < 0) {
                        $detalles .= 'Pagado por adelantado.';
                    }
                    $detalles .= '<div>Pago: $' . number_format($pago->pago,2) .'</div>';
                    $detalles .= '<div>Deposito en: ' . $pago->nombre .'</div>';
                    $detalles .= '<div>Fecha: ' . $pago->fecha_pago .'</div>';
                    $detalles .= '<div>Comisión: $' . number_format($pago->comision,2) .'</div>';
                    $detalles .= '<div>Penalización: $' . number_format($pago->penalizacion,2) .'</div>';
                    $detalles .= '<div>Total: $' . number_format($pago->total,2) .'</div>';
                }
                $updated_pago[0]->estado = ($updated_pago[0]->estado == 0) ? 'Pendiente' : 'Pagado';
                $updated_pago[0]->detalles = $detalles;
                $updated_pago[0]->abono = '$' . number_format($updated_pago[0]->abono,2);
                $updated_pago[0]->is_admin = $this->ion_auth->in_group('administrador');
                echo json_encode($updated_pago[0]);
           }
        }
    }
    public function remover_pago () {
        header("Content-type: application/json; charset=utf-8");
        if($this->input->post('id_historial')){
            $row = $this->Historial_model->where(['id_historial' => $this->input->post('id_historial')])
                                         ->get();
            $set_data = [
                'fecha' => $row[0]->fecha,
                'id_ingreso' => 0,
                'pago' => 0,
                'comision' => 0,
                'penalizacion' => 0,
                'estado' => 0,
            ];
            $n_updated_pago = $this->Historial_model->where(['id_historial' => $this->input->post('id_historial')])
                                  ->update($set_data)
                                  ->affected_rows();
            if($n_updated_pago == 1){
                $updated_pago = $this->Historial_model->select('historial.id_historial,
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
                                                      ->where(['id_historial' => $this->input->post('id_historial')])
                                                      ->get();
                $detalles = "";
                foreach($updated_pago as $pago){
                   if($pago->daysAccumulated > 0) { 
                        $detalles.= 'Tiene un retraso en pago de: '.$pago->daysAccumulated . ' días.';                        
                    } else if($pago->daysAccumulated == 0){
                        $detalles.= 'Hoy es día de pago.';                        
                    }else{
                        $detalles.= 'Aun no es fecha de pago.';                                    
                    }
                }
                $updated_pago[0]->estado = ($updated_pago[0]->estado == 0) ? 'Pendiente' : 'Pagado';
                $updated_pago[0]->detalles = $detalles;
                $updated_pago[0]->abono = '$' . number_format($updated_pago[0]->abono,2);
                echo json_encode($updated_pago[0]);
           }
        }
    }
    public function pagar_comision(){
        header("Content-type: application/json; charset=utf-8");
        if($this->input->post('id_historial')){
            $set_data = [
                'comision' => $this->input->post('comision'),     
                'id_lider' => $this->input->post('id_lider'),                
            ];
            $n_updated_pago = $this->Historial_model->where(['id_historial' => $this->input->post('id_historial')])
                                  ->update($set_data)
                                  ->affected_rows();
           if($n_updated_pago == 1){
               $updated_pago = $this->Historial_model->select(' historial.id_historial,
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
                                                      ->where(['id_historial' => $this->input->post('id_historial')])
                                                      ->get();
                $detalles = "";
                foreach($updated_pago as $pago){
                   if($pago->daysAccumulated > 0) { 
                        $detalles .= 'Realizó el pago con un retraso de: '.$pago->daysAccumulated.' días.';                                            
                    } else if($pago->daysAccumulated == 0) {
                        $detalles .= 'Pagado en tiempo.';
                    } else if($pago->daysAccumulated < 0) {
                        $detalles .= 'Pagado por adelantado.';
                    }
                    $detalles .= '<div>Pago: $' . number_format($pago->pago,2) .'</div>';
                    $detalles .= '<div>Deposito en: ' . $pago->nombre .'</div>';
                    $detalles .= '<div>Fecha: ' . $pago->fecha_pago .'</div>';
                    $detalles .= '<div>Comisión: $' . number_format($pago->comision,2) .'</div>';
                    $detalles .= '<div>Penalización: $' . number_format($pago->penalizacion,2) .'</div>';
                    $detalles .= '<div>Total: $' . number_format($pago->total,2) .'</div>';
                }
                $updated_pago[0]->estado = ($updated_pago[0]->estado == 0) ? 'Pendiente' : 'Pagado';
                $updated_pago[0]->detalles = $detalles;
                $updated_pago[0]->abono = '$' . number_format($updated_pago[0]->abono,2);
                echo json_encode($updated_pago[0]);
           }
        }
    }
    public function autocomplete_clientes()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->get('query')) {
            $like = $this->input->get('query');
            $full_name = "CONCAT(users.first_name,' ',users.last_name)";
            $select = ' users.id AS id_cliente,
                        users.first_name, 
                        users.last_name, 
                        users.email, 
                        users.phone, 
                        users.calle, 
                        users.no_ext, 
                        users.no_int, 
                        users.phone, 
                        users.colonia, 
                        users.municipio, 
                        users.estado,
                        users.ciudad,
                        users.cp,
                        users.lugar_nacimiento,
                        DATE_FORMAT(users.fecha_nacimiento,"%d-%m-%Y") as fecha_nacimiento';
            $clientes = $this->ion_auth->select("{$select},{$full_name} AS data, {$full_name} AS value")->where(["{$full_name} LIKE" => "%{$like}%",'active' => 1])->users('cliente')->result();
            $response = new stdClass();
            $response->suggestions = $clientes;
            echo json_encode($response);
        }
    }
    public function autocomplete_lideres()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->get('query')) {
            $like = $this->input->get('query');
            $full_name = "CONCAT(users.first_name,' ',users.last_name)";
            $lideres = $this->ion_auth->select("users.id, {$full_name} AS data, {$full_name} AS value")->where(["{$full_name} LIKE" => "%{$like}%",'active' => 1])->users('lider')->result();
            $response = new stdClass();
            $response->query = $this->input->get('query');
            $response->suggestions = $lideres;
            echo json_encode($response);
        }
    }
    public function add_cart()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->post("id_huerto")) {
            $where = [
                    'id_huerto' => $this->input->post("id_huerto"),
                    'vendido' => false
            ];
            $huertos = $this->Huerto_model->getHuertosPM($where, "object");
            
            if (count($huertos)) {
                foreach ($huertos as $huerto) {
                    $data = array(
                        'id'      => 'huerto_'.$huerto->id_huerto,
                        'qty'     => 1,
                        'price'   => (float) $huerto->precio,
                        'abono'   => (float) $huerto->abono,
                        'enganche'   => (float) $huerto->enganche,
                        'name'    => "Manzana: {$huerto->manzana}, Huerto: {$huerto->huerto}",
                        'id_huerto' => $huerto->id_huerto,
                        'id_manzana' => $huerto->id_manzana,
                        'manzana' => $huerto->manzana,
                        'huerto' => $huerto->huerto,
                    );
                    $this->cart->insert($data);
                }
            }
        }
        $this->show_cart();
    }
    public function delete_cart()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->post("rowid")) {
            $this->cart->remove($this->input->post("rowid"));
        }
        $this->show_cart();
    }
    public function show_cart()
    {
        $respuesta = new stdClass();
        $respuesta->huertos = [];
        $respuesta->enganche = 0;
        $respuesta->abono = 0;
        foreach ($this->cart->contents() as $items) {
            $obj = new stdClass();
            $obj->descripcion = $items["name"];
            $rowid = $items["rowid"];
            $obj->btn = "<button class='btn btn-danger itemCartDelete' value='{$rowid}'><i class='fa fa-trash'></i></button>";
            array_push($respuesta->huertos, $obj);
            $respuesta->enganche +=  $items["enganche"];
            $respuesta->abono +=  $items["abono"];
        }
        $respuesta->enganche = ($respuesta->enganche);
        $respuesta->abono = ($respuesta->abono);
        $respuesta->total = ($this->cart->total());
        $respuesta->count = $this->cart->total_items();
        if ($this->ion_auth->in_group('administrador') || $this->ion_auth->in_group('miembro')) {
            $respuesta->link = ($this->cart->total_items()>0) ? '<a href="'.base_url().'venta" class="btn btn-success center-block">Vender</a>' : '';
        } elseif ($this->ion_auth->in_group('lider')) {
            $respuesta->link = ($this->cart->total_items()>0) ? '<a href="'.base_url().'#" class="btn btn-warning center-block">Reservar</a>' : '';
        }

        echo json_encode($respuesta);
    }
    public function add_ion_user()
    {
        header("Content-type: application/json; charset=utf-8");
        $this->form_validation->set_error_delimiters('', '');
        $tables = $this->config->item('tables', 'ion_auth');

        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        if ($identity_column!=='email') {
            $this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true) {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone'),
            );
        }

        if ($this->form_validation->run() == true && $idNewUser = $this->ion_auth->register($identity, $password, $email, $additional_data)) {
            // check to see if we are creating the user
            // redirect them back to the admin page
            //$this->session->set_flashdata('message', $this->ion_auth->messages());
            //redirect("auth", 'refresh');
            $newUser = $this->ion_auth->user($idNewUser)->row();
            $newUser->btn_activar_desactivar = '<a href="'.base_url().'auth/deactivate/'.$newUser->user_id.'" class="btn btn-success" data-target="#userModal" data-toggle="modal">Activo</a>';
            $newUser->btn_editar = '<a href="'.base_url().'auth/edit_user/'.$newUser->user_id.'" class="btn btn-info" data-target="#userModal" data-toggle="modal" data-btn-type="edit">Editar Usuario</a>';
            $grupo = "<ul>";
            foreach($this->ion_auth->get_users_groups($idNewUser)->result() as $group){
                $grupo.= '<li>'.$group->name.'</li>';
            }
            $grupo .= "<ul>";
            $newUser->groups = $grupo;
            $response = [];
            array_push($response,$newUser);
            echo json_encode($response);
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            echo $this->data['message'];
        }
    }
    public function update_ion_user(){
        header("Content-type: application/json; charset=utf-8");
        $this->form_validation->set_error_delimiters('', '');
        $id = $this->input->post('id');
        $user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			/*if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}*/

			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}
            if ($this->input->post('email'))
            {

                $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[users.email]');
            }
			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'company'    => $this->input->post('company'),
					'phone'      => $this->input->post('phone'),
				);

				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}


                if ($this->input->post('email'))
                {
                    $data['email'] = strtolower($this->input->post('email'));
                    $data['username'] = strtolower($this->input->post('email'));
                }
				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin())
				{
					//Update the groups user belongs to
					$groupData = $this->input->post('groups');

					if (isset($groupData) && !empty($groupData)) {

						$this->ion_auth->remove_from_group('', $id);

						foreach ($groupData as $grp) {
							$this->ion_auth->add_to_group($grp, $id);
						}

					}
				}

			    // check to see if we are updating the user
			   if($this->ion_auth->update($user->id, $data))
			    {
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    //$this->session->set_flashdata('message', $this->ion_auth->messages() );
                    $newUser = $this->ion_auth->user($user->id)->row();
                    $newUser->btn_activar_desactivar = '<a href="'.base_url().'auth/deactivate/'.$user->id.'" class="btn btn-success" data-target="#userModal" data-toggle="modal">Activo</a>';
                    $newUser->btn_editar = '<a href="'.base_url().'auth/edit_user/'.$user->id.'" class="btn btn-info" data-target="#userModal" data-toggle="modal" data-btn-type="edit">Editar Usuario</a>';
                    $grupo = "<ul>";
                    foreach($this->ion_auth->get_users_groups($user->id)->result() as $group){
                        $grupo.= '<li>'.$group->name.'</li>';
                    }
                    $grupo .= "<ul>";
                    $newUser->groups = $grupo;
                    $response = [];
                    array_push($response,$newUser);
                    echo json_encode($response);
				    /*if ($this->ion_auth->is_admin())
					{
						redirect('auth', 'refresh');
					}
					else
					{
						redirect('/', 'refresh');
					}*/

			    }
			    else
			    {
			    	// redirect them back to the admin page if admin, or to the base url if non admin
                    echo $this->ion_auth->errors();
				    /*$this->session->set_flashdata('message', $this->ion_auth->errors() );
				    if ($this->ion_auth->is_admin())
					{
						redirect('auth', 'refresh');
					}
					else
					{
						redirect('/', 'refresh');
					}*/

			    }

			}else{
                echo validation_errors();
            }
		}

		// display the edit user form
		//$this->data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		/*$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
/*
		// pass the user to the view
		/*$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;


		$this->_render_page('auth/edit_user', $this->data);*/
    }
    public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}
    public function _valid_csrf_nonce()
	{
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
    //Utileria inutil xD.... Despreciar usando stdClass
    public function format_datatable($data)
    {
        $response = array("data" => array());
        foreach ($data as $row) {
            $values = array_values($row);
            array_push($response['data'], $values);
        }
        return $response;
    }
    //Manzana y huerto combinados en insert
    public function mhi_check($value, $request)
    {
        $data = explode(",", $request);
        $where = [];
        foreach ($data as $input) {
            $where['huertos.' . $input] = $this->input->post($input);
        }
        $huertos = $this->Huerto_model->getHuertosPM($where);
        if (count($huertos)) {
             $this->form_validation->set_message('mhi_check', 'Combinacion Manzana/Huerto repetida.'); // set your message
             return false;
        } else {
            return true;
        }
    }
    //Manzana y huerto combinados en update
    public function mhu_check($value, $request)
    {
        $data = explode(",", $request);
        $where = [];
        foreach ($data as $input) {
            $where['huertos.' . $input] = $this->input->post($input);
        }
        
        $huertos = $this->Huerto_model->getHuertosPM($where, 'object');
        if (count($huertos)) { //Si el huerto por alguna razón deja de existir, un delete
            if ($huertos[0]->huerto == $this->input->post('huerto')) { //Si el huerto no ha cambiado
                return true;
            } else { //Si se ha cambiado el número de huerto verificar que no exista en otro lado
                $where = [];
                $where['huertos.id_manzana'] = $this->input->post('id_manzana');
                $where['huertos.huerto'] = $this->input->post('huerto');
                $huertos = $this->Huerto_model->getHuertosPM($where, 'obj');
                if (count($huertos)) {
                    $this->form_validation->set_message('mhu_check', 'Combinacion Manzana/Huerto repetida'); // set your message
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            $this->form_validation->set_message('mhu_check', 'Intenta actualizar un huerto no existente.'); // set your message
            return false;
        }
    }
    public function opingreso_check($value, $request){
        $data = explode(",", $request);
        $where = [];
        foreach ($data as $input) {
            $where['opciones_ingreso.' . $input] = $this->input->post($input);
        }
        $op_ingreso = $this->Opciones_ingreso_model->where($where)->get();
        if (count($op_ingreso)) { //Si la opcion de ingreso por alguna razón deja de existir, un delete
            if ($op_ingreso[0]->cuenta == $this->input->post('cuenta')) { //Si el huerto no ha cambiado
                return true;
            } else {
                $exist_cuenta_op_ingreso = $this->Opciones_ingreso_model->where(['cuenta' => $this->input->post('cuenta') ])->get();
                if(count($exist_cuenta_op_ingreso)){
                    $this->form_validation->set_message('opingreso_check', 'Cuenta de ingreso ya existente.'); // set your message
                    return false;
                }else{
                    return true;
                }
            }
            if ($op_ingreso[0]->tarjeta == $this->input->post('tarjeta')) { //Si el huerto no ha cambiado
                return true;
            } else {
                $exist_cuenta_op_ingreso = $this->Opciones_ingreso_model->where(['tarjeta' => $this->input->post('tarjeta') ])->get();
                if(count($exist_cuenta_op_ingreso)){
                    $this->form_validation->set_message('opingreso_check', 'Tarjeta de ingreso ya existente.'); // set your message
                    return false;
                }else{
                    return true;
                }
            }
        } else{
            $this->form_validation->set_message('opingreso_check', 'Intenta actualizar una opcion de ingreso no existente.'); // set your message
            return false;
        }
    }
}
