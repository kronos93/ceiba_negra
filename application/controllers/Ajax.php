<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Carbon\Carbon;

class Ajax extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Huerto_model');
        $this->load->model('Manzana_model');
        $this->load->model('Cliente_model');
        $this->load->model('Opciones_ingreso_model');
        $this->load->model('Venta_model');
        $this->load->model('Historial_model');
        $this->load->model('HuertosVentas_model');
        $this->load->model('Reserva_model');
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
    public function get_opciones_de_ingreso()
    {
        header("Content-type: application/json; charset=utf-8"); //Header generico
        $response = new stdClass(); //Clase generica
        $opciones_ingreso = $this->Opciones_ingreso_model->select(' opciones_ingreso.id_opcion_ingreso, 
																	opciones_ingreso.nombre, 
																	opciones_ingreso.cuenta, 
																	opciones_ingreso.tarjeta,
																	SUM( IF(ventas.estado != 3 ,
                                                                            (historial.pago - historial.comision + historial.penalizacion),
                                                                            0
                                                                            )) as ingreso	
																  ')
                                                         ->join('historial', 'opciones_ingreso.id_opcion_ingreso = historial.id_ingreso', 'left')
                                                         ->join('ventas', 'historial.id_venta = ventas.id_venta', 'left')

                                                         ->group_by('opciones_ingreso.id_opcion_ingreso')
                                                         ->get(); //<- Obtener datos
        $response->data = $opciones_ingreso; // Atributo de la clase generico para el dataTable
        echo json_encode($response); //Response JSON
    }
    public function add_opcion_ingreso()
    {
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
    public function update_opcion_ingreso()
    {
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
    //RESERVAS
    public function get_reservas()
    {
        header("Content-type: application/json; charset=utf-8"); //Header generico
        $response = new stdClass(); //Clase generica
        $reservacion = new $this->Reserva_model();
        if ($this->ion_auth->in_group(['lider'])) {
            $reservacion->where(['reservas.id_lider'=> $this->ion_auth->get_user_id()]);
            
            if ($this->session->userdata('id_reserva')) {
                $reservacion->where(['reservas.id_reserva' => $this->session->userdata('id_reserva')]);
                $this->session->unset_userdata('id_reserva');
            }
        }
         
        $reservas = $reservacion->select("reservas.id_reserva, 
                                                  CONCAT(lider.first_name, ' ',lider.last_name ) AS nombre_lider,
                                                  GROUP_CONCAT('Mz. ',manzanas.manzana, ' Ht. ', huertos.huerto) AS descripcion,
                                                  reservas.email,
                                                  reservas.phone,
                                                  reservas.comment,
                                                  reservas.precio,
                                                  reservas.enganche,
                                                  reservas.abono,
                                                  DATE_FORMAT(reservas.expira,'%d-%m-%Y') as expira
                                                  ")
                                        ->join('users AS lider', 'reservas.id_lider = lider.id', 'left')
                                        ->join('huertos_reservas', 'reservas.id_reserva = huertos_reservas.id_reserva', 'inner')
                                        ->join('huertos', 'huertos_reservas.id_huerto = huertos.id_huerto', 'inner')
                                        ->join('manzanas', 'huertos.id_manzana = manzanas.id_manzana', 'inner')
                                        ->group_by('reservas.id_reserva')
                                        ->get();
        foreach ($reservas as $key => $reserva) {
            $reservas[$key]->detalles = '';
            $reservas[$key]->detalles .= "<p><strong>Correo: </strong>".$reserva->email."<p>";
            $reservas[$key]->detalles .= "<p><strong>Teléfono: </strong><span class='phone'>".$reserva->phone."</span><p>";
            $reservas[$key]->detalles .= "<p><strong>Precio: </strong>$".number_format($reserva->precio, 2)."<p>";
            $reservas[$key]->detalles .= "<p><strong>Enganche: </strong>$".number_format($reserva->enganche, 2)."<p>";
            $reservas[$key]->detalles .= "<p><strong>Abono: </strong>$".number_format($reserva->abono, 2)."<p>";
            $reservas[$key]->detalles .= "<p><strong>Comentarios: </strong>".$reserva->comment."<p>";
            $reservas[$key]->is_admin = $this->ion_auth->in_group('administrador');
        }
        $response->data = $reservas; // Atributo de la clase generico para el dataTable
        echo json_encode($response); //Response JSON
    }
    public function get_pagos()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->post('id_historial')) {
            $historial = $this->Historial_model ->select('  ventas.id_venta, 
                                                            historial.abono,
															historial.id_lider, 
															ventas.porcentaje_comision, 
															ventas.porcentaje_penalizacion,
                                                            ventas.comision AS limite_comision,
															DATE_FORMAT(historial.fecha,"%d-%m-%Y") as fecha,
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
                                                ->join('ventas', 'ventas.id_venta = historial.id_venta', 'left')
                                                ->where(['id_historial' => $this->input->post('id_historial')])
                                                ->get();
            //Cuanto se ha pagado en comisiones
            $comisionado = $this->Historial_model->select("SUM(historial.comision) AS comisionado")
                                                 ->where(['id_venta' =>$historial[0]->id_venta])
                                                 ->get();
            $historial[0]->comisionado = $comisionado[0]->comisionado;
            echo json_encode($historial[0]);
        }
    }
    public function get_comision()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->post('id_historial')) {
            //Datos para la comision
            $historial = $this->Historial_model->select('   historial.abono, 
															historial.id_lider, 
															ventas.porcentaje_comision,
                                                            ventas.comision AS limite_comision,
                                                            ventas.id_venta, 
															TRUNCATE( (historial.abono * (ventas.porcentaje_comision / 100)), 2) AS comision')
                                                ->join('ventas', 'ventas.id_venta = historial.id_venta', 'left')
                                                ->where(['id_historial' => $this->input->post('id_historial')])
                                                ->get();
            //Cuanto se ha pagado en comisiones
            $comisionado = $this->Historial_model->select("SUM(historial.comision) AS comisionado")
                                                 ->where(['id_venta' =>$historial[0]->id_venta])
                                                 ->where(['estado' =>1])
                                                 ->get();
            $historial[0]->comisionado = $comisionado[0]->comisionado;
            echo json_encode($historial[0]);
        }
    }
    public function get_comision_per_lider(){
        $response = new stdClass();
        $lideres = $this->Venta_model->select(" users.id, 
                                                IF(users.last_name != NULL OR users.last_name != '',
                                                    CONCAT(users.first_name, ' ' , users.last_name),
                                                    users.first_name) AS nombre,
                                                SUM(IF(ventas.estado = 0 || ventas.estado = 1, ventas.comision , 0)) AS comision")                                  
                                  ->join('users','ventas.id_lider = users.id','left')
                                  /*->where('ventas.estado = 0 OR ventas.estado = 1 OR ventas.estado = 2')*/
                                  ->group_by('ventas.id_lider')
                                  ->get();
        
        foreach($lideres as $key => $lider){
            /*$comisiones_pendientes = $this->Historial_model->select("
                                                            SUM(historial.pago * (ventas.porcentaje_comision/100)) AS comisiones_pendientes
                                                            ")
                                                           ->join('ventas','historial.id_venta = ventas.id_venta','left')
                                                           ->where([
                                                               'historial.id_lider' => $lider->id,
                                                               'historial.estado' => 1,
                                                               'historial.comision' => 0,
                                                           ])
                                                           ->where('ventas.estado = 0 OR ventas.estado = 1 OR ventas.estado = 2')
                                                           ->get();
            $comisiones_pagadas = $this->Historial_model->select("
                                                            SUM(historial.comision) AS comisiones_pagadas
                                                            ")
                                                           ->join('ventas','historial.id_venta = ventas.id_venta','left')
                                                           ->where([
                                                               'historial.id_lider' => $lider->id,
                                                               'historial.comision >' => 0,
                                                              
                                                           ])
                                                           ->where('ventas.estado = 0 OR ventas.estado = 1 OR ventas.estado = 2')
                                                           ->get();*/
            $lideres[$key]->comisiones_pendientes = 0; //$comisiones_pendientes[0]->comisiones_pendientes; 
            $lideres[$key]->comisiones_pagadas = 0;//$comisiones_pagadas[0]->comisiones_pagadas; 
        }
        $response->data = $lideres;
        echo json_encode($response);
    }
    public function pagar()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->post('id_historial') &&
           $this->input->post('id_ingreso')) {
            if($this->input->post('folio')){
                $folio_repetido = $this->Historial_model->select('  historial.concepto,
                                                                    DATE_FORMAT(historial.fecha_pago,"%d-%m-%Y") AS fecha,
                                                                    CONCAT(users.first_name," ",users.last_name) AS nombre_cliente')
                                                        ->join('ventas','historial.id_venta = ventas.id_venta','left')
                                                        ->join('users','ventas.id_cliente = users.id','left')
                                                        ->where(['historial.folio' => $this->input->post('folio')])
                                                        ->get(); 
                $n_folios_repeditos = count($folio_repetido);
                if($n_folios_repeditos){
                    echo "Existe un folio de banco duplicado, verifique la información. 
                    Folio duplicado: {$this->input->post('folio')}
                    Coincidencias: {$n_folios_repeditos}
                    Fecha :  {$folio_repetido[0]->fecha}
                    Cliente: {$folio_repetido[0]->nombre_cliente}";
                    exit();
                }
                
            }

            $fecha = Carbon::createFromFormat('d-m-Y', $this->input->post('fecha_pago'));
            $set_data = [
                'fecha_pago' => $fecha->format('Y-m-d'),
                'pago' => $this->input->post('pago'),
                'id_ingreso' => $this->input->post('id_ingreso'),
                'folio' => $this->input->post('folio'),
                'estado' => 1,
            ];
            if ($this->input->post('confirm_comision') == 'true') {
                $set_data['comision'] = $this->input->post('comision');
                $set_data['id_lider'] = $this->input->post('id_lider');
            }
            if ($this->input->post('confirm_penalizacion') == 'true') {
                $set_data['penalizacion'] = $this->input->post('penalizacion');
            }
            $this->Trans_model->begin();
            $n_updated_pago = $this->Historial_model->where(['id_historial' => $this->input->post('id_historial')])
                                  ->update($set_data)
                                  ->affected_rows();
            $id_venta = $this->Historial_model->select('id_venta')
                                              ->where(['id_historial' => $this->input->post('id_historial')])
                                              ->get();
            $pago_final = $this->Historial_model->select('IF(SUM(historial.abono)  = SUM(historial.pago),1,0 ) as pago_final')
                                                ->where(['id_venta' => $id_venta[0]->id_venta])
                                                ->get();
            $estado_venta = $this->Historial_model->select('ventas.estado')
                                                  ->join('ventas','historial.id_venta = ventas.id_venta','left')
                                                  ->where(['id_historial' => $this->input->post('id_historial')])
                                                  ->get();
            if($estado_venta[0]->estado != 2 || $estado_venta[0]->estado != 3) {
                if ($pago_final[0]->pago_final) {
                    $huertos = $this->HuertosVentas_model->select('id_huerto, 3 AS vendido')
                                                        ->where(['id_venta' => $id_venta[0]->id_venta])
                                                        ->get();
                    $this->Huerto_model->update_batch($huertos, 'id_huerto');
                }
                if ($n_updated_pago == 1) {
                    $updated_pago = $this->Historial_model->select('historial.id_historial,
                                                                    CONCAT(cliente.first_name," ",cliente.last_name) AS nombre_cliente,
                                                                    CONCAT(lider.first_name," ",lider.last_name) AS nombre_lider,
                                                                    IF( opciones_ingreso.id_opcion_ingreso != 1, 
                                                                        CONCAT(opciones_ingreso.nombre, " - " ,opciones_ingreso.cuenta),
                                                                        opciones_ingreso.nombre) as nombre,
                                                                    historial.concepto,
                                                                    historial.abono,
                                                                    DATE_FORMAT(historial.fecha,"%d-%m-%Y") as fecha,
                                                                    historial.estado,
                                                                    DATE_FORMAT(historial.fecha_pago,"%d-%m-%Y") as fecha_pago, 
                                                                    IF( historial.estado = 0 , DATEDIFF( CURRENT_DATE() , historial.fecha ) , DATEDIFF( historial.fecha_pago ,historial.fecha ) ) as daysAccumulated,
                                                                    historial.pago,
                                                                    historial.comision,
                                                                    historial.penalizacion,
                                                                    (historial.pago + historial.penalizacion - historial.comision) as total')
                                                        ->join('ventas', 'historial.id_venta = ventas.id_venta', 'left')
                                                        ->join('users AS cliente', 'ventas.id_cliente = cliente.id', 'left')
                                                        ->join('users AS lider', 'historial.id_lider = lider.id', 'left')
                                                        ->join('opciones_ingreso', 'historial.id_ingreso = opciones_ingreso.id_opcion_ingreso', 'left')
                                                        ->where(['id_historial' => $this->input->post('id_historial')])
                                                        ->get();
                    $detalles = "";
                    foreach ($updated_pago as $key => $ingreso) {
                        if ($ingreso->estado == 0) {
                            $fecha = Carbon::createFromFormat('d-m-Y', $ingreso->fecha);
                            $today = Carbon::createFromFormat('Y-m-d', Carbon::today()->format('Y-m-d'));
                            $updated_pago[$key]->diff = $fecha->diff($today);
                        } else {
                            $fecha = Carbon::createFromFormat('d-m-Y', $ingreso->fecha);
                            $fecha_pago = Carbon::createFromFormat('d-m-Y', $ingreso->fecha_pago);
                            $updated_pago[$key]->diff = $fecha->diff($fecha_pago);
                        }
                    }
                    foreach ($updated_pago as $pago) {
                        if ($pago->daysAccumulated > 0) {
                            $detalles .= 'Realizó el pago con un retraso de: ' . $pago->diff->format('%y año(s) %m mes(es) y %d día(s)');
                        } elseif ($pago->daysAccumulated == 0) {
                            $detalles .= 'Pagado en tiempo.';
                        } elseif ($pago->daysAccumulated < 0) {
                            $detalles .= 'Pagado por adelantado. Con: ' . $pago->diff->format('%y año(s) %m mes(es) y %d día(s)');
                        }
                        $detalles .= '<div>Pago: $' . number_format($pago->pago, 2) .'</div>';
                        $detalles .= '<div>Deposito en: ' . $pago->nombre .'</div>';
                        $detalles .= '<div>Fecha: ' . $pago->fecha_pago .'</div>';
                        $detalles .= '<div>Comisión: $' . number_format($pago->comision, 2) .'</div>';
                        $detalles .= '<div>Penalización: $' . number_format($pago->penalizacion, 2) .'</div>';
                        $detalles .= '<div>Total: $' . number_format($pago->total, 2) .'</div>';
                    }
                    $updated_pago[0]->estado = ($updated_pago[0]->estado == 0) ? 'Pendiente' : 'Pagado';
                    $updated_pago[0]->detalles = $detalles;
                    $updated_pago[0]->abono = '$' . number_format($updated_pago[0]->abono, 2);
                    $updated_pago[0]->is_admin = $this->ion_auth->in_group('administrador');
                    echo json_encode($updated_pago[0]);
                }
                if ($this->Trans_model->status() === false) {
                    $this->Trans_model->rollback();
                    echo "Error fatal";
                } else {
                    $this->Trans_model->commit();
                }
            }else{
                 echo "Error: No es posible actualizar una venta cancelada";
            }
        }
    }
    public function remover_pago()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->post('id_historial')) {
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
            $estado_venta = $this->Historial_model->select('ventas.estado')
                                                  ->join('ventas','historial.id_venta = ventas.id_venta','left')
                                                  ->where(['id_historial' => $this->input->post('id_historial')])
                                                  ->get();
            if($estado_venta[0]->estado != 2 || $estado_venta[0]->estado != 3) {
                $this->Trans_model->begin();
                $n_updated_pago = $this->Historial_model->where(['id_historial' => $this->input->post('id_historial')])
                                    ->update($set_data)
                                    ->affected_rows();
                $id_venta = $this->Historial_model->select('id_venta')
                                                ->where(['id_historial' => $this->input->post('id_historial')])
                                                ->get();
                $pago_final = $this->Historial_model->select('IF(SUM(historial.abono)  > SUM(historial.pago),1,0 ) as pago_final')
                                                    ->where(['id_venta' => $id_venta[0]->id_venta])
                                                    ->get();
                                                    
                if ($pago_final[0]->pago_final) {
                    $huertos = $this->HuertosVentas_model->select('id_huerto, 1 AS vendido')
                                                        ->where(['id_venta' => $id_venta[0]->id_venta])
                                                        ->get();
                    $this->Huerto_model->update_batch($huertos, 'id_huerto');
                }
                if ($n_updated_pago == 1) {
                    $updated_pago = $this->Historial_model->select('historial.id_historial,
                                                                    CONCAT(cliente.first_name," ",cliente.last_name) AS nombre_cliente,
                                                                    CONCAT(lider.first_name," ",lider.last_name) AS nombre_lider,
                                                                    IF( opciones_ingreso.id_opcion_ingreso != 1, 
                                                                        CONCAT(opciones_ingreso.nombre, " - " ,opciones_ingreso.cuenta),
                                                                        opciones_ingreso.nombre) as nombre,
                                                                    historial.concepto,
                                                                    historial.abono,
                                                                    DATE_FORMAT(historial.fecha,"%d-%m-%Y") AS fecha,
                                                                    historial.estado,
                                                                    DATE_FORMAT(historial.fecha_pago,"%d-%m-%Y") AS fecha_pago, 
                                                                    IF( historial.estado = 0 , DATEDIFF( CURRENT_DATE() , historial.fecha ) , DATEDIFF( historial.fecha_pago ,historial.fecha ) ) as daysAccumulated,
                                                                    historial.pago,
                                                                    historial.comision,
                                                                    historial.penalizacion,
                                                                    (historial.pago + historial.penalizacion - historial.comision) as total')
                                                        ->join('ventas', 'historial.id_venta = ventas.id_venta', 'left')
                                                        ->join('users AS cliente', 'ventas.id_cliente = cliente.id', 'left')
                                                        ->join('users AS lider', 'historial.id_lider = lider.id', 'left')
                                                        ->join('opciones_ingreso', 'historial.id_ingreso = opciones_ingreso.id_opcion_ingreso', 'left')
                                                        ->where(['id_historial' => $this->input->post('id_historial')])
                                                        ->get();
                    $detalles = "";
                    foreach ($updated_pago as $key => $ingreso) {
                        if ($ingreso->estado == 0) {
                            $fecha = Carbon::createFromFormat('d-m-Y', $ingreso->fecha);
                            $today = Carbon::createFromFormat('Y-m-d', Carbon::today()->format('Y-m-d'));
                            $updated_pago[$key]->diff = $fecha->diff($today);
                        } else {
                            $fecha = Carbon::createFromFormat('d-m-Y', $ingreso->fecha);
                            $fecha_pago = Carbon::createFromFormat('d-m-Y', $ingreso->fecha_pago);
                            $updated_pago[$key]->diff = $fecha->diff($fecha_pago);
                        }
                    }
                    foreach ($updated_pago as $pago) {
                        if ($pago->daysAccumulated > 0) {
                            $detalles.= 'Tiene un retraso en pago de: ' . $pago->diff->format('%y año(s) %m mes(es) y %d día(s)');
                        } elseif ($pago->daysAccumulated == 0) {
                            $detalles.= 'Hoy es día de pago.';
                        } else {
                            $detalles.= 'Aun no es fecha de pago. Faltan: '. $pago->diff->format('%y año(s) %m mes(es) y %d día(s)');
                        }
                    }
                    $updated_pago[0]->estado = ($updated_pago[0]->estado == 0) ? 'Pendiente' : 'Pagado';
                    $updated_pago[0]->detalles = $detalles;
                    $updated_pago[0]->abono = '$' . number_format($updated_pago[0]->abono, 2);
                    echo json_encode($updated_pago[0]);
                }
                if ($this->Trans_model->status() === false) {
                    $this->Trans_model->rollback();
                    echo "Error fatal";
                } else {
                    $this->Trans_model->commit();
                }
            } else {
                echo "Error: no se puede remover el pago de una venta cancelada.";
            }
        }
    }
    public function pagar_comision()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->post('id_historial')) {
            $set_data = [
                'comision' => $this->input->post('comision'),
                'id_lider' => $this->input->post('id_lider'),
            ];
            $estado_venta = $this->Historial_model->select('ventas.estado')
                                                  ->join('ventas','historial.id_venta = ventas.id_venta','left')
                                                  ->where(['id_historial' => $this->input->post('id_historial')])
                                                  ->get();
            if($estado_venta[0]->estado != 2 || $estado_venta[0]->estado != 3) {
                $n_updated_pago = $this->Historial_model->where(['id_historial' => $this->input->post('id_historial')])
                                    ->update($set_data)
                                    ->affected_rows();
                if ($n_updated_pago == 1) {
                    $updated_pago = $this->Historial_model->select(' historial.id_historial,
                                                                    CONCAT(cliente.first_name," ",cliente.last_name) AS nombre_cliente,
                                                                    CONCAT(lider.first_name," ",lider.last_name) AS nombre_lider,
                                                                    IF( opciones_ingreso.id_opcion_ingreso != 1, 
                                                                        CONCAT(opciones_ingreso.nombre, " - " ,opciones_ingreso.cuenta),
                                                                        opciones_ingreso.nombre) as nombre,
                                                                    historial.concepto,
                                                                    historial.abono,
                                                                    DATE_FORMAT(historial.fecha,"%d-%m-%Y") AS fecha,
                                                                    historial.estado,
                                                                    DATE_FORMAT(historial.fecha_pago,"%d-%m-%Y") AS fecha_pago, 
                                                                    IF( historial.estado = 0 , DATEDIFF( CURRENT_DATE() , historial.fecha ) , DATEDIFF( historial.fecha_pago ,historial.fecha ) ) as daysAccumulated,
                                                                    historial.pago,
                                                                    historial.comision,
                                                                    historial.penalizacion,
                                                                    (historial.pago + historial.penalizacion - historial.comision) as total')
                                                        ->join('ventas', 'historial.id_venta = ventas.id_venta', 'left')
                                                        ->join('users AS cliente', 'ventas.id_cliente = cliente.id', 'left')
                                                        ->join('users AS lider', 'historial.id_lider = lider.id', 'left')
                                                        ->join('opciones_ingreso', 'historial.id_ingreso = opciones_ingreso.id_opcion_ingreso', 'left')
                                                        ->where(['id_historial' => $this->input->post('id_historial')])
                                                        ->get();
                    $detalles = "";
                    foreach ($updated_pago as $key => $ingreso) {
                        if ($ingreso->estado == 0) {
                            $fecha = Carbon::createFromFormat('d-m-Y', $ingreso->fecha);
                            $today = Carbon::createFromFormat('Y-m-d', Carbon::today()->format('Y-m-d'));
                            $updated_pago[$key]->diff = $fecha->diff($today);
                        } else {
                            $fecha = Carbon::createFromFormat('d-m-Y', $ingreso->fecha);
                            $fecha_pago = Carbon::createFromFormat('d-m-Y', $ingreso->fecha_pago);
                            $updated_pago[$key]->diff = $fecha->diff($fecha_pago);
                        }
                    }
                    foreach ($updated_pago as $pago) {
                        if ($pago->daysAccumulated > 0) {
                            $detalles .= 'Realizó el pago con un retraso de: ' . $pago->diff->format('%y año(s) %m mes(es) y %d día(s)');
                        } elseif ($pago->daysAccumulated == 0) {
                            $detalles .= 'Pagado en tiempo.';
                        } elseif ($pago->daysAccumulated < 0) {
                            $detalles .= 'Pagado por adelantado. Con: ' . $pago->diff->format('%y año(s) %m mes(es) y %d día(s)');
                        }
                        $detalles .= '<div>Pago: $' . number_format($pago->pago, 2) .'</div>';
                        $detalles .= '<div>Deposito en: ' . $pago->nombre .'</div>';
                        $detalles .= '<div>Fecha: ' . $pago->fecha_pago .'</div>';
                        $detalles .= '<div>Comisión: $' . number_format($pago->comision, 2) .'</div>';
                        $detalles .= '<div>Penalización: $' . number_format($pago->penalizacion, 2) .'</div>';
                        $detalles .= '<div>Total: $' . number_format($pago->total, 2) .'</div>';
                    }
                    $updated_pago[0]->estado = ($updated_pago[0]->estado == 0) ? 'Pendiente' : 'Pagado';
                    $updated_pago[0]->detalles = $detalles;
                    $updated_pago[0]->abono = '$' . number_format($updated_pago[0]->abono, 2);
                    $updated_pago[0]->is_admin = $this->ion_auth->in_group('administrador');
                    echo json_encode($updated_pago[0]);
                }
            } else {
                 echo "Error: no es posible actualizar una venta cancelada";
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
    public function autocomplete_saldos_clientes(){
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->get('query')) {
            $like = $this->input->get('query');
            $full_name = "CONCAT(users.first_name,' ',users.last_name)";
            $select = " CONCAT({$full_name}) AS data, 
                        CONCAT({$full_name}) AS value,
                        users.id AS id_cliente,
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
						DATE_FORMAT(users.fecha_nacimiento,'%d-%m-%Y') as fecha_nacimiento,                       
                        ventas.testigo_1 AS testigo_1,
                        ventas.testigo_2 AS testigo_2,
                        ventas.ciudad_expedicion,
                        SUM( IF( historial.estado = 1,(historial.pago + historial.penalizacion), 0 ) ) AS pagado,
                        ventas.id_venta";
            //$clientes = $this->ion_auth->select("{$select},{$full_name} AS data, {$full_name} AS value")->where(["{$full_name} LIKE" => "%{$like}%",'active' => 1])->users('cliente')->result();
            $clientes = $this->Venta_model->select($select)
                                          ->join('users','ventas.id_cliente = users.id','left')
                                          ->join('historial','ventas.id_venta = historial.id_venta','left')
                                          ->where(['ventas.estado' => 2])
                                          ->where(["{$full_name} LIKE" => "%{$like}%",'users.active' => 1])
                                          ->group_by('ventas.id_venta')
                                          ->get();
            foreach ($clientes as $key => $cliente) {
                $descripcion = $this->Venta_model->select('GROUP_CONCAT("Mz. ",manzanas.manzana, " Ht. ", huertos.huerto) as descripcion')
                                                ->join('huertos_ventas', 'ventas.id_venta = huertos_ventas.id_venta', 'inner')
                                                ->join('huertos', 'huertos_ventas.id_huerto = huertos.id_huerto', 'inner')
                                                ->join('manzanas', 'huertos.id_manzana = manzanas.id_manzana', 'inner')
                                                ->where(['ventas.id_venta' => $cliente->id_venta])
                                                ->get();

                 $clientes[$key]->value = $clientes[$key]->value . ' - <strong>$' . number_format($clientes[$key]->pagado,2).'</strong>';
                 $clientes[$key]->data = $clientes[$key]->data . ' - $' . number_format($clientes[$key]->pagado);
                
                 $clientes[$key]->value = $clientes[$key]->value . ' - <strong>' . $descripcion[0]->descripcion.'</strong>';
                 $clientes[$key]->data = $clientes[$key]->data . ' - ' . $descripcion[0]->descripcion;

                
            }
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
        if($this->cart->total() == 0){
            $this->cart->destroy();
        }
        $this->show_cart();
    }
    public function show_cart()
    {
        $respuesta = new stdClass();
        $respuesta->huertos = [];
        $respuesta->enganche = 0;
        $respuesta->abono = 0;
        /*var_dump($this->cart->contents());*/
        $respuesta->is_reserva = false;
        foreach ($this->cart->contents() as $items) {
            $obj = new stdClass();
            $obj->descripcion = $items["name"];
            $rowid = $items["rowid"];
            if($items["price"] != 0){
                $obj->btn = "<button class='btn btn-danger itemCartDelete' value='{$rowid}'><i class='fa fa-trash'></i></button>";
            }
            array_push($respuesta->huertos, $obj);
            $respuesta->enganche +=  $items["enganche"];
            $respuesta->abono +=  $items["abono"];
            
            if(isset($items['id_reserva'])){
                $respuesta->is_reserva = true;
                $respuesta->id_reserva = $items['id_reserva'];
                $respuesta->id_lider = $items['id_lider'];

                $respuesta->nombre_lider = $items['nombre_lider'];
                $respuesta->nombre_cliente = $items['nombre_cliente'];
                $respuesta->apellidos_cliente = $items['apellidos_cliente'];
                $respuesta->email_cliente = $items['email_cliente'];
                $respuesta->phone_cliente = $items['phone_cliente'];
                $respuesta->comment = $items['comment'];
            }
        }
        $respuesta->enganche = ($respuesta->enganche);
        $respuesta->abono = ($respuesta->abono);
        $respuesta->total = ($this->cart->total());
        $respuesta->count = $this->cart->total_items();
       
        if ($this->ion_auth->in_group('administrador') || $this->ion_auth->in_group('miembro')) {
            $respuesta->link = ($this->cart->total_items()>0) ? '<a href="'.base_url().'venta" class="btn btn-success center-block">Vender</a>' : '';
        } elseif ($this->ion_auth->in_group('lider')) {
            $respuesta->link = ($this->cart->total_items()>0) ? '<a href="'.base_url().'reserva" class="btn btn-warning center-block">Reservar</a>' : '';
        }

        echo json_encode($respuesta);
    }
    public function aplicar_reserva()
    {
        if ($this->input->is_ajax_request()) {
            header("Content-type: application/json; charset=utf-8");
            if ($this->input->post('id_reserva')) {
                $this->cart->destroy();
                $huertos_in_reservas = $this->Reserva_model->select("
                                                                     reservas.precio,
                                                                     reservas.enganche, 
                                                                     reservas.abono,
                                                                     huertos_reservas.id_huerto,
                                                                     reservas.id_reserva,
                                                                     reservas.id_lider,
                                                                     CONCAT(lider.first_name, ' ', lider.last_name) AS nombre_lider,
                                                                     reservas.first_name, 
                                                                     reservas.last_name,
                                                                     reservas.email,
                                                                     reservas.phone,
                                                                     reservas.comment")
                                               ->where(['reservas.id_reserva'=>$this->input->post('id_reserva')])
                                               ->join('huertos_reservas', 'reservas.id_reserva = huertos_reservas.id_reserva')
                                               ->join('users AS lider', 'reservas.id_lider = lider.id')
                                               ->get();
                $huertos_in_reserva = [];
                $precio = 0;
                $enganche = 0;
                $abono = 0;
                $id_reserva = 0;
                $id_lider = 0;
                $nombre_lider = "";
                $nombre_cliente = "";
                $apellidos_cliente = "";
                $email_cliente = "";
                $phone_cliente = "";
                $comment = "";
                /*var_dump($huertos_in_reservas);
                die();*/
                foreach ($huertos_in_reservas as $key => $huertos_in) {
                    if ($key == 0) {
                        $precio = $huertos_in->precio;
                        $enganche = $huertos_in->enganche;
                        $abono = $huertos_in->abono;

                        $id_reserva = $huertos_in->id_reserva;
                        $id_lider = $huertos_in->id_lider;
                        $nombre_lider = $huertos_in->nombre_lider;
                        $nombre_cliente = $huertos_in->first_name;
                        $apellidos_cliente = $huertos_in->last_name;
                        $email_cliente = $huertos_in->email;
                        $phone_cliente = $huertos_in->phone;
                        $comment = $huertos_in->comment;
                    }
                    array_push($huertos_in_reserva, $huertos_in->id_huerto);
                }
                $huertos = $this->Huerto_model->select('huertos.id_huerto,manzanas.id_manzana,huertos.huerto,manzanas.manzana')
                                              ->join("manzanas", "huertos.id_manzana = manzanas.id_manzana", 'left')
                                              ->where_in('id_huerto', $huertos_in_reserva)
                                              ->get();
                
                if (count($huertos)) {
                    foreach ($huertos as $key => $huerto) {
                        $data = array(
                            'id'      => 'huerto_'.$huerto->id_huerto,
                            'qty'     => 1,
                            'name'    => "Manzana: {$huerto->manzana}, Huerto: {$huerto->huerto}",
                            'id_huerto' => $huerto->id_huerto,
                            'id_manzana' => $huerto->id_manzana,
                            'manzana' => $huerto->manzana,
                            'huerto' => $huerto->huerto,                            
                        );
                        if ($key == 0) {
                            $data['price'] = (float) $precio;
                            $data['abono'] = (float) $abono;
                            $data['enganche'] = (float) $enganche;

                            $data['id_reserva'] = $id_reserva;
                            $data['id_lider'] = $id_lider;
                            $data['nombre_lider'] = $nombre_lider;
                            $data['nombre_cliente'] = $nombre_cliente;
                            $data['apellidos_cliente'] = $apellidos_cliente;
                            $data['email_cliente'] = $email_cliente;
                            $data['phone_cliente'] = $phone_cliente;
                            $data['comment'] = $comment;
                        } else {
                            $data['price'] = 0;
                            $data['abono'] = 0;
                            $data['enganche'] = 0;
                        }
                        $this->cart->insert($data);
                    }
                }
                echo json_encode(['status' => 200 ,'msg' => 'ok']);
            }
        } else {
            show_404(); //this is your login view file
        }
    }
    public function cancelar_venta()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->post("id_venta")) {
            $id_venta = $this->input->post("id_venta");
            $huertos = $this->Venta_model->select('huertos.id_huerto')
                              ->join('huertos_ventas', 'ventas.id_venta = huertos_ventas.id_venta', 'left')
                              ->join('huertos', 'huertos_ventas.id_huerto = huertos.id_huerto', 'left')
                              ->where(['ventas.id_venta' => $id_venta])
                              ->get();
            $this->Venta_model->where(['ventas.id_venta' => $id_venta])
                              ->update(['estado' => 2]);
            foreach ($huertos as $key => $huerto) {
                $huertos[$key]->vendido = 0;
            }
            $this->Huerto_model->update_batch($huertos, 'id_huerto');

            $venta = $this->Venta_model->select("ventas.id_venta, 
                                          ventas.version, 
                                          ventas.precio, 
										  ventas.comision, 
                                          ventas.porcentaje_comision,
                                          ventas.estado,
                                          SUM(IF(historial.estado = 0 && DATE(historial.fecha) <= NOW(),1,0)) AS retraso,
                                          SUM(IF(DATEDIFF( historial.fecha_pago ,historial.fecha) > 0 ,1,0)) AS retrasados,
                                          SUM(IF(DATEDIFF( historial.fecha_pago ,historial.fecha) < 0 ,1,0)) AS adelantados,
                                          SUM(IF(historial.estado = 1 && DATE(historial.fecha) = DATE(historial.fecha_pago),1,0)) AS en_tiempo,
                                          SUM(IF(historial.estado = 1,1,0)) AS realizados,
                                          SUM(historial.pago) AS pagado, 
                                          SUM(IF(historial.estado = 1,historial.comision,0)) AS comisionado,
                                          CONCAT(cliente.first_name,' ',cliente.last_name) AS nombre_cliente,
                                          cliente.phone,
                                          cliente.email,
                                          CONCAT(lider.first_name,' ',lider.last_name) AS nombre_lider,
                                          CONCAT(user.first_name,' ',user.last_name) AS nombre_user")
                                ->join('historial', 'ventas.id_venta = historial.id_venta', 'left')
                                ->join('users as cliente', 'ventas.id_cliente = cliente.id', 'left')
                                ->join('users as lider', 'ventas.id_lider = lider.id', 'left')
                                ->join('users as user', 'ventas.id_usuario = user.id', 'left')
                                ->where(['ventas.id_venta' => $id_venta])
                                ->get();
            foreach ($venta as $key => $v) {
                $descripcion = $this->Venta_model->select('GROUP_CONCAT("Mz. ",manzanas.manzana, " Ht. ", huertos.huerto) as descripcion')
                                                 ->join('huertos_ventas', 'ventas.id_venta = huertos_ventas.id_venta', 'inner')
                                                 ->join('huertos', 'huertos_ventas.id_huerto = huertos.id_huerto', 'inner')
                                                 ->join('manzanas', 'huertos.id_manzana = manzanas.id_manzana', 'inner')
                                                 ->where(['ventas.id_venta' => $v->id_venta])
                                                 ->get();
                $venta[$key]->descripcion = $descripcion[0]->descripcion;
                $venta[$key]->detalles = "Pagado en tiempo: {$v->en_tiempo} Pagado con retraso: {$v->retrasados} delantado: {$v->adelantados} Realizados: {$v->realizados}";
                if ($venta[$key]->estado == 0) {
                    $venta[$key]->detalles .= '<span class="label label-warning">En proceso de pago</span>';
                } elseif ($venta[$key]->estado == 1) {
                    $venta[$key]->detalles .= '<span class="label label-success">Saldado</span>';
                } elseif ($venta[$key]->estado == 2) {
                    $venta[$key]->detalles .= '<span class="label label-default">Cancelado</span>';
                } elseif ($venta[$key]->estado == 3) {
                    $venta[$key]->detalles .= '<span class="label label-danger">Eliminado</span>';
                } 
            }
            echo json_encode($venta[0]);
        }
    }
    public function activar_venta()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->post("id_venta")) {
            $this->Trans_model->begin();
            $id_venta = $this->input->post("id_venta");
            $huertos = $this->Venta_model->select('huertos.id_huerto')
                              ->join('huertos_ventas', 'ventas.id_venta = huertos_ventas.id_venta', 'left')
                              ->join('huertos', 'huertos_ventas.id_huerto = huertos.id_huerto', 'left')
                              ->where(['ventas.id_venta' => $id_venta])
                              ->get();
            $this->Venta_model->where(['ventas.id_venta' => $id_venta])
                              ->update(['estado' => 0]);
            foreach ($huertos as $key => $huerto) {
                $huertos[$key]->vendido = 1;
            }
            $huertos_updated = $this->Huerto_model->where(['vendido' => 0])
                                                  ->update_batch($huertos, 'id_huerto');
            $venta = $this->Venta_model->select("ventas.id_venta, 
                                          ventas.version, 
                                          ventas.precio, 
										  ventas.comision, 
                                          ventas.porcentaje_comision,
                                          ventas.estado,
                                          SUM(IF(historial.estado = 0 && DATE(historial.fecha) <= NOW(),1,0)) AS retraso,
                                          SUM(IF(DATEDIFF( historial.fecha_pago ,historial.fecha) > 0 ,1,0)) AS retrasados,
                                          SUM(IF(DATEDIFF( historial.fecha_pago ,historial.fecha) < 0 ,1,0)) AS adelantados,
                                          SUM(IF(historial.estado = 1 && DATE(historial.fecha) = DATE(historial.fecha_pago),1,0)) AS en_tiempo,
                                          SUM(IF(historial.estado = 1,1,0)) AS realizados,
                                          SUM(historial.pago) AS pagado, 
                                          SUM(IF(historial.estado = 1,historial.comision,0)) AS comisionado,
                                          CONCAT(cliente.first_name,' ',cliente.last_name) AS nombre_cliente,
                                          cliente.phone,
                                          cliente.email,
                                          CONCAT(lider.first_name,' ',lider.last_name) AS nombre_lider,
                                          CONCAT(user.first_name,' ',user.last_name) AS nombre_user")
                                ->join('historial', 'ventas.id_venta = historial.id_venta', 'left')
                                ->join('users as cliente', 'ventas.id_cliente = cliente.id', 'left')
                                ->join('users as lider', 'ventas.id_lider = lider.id', 'left')
                                ->join('users as user', 'ventas.id_usuario = user.id', 'left')
                                ->where(['ventas.id_venta' => $id_venta])
                                ->get();
            foreach ($venta as $key => $v) {
                $descripcion = $this->Venta_model->select('GROUP_CONCAT("Mz. ",manzanas.manzana, " Ht. ", huertos.huerto) as descripcion')
                                                 ->join('huertos_ventas', 'ventas.id_venta = huertos_ventas.id_venta', 'inner')
                                                 ->join('huertos', 'huertos_ventas.id_huerto = huertos.id_huerto', 'inner')
                                                 ->join('manzanas', 'huertos.id_manzana = manzanas.id_manzana', 'inner')
                                                 ->where(['ventas.id_venta' => $v->id_venta])
                                                 ->get();
                $venta[$key]->descripcion = $descripcion[0]->descripcion;
                $venta[$key]->detalles = "Pagado en tiempo: {$v->en_tiempo} Pagado con retraso: {$v->retrasados} delantado: {$v->adelantados} Realizados: {$v->realizados} ";
                if ($venta[$key]->estado == 0) {
                    $venta[$key]->detalles .= '<span class="label label-warning">En proceso de pago</span>';
                } elseif ($venta[$key]->estado == 1) {
                    $venta[$key]->detalles .= '<span class="label label-success">Saldado</span>';
                } elseif ($venta[$key]->estado == 2) {
                    $venta[$key]->detalles .= '<span class="label label-default">Cancelado</span>';
                } elseif ($venta[$key]->estado == 3) {
                    $venta[$key]->detalles .= '<span class="label label-danger">Eliminado</span>';
                } 
            }
            if ($this->Trans_model->status() === false) {
                $this->Trans_model->rollback();
                echo "<p>Error de transacción</p>";
            }else{
                if($huertos_updated == count($huertos)){
                    $this->Trans_model->commit();
                    $venta[0]->status = 200;
                    $venta[0]->msg_status = "Ok";
                    echo json_encode($venta[0]);
                }else{
                    $this->Trans_model->rollback();
                    $venta[0]->status = 400;
                    $venta[0]->msg_status = "Error, algún huerto ya ha sido reservado, verificar informacion";
                    echo json_encode($venta[0]);
                }
                
            }
            
        }
    }
    public function eliminar_venta()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->post("id_venta")) {
            $this->Trans_model->begin();
            $id_venta = $this->input->post("id_venta");
            $huertos = $this->Venta_model->select('huertos.id_huerto,huertos.vendido')
                              ->join('huertos_ventas', 'ventas.id_venta = huertos_ventas.id_venta', 'left')
                              ->join('huertos', 'huertos_ventas.id_huerto = huertos.id_huerto', 'left')
                              ->where(['ventas.id_venta' => $id_venta])
                              ->get();
            $this->Venta_model->where(['ventas.id_venta' => $id_venta])
                              ->update(['estado' => 3]);
            $update = true;
            foreach ($huertos as $key => $huerto) {
                if($huerto->vendido != 1){
                    $update = false;
                    break;
                }
                $huertos[$key]->vendido = 0;
            }
            if($update){
                $this->Huerto_model->update_batch($huertos, 'id_huerto');
            }
            
            $venta = $this->Venta_model->select("ventas.id_venta, 
                                          ventas.version, 
                                          ventas.precio, 
										  ventas.comision, 
                                          ventas.porcentaje_comision,
                                          ventas.estado,
                                          SUM(IF(historial.estado = 0 && DATE(historial.fecha) <= NOW(),1,0)) AS retraso,
                                          SUM(IF(DATEDIFF( historial.fecha_pago ,historial.fecha) > 0 ,1,0)) AS retrasados,
                                          SUM(IF(DATEDIFF( historial.fecha_pago ,historial.fecha) < 0 ,1,0)) AS adelantados,
                                          SUM(IF(historial.estado = 1 && DATE(historial.fecha) = DATE(historial.fecha_pago),1,0)) AS en_tiempo,
                                          SUM(IF(historial.estado = 1,1,0)) AS realizados,
                                          SUM(historial.pago) AS pagado, 
                                          SUM(IF(historial.estado = 1,historial.comision,0)) AS comisionado,
                                          CONCAT(cliente.first_name,' ',cliente.last_name) AS nombre_cliente,
                                          cliente.phone,
                                          cliente.email,
                                          CONCAT(lider.first_name,' ',lider.last_name) AS nombre_lider,
                                          CONCAT(user.first_name,' ',user.last_name) AS nombre_user")
                                ->join('historial', 'ventas.id_venta = historial.id_venta', 'left')
                                ->join('users as cliente', 'ventas.id_cliente = cliente.id', 'left')
                                ->join('users as lider', 'ventas.id_lider = lider.id', 'left')
                                ->join('users as user', 'ventas.id_usuario = user.id', 'left')
                                ->where(['ventas.id_venta' => $id_venta])
                                ->get();
            foreach ($venta as $key => $v) {
                $descripcion = $this->Venta_model->select('GROUP_CONCAT("Mz. ",manzanas.manzana, " Ht. ", huertos.huerto) as descripcion')
                                                 ->join('huertos_ventas', 'ventas.id_venta = huertos_ventas.id_venta', 'inner')
                                                 ->join('huertos', 'huertos_ventas.id_huerto = huertos.id_huerto', 'inner')
                                                 ->join('manzanas', 'huertos.id_manzana = manzanas.id_manzana', 'inner')
                                                 ->where(['ventas.id_venta' => $v->id_venta])
                                                 ->get();
                $venta[$key]->descripcion = $descripcion[0]->descripcion;
                $venta[$key]->detalles = "Pagado en tiempo: {$v->en_tiempo} Pagado con retraso: {$v->retrasados} delantado: {$v->adelantados} Realizados: {$v->realizados} ";
                if ($venta[$key]->estado == 0) {
                    $venta[$key]->detalles .= '<span class="label label-warning">En proceso de pago</span>';
                } elseif ($venta[$key]->estado == 1) {
                    $venta[$key]->detalles .= '<span class="label label-success">Saldado</span>';
                } elseif ($venta[$key]->estado == 2) {
                    $venta[$key]->detalles .= '<span class="label label-default">Cancelado</span>';
                } elseif ($venta[$key]->estado == 3) {
                    $venta[$key]->detalles .= '<span class="label label-danger">Eliminado</span>';
                } 
            }
            if ($this->Trans_model->status() === false) {
                $this->Trans_model->rollback();
                echo "<p>Error de transacción</p>";
            }else{
                $this->Trans_model->commit();
                echo json_encode($venta[0]);
            }
        }
    }
    public function recuperar_venta()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($this->input->post("id_venta")) {
            $id_venta = $this->input->post("id_venta");
            $this->Venta_model->where(['ventas.id_venta' => $id_venta])
                              ->update(['estado' => 2]);
            $venta = $this->Venta_model->select("ventas.id_venta, 
                                          ventas.version, 
                                          ventas.precio, 
										  ventas.comision, 
                                          ventas.porcentaje_comision,
                                          ventas.estado,
                                          SUM(IF(historial.estado = 0 && DATE(historial.fecha) <= NOW(),1,0)) AS retraso,
                                          SUM(IF(DATEDIFF( historial.fecha_pago ,historial.fecha) > 0 ,1,0)) AS retrasados,
                                          SUM(IF(DATEDIFF( historial.fecha_pago ,historial.fecha) < 0 ,1,0)) AS adelantados,
                                          SUM(IF(historial.estado = 1 && DATE(historial.fecha) = DATE(historial.fecha_pago),1,0)) AS en_tiempo,
                                          SUM(IF(historial.estado = 1,1,0)) AS realizados,
                                          SUM(historial.pago) AS pagado, 
                                          SUM(IF(historial.estado = 1,historial.comision,0)) AS comisionado,
                                          CONCAT(cliente.first_name,' ',cliente.last_name) AS nombre_cliente,
                                          cliente.phone,
                                          cliente.email,
                                          CONCAT(lider.first_name,' ',lider.last_name) AS nombre_lider,
                                          CONCAT(user.first_name,' ',user.last_name) AS nombre_user")
                                ->join('historial', 'ventas.id_venta = historial.id_venta', 'left')
                                ->join('users as cliente', 'ventas.id_cliente = cliente.id', 'left')
                                ->join('users as lider', 'ventas.id_lider = lider.id', 'left')
                                ->join('users as user', 'ventas.id_usuario = user.id', 'left')
                                ->where(['ventas.id_venta' => $id_venta])
                                ->get();
            foreach ($venta as $key => $v) {
                $descripcion = $this->Venta_model->select('GROUP_CONCAT("Mz. ",manzanas.manzana, " Ht. ", huertos.huerto) as descripcion')
                                                 ->join('huertos_ventas', 'ventas.id_venta = huertos_ventas.id_venta', 'inner')
                                                 ->join('huertos', 'huertos_ventas.id_huerto = huertos.id_huerto', 'inner')
                                                 ->join('manzanas', 'huertos.id_manzana = manzanas.id_manzana', 'inner')
                                                 ->where(['ventas.id_venta' => $v->id_venta])
                                                 ->get();
                $venta[$key]->descripcion = $descripcion[0]->descripcion;
                $venta[$key]->detalles = "Pagado en tiempo: {$v->en_tiempo} Pagado con retraso: {$v->retrasados} delantado: {$v->adelantados} Realizados: {$v->realizados} ";
                if ($venta[$key]->estado == 0) {
                    $venta[$key]->detalles .= '<span class="label label-warning">En proceso de pago</span>';
                } elseif ($venta[$key]->estado == 1) {
                    $venta[$key]->detalles .= '<span class="label label-success">Saldado</span>';
                } elseif ($venta[$key]->estado == 2) {
                    $venta[$key]->detalles .= '<span class="label label-default">Cancelado</span>';
                } elseif ($venta[$key]->estado == 3) {
                    $venta[$key]->detalles .= '<span class="label label-danger">Eliminado</span>';
                } 
            }
            echo json_encode($venta[0]);
        }
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
            foreach ($this->ion_auth->get_users_groups($idNewUser)->result() as $group) {
                $grupo.= '<li>'.$group->name.'</li>';
            }
            $grupo .= "<ul>";
            $newUser->groups = $grupo;
            $response = [];
            array_push($response, $newUser);
            echo json_encode($response);
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            echo $this->data['message'];
        }
    }
    public function update_ion_user()
    {
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

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            /*if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
            {
                show_error($this->lang->line('error_csrf'));
            }*/

            // update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
            }
            if ($this->input->post('email')) {
                $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[users.email]');
            }
            if ($this->form_validation->run() === true) {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name'  => $this->input->post('last_name'),
                    'company'    => $this->input->post('company'),
                    'phone'      => $this->input->post('phone'),
                );

                // update the password if it was posted
                if ($this->input->post('password')) {
                    $data['password'] = $this->input->post('password');
                }


                if ($this->input->post('email')) {
                    $data['email'] = strtolower($this->input->post('email'));
                    $data['username'] = strtolower($this->input->post('email'));
                }
                // Only allow updating groups if user is admin
                if ($this->ion_auth->is_admin()) {
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
                if ($this->ion_auth->update($user->id, $data)) {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    //$this->session->set_flashdata('message', $this->ion_auth->messages() );
                    $newUser = $this->ion_auth->user($user->id)->row();
                    $newUser->btn_activar_desactivar = '<a href="'.base_url().'auth/deactivate/'.$user->id.'" class="btn btn-success" data-target="#userModal" data-toggle="modal">Activo</a>';
                    $newUser->btn_editar = '<a href="'.base_url().'auth/edit_user/'.$user->id.'" class="btn btn-info" data-target="#userModal" data-toggle="modal" data-btn-type="edit">Editar Usuario</a>';
                    $grupo = "<ul>";
                    foreach ($this->ion_auth->get_users_groups($user->id)->result() as $group) {
                        $grupo.= '<li>'.$group->name.'</li>';
                    }
                    $grupo .= "<ul>";
                    $newUser->groups = $grupo;
                    $response = [];
                    array_push($response, $newUser);
                    echo json_encode($response);
                    /*if ($this->ion_auth->is_admin())
                    {
                        redirect('auth', 'refresh');
                    }
                    else
                    {
                        redirect('/', 'refresh');
                    }*/
                } else {
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
            } else {
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


        $this->_render_page('auth/edit_user', $this->data);
        */
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
        if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue')) {
            return true;
        } else {
            return false;
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
    public function opingreso_check($value, $request)
    {
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
                if (count($exist_cuenta_op_ingreso)) {
                    $this->form_validation->set_message('opingreso_check', 'Cuenta de ingreso ya existente.'); // set your message
                    return false;
                } else {
                    return true;
                }
            }
            if ($op_ingreso[0]->tarjeta == $this->input->post('tarjeta')) { //Si el huerto no ha cambiado
                return true;
            } else {
                $exist_cuenta_op_ingreso = $this->Opciones_ingreso_model->where(['tarjeta' => $this->input->post('tarjeta') ])->get();
                if (count($exist_cuenta_op_ingreso)) {
                    $this->form_validation->set_message('opingreso_check', 'Tarjeta de ingreso ya existente.'); // set your message
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            $this->form_validation->set_message('opingreso_check', 'Intenta actualizar una opcion de ingreso no existente.'); // set your message
            return false;
        }
    }
}
