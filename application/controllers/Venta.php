<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options as Options;
use zz\Html\HTMLMinify;

class Venta extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Contrato_model');
        $this->load->model('Opciones_ingreso_model');
        $this->load->model('Manzana_model');
        $this->load->model('Huerto_model');
        $this->load->model('Venta_model');
        $this->load->model('Historial_model');
        $this->load->model('HuertosVentas_model');
        $this->load->model('Trans_model');
    }
    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else {
            $data['title'] = "Venta";
            $data['body'] = "venta";
            $fecha = Carbon::now();
            $data['fecha'] = $fecha->format('d-m-Y');
            $data['ingresos'] = $this->Opciones_ingreso_model->get();
            $this->load->view('templates/template', $data);
        }
    }
    public function historial_de_ventas()
    {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else {
            $data['title'] = "Historial de ventas";
            $data['body'] = "historial_ventas";

            $venta = $this->Venta_model;
            if ($this->session->flashdata('id_venta')) {
                $venta->where(['ventas.id_venta' => $this->session->flashdata('id_venta')]);
            }
            $data['ventas'] = $venta->select("ventas.id_venta,
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
                                    ->where(['ventas.estado !=' => 4])
                                    ->group_by('ventas.id_venta')
                                    ->get();
            foreach ($data['ventas'] as $key => $venta) {
                $descripcion = $this->Venta_model->select('GROUP_CONCAT("Mz. ",manzanas.manzana, " Ht. ", huertos.huerto) as descripcion')
                                                ->join('huertos_ventas', 'ventas.id_venta = huertos_ventas.id_venta', 'inner')
                                                ->join('huertos', 'huertos_ventas.id_huerto = huertos.id_huerto', 'inner')
                                                ->join('manzanas', 'huertos.id_manzana = manzanas.id_manzana', 'inner')
                                                ->where(['ventas.id_venta' => $venta->id_venta])
                                                ->order_by('manzanas.manzana ASC, huertos.huerto ASC')
                                                ->get();
                $data['ventas'][$key]->descripcion = $descripcion[0]->descripcion;
            }
            $this->load->view('templates/template', $data);
        }
    }
    public function generar_contrato()
    {
        //Obtener datos de la manzanas
        $datosMz = new stdClass();
        $datosMz->id_mz = [];
        $datosMz->mz = [];
        $datosMz->huertos = [];
        //Obtener número de huertos
        $n_ht = 0;
        foreach ($this->cart->contents() as $items) {
            $where = ['id_huerto' => $items['id_huerto']];
            $huertos = $this->Huerto_model->getHuertosPM($where, 'object');
            foreach ($huertos as $key => $huerto) {
                if (!in_array($huerto->id_manzana, $datosMz->id_mz)) {
                    array_push($datosMz->id_mz, $huerto->id_manzana);
                }
            }
            $n_ht++;
        }
        $multiple_mz = (count($datosMz->id_mz)>1) ? true : false;
        //Obtener las colindancias de los lotes
        $colindancias_mz = "";
        $superficie_mz = 0;
        $colindancias = "";
        foreach ($datosMz->id_mz as $id) {
            $where = ['id_manzana' => $id];
            $manzanas = $this->Manzana_model->get('*', $where);
            foreach ($manzanas as $manzana) {
                if (!in_array($manzana->manzana, $datosMz->mz)) {
                    array_push($datosMz->mz, $manzana->manzana);
                }
                if (!empty($manzana->col_norte)) {
                    $colindancias .= "<strong>Al Norte</strong>, {$manzana->col_norte}; ";
                }
                if (!empty($manzana->col_noreste)) {
                    $colindancias .= "<strong>Al Noreste</strong>, {$manzana->col_noreste}; ";
                }
                if (!empty($manzana->col_este)) {
                    $colindancias .= "<strong>Al Este</strong>, {$manzana->col_este}; ";
                }
                if (!empty($manzana->col_sureste)) {
                    $colindancias .= "<strong>Al Sureste</strong>, {$manzana->col_sureste}; ";
                }
                if (!empty($manzana->col_sur)) {
                    $colindancias .= "<strong>Al Sur</strong>, {$manzana->col_sur}; ";
                }
                if (!empty($manzana->col_suroeste)) {
                    $colindancias .= "<strong>Al Suroeste</strong>, {$manzana->col_suroeste}; ";
                }
                if (!empty($manzana->col_noroeste)) {
                    $colindancias .= "<strong>Al Noroeste</strong>, {$manzana->col_noroeste}; ";
                }
                $superficie_mz = number_format($manzana->superficie, 2);
                $colindancias.= " con una Superficie total de {$superficie_mz} M<sup>2</sup>";
                if (!$multiple_mz) {
                    $colindancias_mz = $colindancias;
                } else {
                    $colindancias_mz .= "<div><strong>MANZANA {$manzana->manzana}</strong> {$colindancias}</div>";
                    $colindancias = "";
                }
            }
        }
        //Manzanas a texto
        $mz_txt = implode(',', $datosMz->mz);
        //Obtener la colindancias de los huertos
        $colindancias = "";
        $colindancias_ht = "";
        $superficie_ht = 0;
        foreach ($this->cart->contents() as $items) {
            $where = ['id_huerto' => $items['id_huerto']];
            $huertos = $this->Huerto_model->getHuertosPM($where, 'object');

            foreach ($huertos as $key => $huerto) {
                $superficie_ht = number_format($huerto->superficie, 2);
                $colindancias .= "el<strong>&nbsp;huerto {$huerto->huerto}</strong> con una superficie de {$superficie_ht} M<sup>2</sup>, con las Medidas y Colindancias Siguientes: ";
                if (!empty($huerto->col_norte)) {
                    $colindancias .= "<strong>Al Norte</strong>, {$huerto->col_norte}; ";
                }
                if (!empty($huerto->col_noreste)) {
                    $colindancias .= "<strong>Al Noreste</strong>, {$huerto->col_noreste}; ";
                }
                if (!empty($huerto->col_este)) {
                    $colindancias .= "<strong>Al Este</strong>, {$huerto->col_este}; ";
                }
                if (!empty($huerto->col_sureste)) {
                    $colindancias .= "<strong>Al Sureste</strong>, {$huerto->col_sureste}; ";
                }
                if (!empty($huerto->col_sur)) {
                    $colindancias .= "<strong>Al Sur</strong>, {$huerto->col_sur}; ";
                }
                if (!empty($huerto->col_suroeste)) {
                    $colindancias .= "<strong>Al Suroeste</strong>, {$huerto->col_suroeste}; ";
                }
                if (!empty($huerto->col_noroeste)) {
                    $colindancias .= "<strong>Al Noroeste</strong>, {$huerto->col_noroeste}; ";
                }
            }

            if (!$multiple_mz) {
                //Multiples huertos
                if ($n_ht > 1) {
                    $colindancias_ht .= "<div>{$colindancias}</div>";
                    $colindancias = "";
                } else {
                    $colindancias_ht = $colindancias;
                }
            } else {
                $colindancias_ht .= "<div><strong>EN MANZANA {$huerto->manzana} </strong>{$colindancias}</div>";
                $colindancias = "";
            }
        }
        $fecha_init = $this->input->post('fecha_init');
        $precio = $this->input->post('precio');
        $enganche = $this->input->post('enganche');
        $abono = $this->input->post('abono');
        $tipo_historial = $this->input->post('tipo_historial');
        if ($this->input->post('n_pago')) {
            $historial = new Historial($precio, $enganche, $abono, $fecha_init, $tipo_historial);
        } else {
            $historial = new Historial($precio, $enganche, $abono, $fecha_init, $tipo_historial);
        }

        $historial_pagos = "";
        $historial_pagos .= "<table class='pagares-tabla'>";
        $historial_pagos .=    "<thead>";
        $historial_pagos .=         "<tr>";
        $historial_pagos .=             "<th>Concepto</th>";
        $historial_pagos .=             "<th>Monto a pagar</th>";
        $historial_pagos .=             "<th>Fecha de pago</th>";
        $historial_pagos .=             "<th>Concepto</th>";
        $historial_pagos .=             "<th>Monto a pagar</th>";
        $historial_pagos .=             "<th>Fecha de pago</th>";
        $historial_pagos .=         "</tr>";
        $historial_pagos .=    "</thead>";
        $historial_pagos .=    "<tbody>";
        $size_history = count($historial->getHistorial());
        $size_history_mid = (int) ($size_history/2);
        $complemento = true;
        foreach ($historial->getHistorial() as $key => $pago) {
            if ($key == 1) {
                $historial->setFechaPrimerPago($pago->getFecha()->format('d-m-Y'));
            } else {
                $historial->setFechaUltimoPago($pago->getFecha()->format('d-m-Y'));
            }
            $abonos = number_format($pago->getAbono(), 2);
            if ($key % 2 == 0) {
                $historial_pagos .=     "<tr>";
                $historial_pagos .=         "<td>{$pago->getConcepto()}</td>";
                $historial_pagos .=         "<td>$ {$abonos}</td>";
                $historial_pagos .=         "<td>{$pago->getFecha()->format('d-m-Y')}</td>";
                $complemento = true;
            } else {
                $historial_pagos .=         "<td>{$pago->getConcepto()}</td>";
                $historial_pagos .=         "<td>$ {$abonos}</td>";
                $historial_pagos .=         "<td>{$pago->getFecha()->format('d-m-Y')}</td>";
                $historial_pagos .=     "</tr>";
                $complemento = false;
            }
        }
        if ($complemento) {
            $historial_pagos .=         "<td>&nbsp;</td>";
            $historial_pagos .=         "<td>&nbsp;</td>";
            $historial_pagos .=         "<td>&nbsp;</td>";
            $historial_pagos .=     "</tr>";
        }
        $pagado = number_format($historial->getPagado(), 2);
        $historial_pagos .=    "</tbody>";
        $historial_pagos .=    "<tfoot>";
        $historial_pagos .=         "<tr>";
        $historial_pagos .=             "<td>&nbsp;</td>";
        $historial_pagos .=             "<td>&nbsp;</td>";
        $historial_pagos .=             "<td>&nbsp;</td>";
        $historial_pagos .=             "<td><strong>TOTAL:</strong></td>";
        $historial_pagos .=             "<td>$ {$pagado}</td>";
        $historial_pagos .=             "<td>&nbsp;</td>";
        $historial_pagos .=         "</tr>";
        $historial_pagos .=    "</tfoot>";
        $historial_pagos .="</table>";
        //Una manzana manzana
        if (!$multiple_mz) {
            $complemento_manzana_ii = ', que a continuación se describe';
            $complemento_manzana_v = 'motivo de la Presente Operación, no ha sido Cedida, Vendida o Comprometida en forma con anterioridad a este Acto';
            $fraccion = 'la fracción marcada';
            $numero_manzana = "el Número {$mz_txt}";
            $manzana_txt = "LA MANZANA {$mz_txt}";
        } else {
            $complemento_manzana_ii = ', que a continuación se describen';
            $complemento_manzana_v = 'motivos de la Presente Operación, no han sido Cedidas, Vendidas o Comprometidas en forma con anterioridad a este Acto';
            $fraccion = 'las fracciones marcadas';
            $numero_manzana = "los Números {$mz_txt}"; //str_replace la penúltima coma para poner una y
            $manzana_txt = "LAS MANZANAS {$mz_txt}"; //str_replace la última coma para poner una y
        }

        $nombre_cliente = ucwords($this->input->post('first_name') . " " .$this->input->post('last_name'));
        $domicilio_cliente = ($this->input->post('calle')) ? "Calle: ". ucwords($this->input->post('calle')) : "";
        $domicilio_cliente .= ($this->input->post('no_ext')) ? " No. ext: ".$this->input->post('no_ext') : "";
        $domicilio_cliente .= ($this->input->post('no_int')) ? " No. int: ".$this->input->post('no_int') : "";
        $domicilio_cliente .= ($this->input->post('colonia')) ? " Colonia: ".ucwords($this->input->post('colonia')) : "";
        $domicilio_cliente .= ($this->input->post('municipio')) ? " Municipio: ".ucwords($this->input->post('municipio')) : "";
        $domicilio_cliente .= ($this->input->post('estado')) ? " Estado: ".ucwords($this->input->post('estado')) : "";
        $domicilio_cliente .= ($this->input->post('ciudad')) ? " Ciudad: ".ucwords($this->input->post('ciudad')) : "";
        $domicilio_cliente .= ($this->input->post('cp')) ? " Codigo postal: ".$this->input->post('cp') : "";
        $ciudad = $this->input->post('ciudad_expedicion');
        $nacimiento_cliente = ucwords($this->input->post('lugar_nacimiento'))." el <span id='fecha_init_5' class='fecha_txt'>".$this->input->post('fecha_nacimiento')."</span>";
        $testigo_1 = ucwords($this->input->post('testigo_1'));
        $testigo_2 = ucwords($this->input->post('testigo_2'));
        $vars =
        [
                'fecha_init' => $fecha_init,
                'nombre_cliente' => $nombre_cliente,
                'domicilio_cliente' => $domicilio_cliente, //
                'fraccion' => $fraccion, //
                'numero_manzana' => $numero_manzana, //
                'complemento_manzana_ii' => $complemento_manzana_ii, //
                'complemento_manzana_v' => $complemento_manzana_v, //
                'manzana_txt' => $manzana_txt, //
                'colindancias_mz' => '<div class="mceNonEditable">'.$colindancias_mz.'</div>', //
                'colindancias_ht' => '<div class="mceNonEditable">'.$colindancias_ht.'</div>', //
                'precio' => '$'.number_format($precio, 2), //
                'precio_txt' => $precio, //
                'enganche' => '$'.number_format($enganche, 2), //
                'enganche_txt' => $enganche, //
                'n_pagos' => $historial->getNPago(),
                'abono' => '$'.number_format($abono, 2),
                'abono_txt' => $abono,
                'fecha_primer_pago' => $historial->getFechaPrimerPago(),
                'fecha_ultimo_pago' => $historial->getFechaUltimoPago(),
                'porcentaje_penalizacion' => $this->input->post('porcentaje_penalizacion'), //
                'maximo_retrasos_permitidos' => $this->input->post('maximo_retrasos_permitidos'), //
                'testigo_1' => $testigo_1,
                'testigo_2' => $testigo_2,
                'historial_pagos' => $historial_pagos,
                'ciudad' => $ciudad,
                'nacimiento_cliente' => $nacimiento_cliente,
        ];
        $contrato_template = file_get_contents('./application/views/templates/contrato/contrato.php', FILE_USE_INCLUDE_PATH);
        $output = $contrato_template;
        foreach ($vars as $key => $var) {
            $search = "[@{$key}]";
            $replace = $var;
            $output = str_replace($search, $replace, $output);
        }
        header("Content-type: application/json; charset=utf-8");
        $option = array('optimizationLevel' => HTMLMinify::OPTIMIZATION_ADVANCED);
        $HTMLMinify = new HTMLMinify($output, $option);
        $html_output = $HTMLMinify->process();
        $respuesta['html'] = $html_output;
        echo json_encode($respuesta);
    }
    public function guardar_contrato()
    {
        header("Content-type: application/json; charset=utf-8");
        $email    = strtolower($this->input->post('email'));
        $identity =  $email ;
        $password = 'usuario1234';
        if ($this->input->post('fecha_nacimiento')) {
            $fecha  =  Carbon::createFromFormat('d-m-Y', $this->input->post('fecha_nacimiento'));
        } else {
            $fecha  =  Carbon::createFromFormat('d-m-Y', '01-09-1999');
        }

        $additional_data = [
            'first_name' => ucwords($this->input->post('first_name')),
            'last_name' =>  ucwords($this->input->post('last_name')),
            'phone' => $this->input->post('phone'),
            'calle' => ucwords($this->input->post('calle')),
            'no_ext' => $this->input->post('no_ext'),
            'no_int' => $this->input->post('no_int'),
            'colonia' => ucwords($this->input->post('colonia')),
            'municipio' => ucwords($this->input->post('municipio')),
            'estado' => ucwords($this->input->post('estado')),
            'ciudad' => ucwords($this->input->post('ciudad')),
            'cp' => $this->input->post('cp'),
            'lugar_nacimiento' => ucwords($this->input->post('lugar_nacimiento')),
            'fecha_nacimiento' => $fecha->format('Y-m-d'),
            'company' => 'Huertos la ceiba',
        ];
        $group = array('4');
        $this->Trans_model->begin();
        //Si hay datos de un cliente
        if ($this->input->post('id_cliente')) {
            $user_id = $this->input->post('id_cliente');
            $this->ion_auth->update($user_id, $additional_data);
            $venta = [
                'id_usuario' => $this->ion_auth->get_user_id(),
                'id_cliente' => $user_id,
                'id_lider' =>  $this->input->post('id_lider'),
                'contrato_html' => html_entity_decode($this->input->post('contrato_html')),
                'version' => 2, //Para nuevos contratos
                'estado' => 0,//En proceso de Pago
                'testigo_1' =>  ucwords($this->input->post('testigo_1')),
                'testigo_2' =>  ucwords($this->input->post('testigo_1')),
                'comision' => 0,
                'precio' => $this->input->post('precio'),
                'enganche' => $this->input->post('enganche'),
                'abono' => $this->input->post('abono'),

                'porcentaje_penalizacion' =>  $this->input->post('porcentaje_penalizacion'),
                'retrasos_permitidos' =>  $this->input->post('maximo_retrasos_permitidos'),

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            if ($this->input->post('tipo_historial') === '1-15') {
                $venta['version'] = 1;
            } elseif ($this->input->post('tipo_historial') === 'quincena-mes') {
                $venta['version'] = 1;
            } elseif ($this->input->post('tipo_historial') === 'ini-mes') {
                $venta['version'] = 1;
            }
            if ($this->input->post('confirm') == 'yes') {
                $venta['porcentaje_comision'] =  $this->input->post('porcentaje_comision');
                $venta['comision'] =  ($this->input->post('porcentaje_comision')/100)*$this->input->post('precio');
            } else {
                $venta['porcentaje_comision'] =  0;
                $venta['comision'] =  0;
            }
            if ($this->input->post('enganche') == $this->input->post('precio')) {
                $venta['estado'] =  1;
            }

            $id_venta = $this->Venta_model->insert($venta);
            if ($id_venta) {

                $db_historial = [];
                $precio = $this->input->post('precio');
                $enganche = $this->input->post('enganche');
                $abono = $this->input->post('abono');
                $fecha_init = $this->input->post('fecha_init');
                $tipo_historial = $this->input->post('tipo_historial');
                $pagar = 0;
                $pagado = 0;
                if ($this->input->post('n_pago')) {
                    //$historial = new Historial($precio, $enganche, $abono, $fecha_init, $tipo_historial,$this->input->post('n_pago')-1);
                    $historial = new Historial($precio, $enganche, $abono, $fecha_init, $tipo_historial);
                    $pagar = $this->input->post('n_pago');
                } else {
                    $historial = new Historial($precio, $enganche, $abono, $fecha_init, $tipo_historial);
                }
                $now = Carbon::now();
                foreach ($historial->getHistorial() as $key => $pago) {
                    $db_pago = new stdClass();
                    $db_pago->fecha = $pago->getFecha()->format('Y-m-d');
                    $db_pago->concepto = $pago->getConcepto();
                    $db_pago->abono = $pago->getAbono();
                    $db_pago->id_venta = $id_venta;
                    $db_pago->id_lider =  $this->input->post('id_lider');
                    $db_pago->created_at = $now->toDateTimeString();
                    $db_pago->updated_at = $now->toDateTimeString();
                    if ($key == 0 && $pago->getConcepto() == "ENGANCHE") {
                        $db_pago->id_ingreso = $this->input->post('id_ingreso');
                        $db_pago->fecha_pago = $pago->getFecha()->format('Y-m-d');
                        $db_pago->pago = $this->input->post('enganche');
                        $db_pago->estado = 1;
                        $db_pago->comision = 0;
                        $db_pago->pagado_at = Carbon::now()->toDateTimeString();
                        $db_pago->id_usuario = $this->ion_auth->get_user_id();
                    } else {
                        $db_pago->id_ingreso = 0;
                        $db_pago->fecha_pago = $pago->getFecha()->format('Y-m-d');
                        $db_pago->pago = 0;
                        $db_pago->estado = 0;
                        $db_pago->comision = 0;
                        $db_pago->pagado_at = null;
                        $db_pago->id_usuario = 0;
                        if ($pagado < $pagar && $pagar != 0 && ($tipo_historial == '1-15' || $tipo_historial == 'quincena-mes' || $tipo_historial == 'ini-mes')) {
                            $db_pago->id_ingreso = $this->input->post('id_ingreso');
                            $db_pago->pago = $db_pago->abono;
                            $db_pago->estado = 1;
                            $db_pago->comision = $db_pago->abono * ($venta['porcentaje_comision']/100);
                            $pagado++;
                        }
                    }
                    array_push($db_historial, $db_pago);
                }
                $comisionado = 0;
                $comision_venta = $venta['comision'];
                if($venta['version'] == 2 && $this->input->post('id_venta')){
                    //Cuando se esté migrando un contrato
                    $venta = $this->Venta_model->select("
                                          SUM(IF(historial.estado = 1,historial.pago,0)) AS pagado,
                                          SUM(IF(historial.estado = 1,historial.comision,0)) AS comisionado,
                                          ")
                                    ->join('historial', 'ventas.id_venta = historial.id_venta', 'left')
                                    ->where(['ventas.id_venta' => $this->input->post('id_venta')])
                                    ->get();
                    $ajuste_db_historial = [];
                    $key_0;
                    foreach($db_historial as $key => $db_h){
                        if($key == 0){
                            /*$db_historial[$key]->comision = $venta[0]->comisionado; */
                            array_push($ajuste_db_historial,$db_h);
                            $key_0 = clone $db_h;
                            $key_0->concepto = "AJUSTE DE MIGRACIÓN";
                            $key_0->estado = 2;
                            $key_0->pago = $venta[0]->pagado * -1;
                            $key_0->abono = $venta[0]->pagado * -1;
                            $key_0->comision =  $venta[0]->comisionado * -1;
                            $comisionado = $venta[0]->comisionado * -1;
                            array_push($ajuste_db_historial,$key_0);
                        }else{
                            array_push($ajuste_db_historial,$db_h);
                        }
                    }
                    $db_historial = [];
                    $db_historial = $ajuste_db_historial;
                    $this->Venta_model->where(['ventas.id_venta' => $this->input->post('id_venta')])
                                      ->update(['ventas.estado' => 4]);
                    $this->Venta_model->where(['ventas.id_venta' => $id_venta])
                                      ->update(['ventas.comision' => ($comision_venta + $comisionado)]);

                }

                $huertos_venta = [];
                $huertos = [];
                foreach ($this->cart->contents() as $items) {
                    $huerto_venta = new stdClass();
                    $huerto_venta->id_venta  = $id_venta;
                    $huerto_venta->id_huerto = $items['id_huerto'];
                    array_push($huertos_venta, $huerto_venta);
                    $huerto = new stdClass();
                    $huerto->id_huerto = $items['id_huerto'];
                    $huerto->vendido = 1;
                    if ($enganche == $precio) {
                        $huerto->vendido = 2;
                    }
                    array_push($huertos, $huerto);
                }

                $this->HuertosVentas_model->insert_batch($huertos_venta);
                $this->Huerto_model->update_batch($huertos, 'id_huerto');
                $this->Historial_model->insert_batch($db_historial);
                $this->cart->destroy();
            }
        } //Si es un cliente nuevo
        elseif ($idNewUser = $this->ion_auth->register($identity, $password, $email, $additional_data, $group)) {
            $venta = [
                'id_usuario' => $this->ion_auth->get_user_id(),
                'id_cliente' => $idNewUser,
                'id_lider' =>  $this->input->post('id_lider'),
                'contrato_html' => html_entity_decode($this->input->post('contrato_html')),
                'version' => 2, //Para nuevos contratos
                'estado' => 0,//En proceso de Pago
                'testigo_1' =>  ucwords($this->input->post('testigo_1')),
                'testigo_2' =>  ucwords($this->input->post('testigo_1')),

                'precio' => $this->input->post('precio'),
                'enganche' => $this->input->post('enganche'),
                'abono' => $this->input->post('abono'),

                'porcentaje_penalizacion' =>  $this->input->post('porcentaje_penalizacion'),
                'retrasos_permitidos' =>  $this->input->post('maximo_retrasos_permitidos'),

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            if ($this->input->post('tipo_historial') === '1-16') {
                $venta['version'] = 1;
            } elseif ($this->input->post('tipo_historial') === '15-1') {
                $venta['version'] = 1;
            } elseif ($this->input->post('tipo_historial') === 'ini-mes') {
                $venta['version'] = 1;
            } elseif ($this->input->post('tipo_historial') === 'quincena-mes') {
                $venta['version'] = 1;
            }
            if ($this->input->post('confirm') == 'yes') {
                $venta['porcentaje_comision'] =  $this->input->post('porcentaje_comision');
                $venta['comision'] =  ($this->input->post('porcentaje_comision')/100)*$this->input->post('precio');
            } else {
                $venta['porcentaje_comision'] =  0;
                $venta['comision'] =  0;
            }
            $id_venta = $this->Venta_model->insert($venta);
            if ($id_venta) {
                $db_historial = [];
                $precio = $this->input->post('precio');
                $enganche = $this->input->post('enganche');
                $abono = $this->input->post('abono');
                $fecha_init = $this->input->post('fecha_init');
                $tipo_historial = $this->input->post('tipo_historial');
                if ($this->input->post('n_pago')) {
                    //$historial = new Historial($precio, $enganche, $abono, $fecha_init, $tipo_historial,$this->input->post('n_pago')-1);
                    $historial = new Historial($precio, $enganche, $abono, $fecha_init, $tipo_historial);
                } else {
                    $historial = new Historial($precio, $enganche, $abono, $fecha_init, $tipo_historial);
                }
                $now = Carbon::now();
                foreach ($historial->getHistorial() as $key => $pago) {
                    $db_pago = new stdClass();
                    $db_pago->fecha = $pago->getFecha()->format('Y-m-d');
                    $db_pago->concepto = $pago->getConcepto();
                    $db_pago->abono = $pago->getAbono();
                    $db_pago->id_venta = $id_venta;
                    $db_pago->id_lider =  $this->input->post('id_lider');
                    $db_pago->created_at = $now->toDateTimeString();
                    $db_pago->updated_at = $now->toDateTimeString();
                    if ($key == 0) {
                        $db_pago->id_ingreso = $this->input->post('id_ingreso');
                        $db_pago->fecha_pago = $pago->getFecha()->format('Y-m-d');
                        $db_pago->pago = $this->input->post('enganche');
                        $db_pago->estado = 1;
                        $db_pago->pagado_at = Carbon::now()->toDateTimeString();
                        $db_pago->id_usuario = $this->ion_auth->get_user_id();
                    } else {
                        $db_pago->id_ingreso = 0;
                        $db_pago->fecha_pago = $pago->getFecha()->format('Y-m-d');
                        $db_pago->pago = 0;
                        $db_pago->estado = 0;
                        $db_pago->pagado_at = NULL;
                        $db_pago->id_usuario = 0;
                    }
                    array_push($db_historial, $db_pago);
                }
                $huertos_venta = [];
                foreach ($this->cart->contents() as $items) {
                    $huerto_venta = new stdClass();
                    $huerto_venta->id_venta  = $id_venta;
                    $huerto_venta->id_huerto = $items['id_huerto'];
                    array_push($huertos_venta, $huerto_venta);
                }

                $huertos_venta = [];
                $huertos = [];
                foreach ($this->cart->contents() as $items) {
                    $huerto_venta = new stdClass();
                    $huerto_venta->id_venta  = $id_venta;
                    $huerto_venta->id_huerto = $items['id_huerto'];
                    array_push($huertos_venta, $huerto_venta);
                    $huerto = new stdClass();
                    $huerto->id_huerto = $items['id_huerto'];
                    $huerto->vendido = 1;
                    if ($enganche == $precio) {
                        $huerto->vendido = 2;
                    }
                    array_push($huertos, $huerto);
                }

                $this->HuertosVentas_model->insert_batch($huertos_venta);
                $this->Huerto_model->update_batch($huertos, 'id_huerto');
                $this->Historial_model->insert_batch($db_historial);
                $this->Reserva_model->where(['id_reserva' => $this->input->post('id_reserva')])
                                    ->delete();
                $this->cart->destroy();
            }
        }
        if ($this->Trans_model->status() === false) {
            $this->Trans_model->rollback();
            echo "Error fatal";
        } else {
            $this->Trans_model->commit();
            echo json_encode(['status' => 200 ,'msg' => 'ok']);
            if (isset($id_venta) && !empty($id_venta)) {
                $this->session->set_flashdata('id_venta', $id_venta);
            }
        }
    }
}

class Pago
{
    private $fecha;
    private $concepto;
    private $abono;
    private $pagado;

    public function __construct($concepto, $abono, $pagado, $fecha)
    {
        $this->fecha = $fecha;
        $this->concepto = $concepto;
        $this->abono = $abono;
        $this->pagado = $pagado;
    }
    public function getConcepto()
    {
        return $this->concepto;
    }
    public function getAbono()
    {
        return $this->abono;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
}
class Historial
{
    private $total = 0;
    private $enganche = 0;
    private $abono = 0;
    private $fecha_inicial;
    private $n_pago = 0;
    private $historial = [];
    private $pagado = 0;
    private $getQuincena;
    private $fecha;

    private $fecha_primer_pago;
    private $fecha_ultimo_pago;
    private $tipo_historial;
    public function __construct($total, $enganche, $abono, $fecha_inicial, $tipo_historial, $n_pago = 0)
    {
        $this->total = $total;
        $this->enganche = $enganche;
        $this->abono = $abono;

        $this->n_pago = $n_pago;

        $this->tipo_historial = $tipo_historial;
        $this->fecha_inicial = Carbon::createFromFormat('d-m-Y', $fecha_inicial);
        $this->fecha = Carbon::createFromFormat('d-m-Y', $fecha_inicial);
        $this->generarHistorial();
        $this->setDates();
    }

    public function generarHistorial()
    {
        $abono = $this->enganche;

        if ($this->n_pago == 0 || $this->n_pago == $this->n_pago) {
            $fecha = $this->fecha_inicial;
            $concepto = "ENGANCHE";
            $this->pagado += $this->enganche;
            array_push($this->historial, new Pago($concepto, $abono, $this->pagado, 'Fecha calculada'));
        }
        if ($this->pagado > 0) {
            while ($this->pagado < $this->total) {
                $this->n_pago++;
                $concepto = "PAGO ".$this->n_pago;
                $abono = $this->abono;
                $this->pagado+= $abono;
                if ($this->pagado > $this->total) {
                    $this->pagado-= $abono;
                    $abono = $this->total - $this->pagado;
                    $this->pagado+= $abono;
                    array_push($this->historial, new Pago($concepto, $abono, $this->pagado, 'Fecha calculada'));
                    break;
                }
                array_push($this->historial, new Pago($concepto, $abono, $this->pagado, 'Fecha calculada'));
            }
        } else {
            unset($this->historial[0]);
            $this->historial = array_values($this->historial);
            while ($this->pagado < $this->total) {
                $this->n_pago++;
                $concepto = "PAGO ".$this->n_pago;
                $abono = $this->abono;
                $this->pagado+= $abono;
                if ($this->pagado > $this->total) {
                    $this->pagado-= $abono;
                    $abono = $this->total - $this->pagado;
                    $this->pagado+= $abono;
                    array_push($this->historial, new Pago($concepto, $abono, $this->pagado, 'Fecha calculada'));
                    break;
                }
                array_push($this->historial, new Pago($concepto, $abono, $this->pagado, 'Fecha calculada'));
            }
        }
    }

    public function setDates()
    {
        //Nuevos
        if ($this->tipo_historial === 'nuevo-quincena') {
            $next = "fin_de_mes";
            $fecha = $this->fecha;
            foreach ($this->historial as $key => $row) {
                if ($key === 0) {
                    $this->historial[$key]->setFecha($this->fecha_inicial);
                    $dia = ($this->fecha_inicial->day);
                    if ($dia <= 5) {
                        $next = "quincena";
                        $fecha->startOfMonth();
                    } elseif ($dia > 5 && $dia <= 15) {
                        $next = "ini_mes";
                    } else {
                        $new_date_end = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $endDay = $new_date_end->endOfMonth()->day;

                        if (($dia + 10) > $endDay) {
                            $fecha = $new_date_end->addDay(1);
                            $next = 'quincena';
                        } else {
                            $next = 'ini_mes';
                        }
                    }
                } else {
                    if ($next == 'ini_mes') {
                        $fecha = $fecha->endOfMonth()->addDay(1);
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $next = "quincena";
                    } elseif ($next == 'quincena') {
                        $fecha = $fecha->addDay(14);
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $next = "ini_mes";
                    }
                }
            }
        } elseif ($this->tipo_historial === 'nuevo-mensual-f') {
            $fecha = $this->fecha;
            foreach ($this->historial as $key => $row) {
                if ($key === 0) {
                    $this->historial[$key]->setFecha($this->fecha_inicial);
                    $dia = ($this->fecha_inicial->day);
                    $new_date_end = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                    $endDay = $new_date_end->endOfMonth()->day;
                    if ($dia < $endDay-10) {
                        $next = 'fin_de_mes';
                    } else {
                        $fecha = $fecha->endOfMonth()->addDay(1);
                        $next = 'fin_de_mes';
                    }
                } else {
                    if ($next == 'fin_de_mes') {
                        $fecha = $fecha->endOfMonth();
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $fecha = $fecha->endOfMonth()->addDay(1);
                        $next = "fin_de_mes";
                    }
                }
            }
        } elseif ($this->tipo_historial === 'nuevo-mensual-q') {
            $fecha = $this->fecha;
            foreach ($this->historial as $key => $row) {
                if ($key === 0) {
                    $this->historial[$key]->setFecha($this->fecha_inicial);
                    $dia = ($this->fecha_inicial->day);

                    if ($dia <= 15-10) { //Revisar las demás condiciones
                        $next = 'quincena';
                        $fecha->startOfMonth();
                    } else {
                        $fecha = $fecha->endOfMonth()->addDay(1);
                        $next = 'quincena';
                    }
                } else {
                    if ($next == 'quincena') {
                        $fecha = $fecha->addDay(14);
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $fecha = $fecha->endOfMonth()->addDay(1);
                        $next = "quincena";
                    }
                }
            }
        } elseif ($this->tipo_historial === 'nuevo-mensual-i') {
            $fecha = $this->fecha;
            foreach ($this->historial as $key => $row) {
                if ($key === 0) {
                    $this->historial[$key]->setFecha($this->fecha_inicial);
                    $dia = ($this->fecha_inicial->day);
                    $new_date_end = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                    $endDay = $new_date_end->endOfMonth()->day;

                    if ($dia + 10 <= $endDay) {
                        $fecha = $fecha->endOfMonth();
                        $next = 'ini_mes';
                    } else {
                        $fecha = $fecha->endOfMonth()->addDay(1);
                        $fecha = $fecha->endOfMonth();
                        $next = 'ini_mes';
                    }
                } else {
                    if ($next == 'ini_mes') {
                        $fecha = $fecha->addDay(1);
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $fecha = $fecha->endOfMonth();
                        $next = "ini_mes";
                    }
                }
            }
        } //Antiguos
        elseif ($this->tipo_historial === '1-16') {
            $fecha = $this->fecha;
            foreach ($this->historial as $key => $row) {
                if ($key === 0) {
                    $this->historial[$key]->setFecha($this->fecha_inicial);
                    $dia = ($this->fecha_inicial->day);
                    if ($dia <= 15) {
                        $next = 'dieciseis_de_mes';
                        $fecha->startOfMonth();
                    } elseif ($dia >= 16) {
                        $next = 'inicio_de_mes';
                    }
                } else {
                    if ($next === 'inicio_de_mes') {
                        $fecha = $fecha->endOfMonth()->addDay(1);
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $next = 'dieciseis_de_mes';
                    } elseif ($next === 'dieciseis_de_mes') {
                        $fecha = $fecha->addDay(15);
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $next = 'inicio_de_mes';
                    }
                }
            }
        } //En uso
        elseif ($this->tipo_historial === '1-15') {
            $fecha = $this->fecha;
            foreach ($this->historial as $key => $row) {
                if ($key === 0) {
                    $this->historial[$key]->setFecha($this->fecha_inicial);
                    $dia = ($this->fecha_inicial->day);
                    if ($dia < 15) {
                        $next = 'dieciseis_de_mes';
                        $fecha->startOfMonth();
                    } elseif ($dia == 15) {
                        $next = 'inicio_de_mes';
                    } elseif ($dia >= 16) {
                        $next = 'inicio_de_mes';
                    }
                } else {
                    if ($next === 'inicio_de_mes') {
                        $fecha = $fecha->endOfMonth()->addDay(1);
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $next = 'dieciseis_de_mes';
                    } elseif ($next === 'dieciseis_de_mes') {
                        $fecha = $fecha->addDay(14);
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $next = 'inicio_de_mes';
                    }
                }
            }
        } elseif ($this->tipo_historial === '15-1') {
            $fecha = $this->fecha;
            foreach ($this->historial as $key => $row) {
                if ($key === 0) {
                    $this->historial[$key]->setFecha($this->fecha_inicial);
                    $dia = ($this->fecha_inicial->day);
                    if ($dia < 15) {
                        $fecha->startOfMonth();
                        $next = 'quince_de_mes';
                    } elseif ($dia >= 15) {
                        $next = 'inicio_de_mes';
                    }
                } else {
                    if ($next === 'inicio_de_mes') {
                        $fecha = $fecha->endOfMonth()->addDay(1);
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $next = 'quince_de_mes';
                    } elseif ($next === 'quince_de_mes') {
                        $fecha = $fecha->addDay(14);
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $next = 'inicio_de_mes';
                    }
                }
            }
        } elseif ($this->tipo_historial === 'fin-mes') {
            $fecha = $this->fecha;
            foreach ($this->historial as $key => $row) {
                if ($key === 0) {
                    $this->historial[$key]->setFecha($this->fecha_inicial);
                    $dia = ($this->fecha_inicial->day);
                    $new_date_end = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                    $endDay = $new_date_end->endOfMonth()->day;
                    if ($dia < $endDay) {
                        $next = 'fin_de_mes';
                    } else {
                        $fecha = $fecha->endOfMonth()->addDay(1);
                        $next = 'fin_de_mes';
                    }
                } else {
                    if ($next == 'fin_de_mes') {
                        $fecha = $fecha->endOfMonth();
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $fecha = $fecha->endOfMonth()->addDay(1);
                        $next = "fin_de_mes";
                    }
                }
            }
        } //En uso
        elseif ($this->tipo_historial === 'quincena-mes') {
            $fecha = $this->fecha;
            foreach ($this->historial as $key => $row) {
                if ($key === 0) {
                    $this->historial[$key]->setFecha($this->fecha_inicial);
                    $dia = ($this->fecha_inicial->day);

                    if ($dia < 15) { //Revisar las demás condiciones
                        $next = 'quincena';
                        $fecha->startOfMonth();
                    } elseif ($dia == 15) {
                        $next = 'quincena';
                        $fecha = $fecha->endOfMonth()->addDay(1);
                    } else {
                        $fecha = $fecha->endOfMonth()->addDay(1);
                        $next = 'quincena';
                    }
                } else {
                    if ($next == 'quincena') {
                        $fecha = $fecha->addDay(14);
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $fecha = $fecha->endOfMonth()->addDay(1);
                        $next = "quincena";
                    }
                }
            }
        } //En uso
        elseif ($this->tipo_historial === 'ini-mes') {
            $fecha = $this->fecha;
            foreach ($this->historial as $key => $row) {
                if ($key === 0) {
                    $this->historial[$key]->setFecha($this->fecha_inicial);
                    $dia = ($this->fecha_inicial->day);
                    $new_date_end = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                    $endDay = $new_date_end->endOfMonth()->day;


                    $fecha = $fecha->endOfMonth();
                    $next = 'ini_mes';
                } else {
                    if ($next == 'ini_mes') {
                        $fecha = $fecha->addDay(1);
                        $new_date = Carbon::createFromFormat('d-m-Y', $fecha->format('d-m-Y'));
                        $row->setFecha($new_date);
                        $this->historial[$key] = $row;
                        $fecha = $fecha->endOfMonth();
                        $next = "ini_mes";
                    }
                }
            }
        }
    }

    public function getHistorial()
    {
        return $this->historial;
    }

    /*public function getFecha()
    {
        if ($this->getQuincena == true) {
            $this->getQuincena = false;
            return $this->_fecha->addDays(15);
        } else {
            $this->getQuincena = true;
            return $this->_fecha->endOfMonth();
        }
    }*/
    public function getNPago()
    {
        return $this->n_pago;
    }
    public function getPagado()
    {
        return $this->pagado;
    }
    public function setFechaPrimerPago($fecha)
    {
        $this->fecha_primer_pago = $fecha;
    }
    public function getFechaPrimerPago()
    {
        return $this->fecha_primer_pago;
    }
    public function setFechaUltimoPago($fecha)
    {
        $this->fecha_ultimo_pago = $fecha;
    }
    public function getFechaUltimoPago()
    {
        return $this->fecha_ultimo_pago;
    }
}
/*
    SELECT GROUP_CONCAT(DISTINCT manzanas.manzana ORDER BY  manzanas.manzana ASC) as manzanas, GROUP_CONCAT("Mz. ",manzanas.manzana, " Ht. ", huertos.huerto ORDER BY  manzanas.manzana ASC) as descripcion FROM ventas
    LEFT JOIN huertos_ventas ON ventas.id_venta = huertos_ventas.id_venta
    LEFT JOIN huertos ON huertos_ventas.id_huerto = huertos.id_huerto
    LEFT JOIN manzanas ON huertos.id_manzana = manzanas.id_manzana
    WHERE ventas.id_venta = 3;
*/
