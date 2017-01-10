<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options as Options;
class Venta extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Contrato_model');
        $this->load->model('Ingreso_model');
        $this->load->model('Manzana_model');
        $this->load->model('Huerto_model');
    }
    public function index()
    {
        $data['title'] = "Venta";
        $data['body'] = "venta";
        $fecha = Carbon::now();
        $data['fecha'] = $fecha->format('d-m-Y');
        $data['ingresos'] = $this->Ingreso_model->get();
       
        $this->load->view('templates/template', $data);
    }
    public function pdf(){
        $html = "";
        $dompdf = new Dompdf();
        $dompdf->loadHtml("");

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('hola.pdf',array('Attachment'=>0));
    }

    public function maxpdf(){
        $html = file_get_contents('./application/views/templates/pagares/pagare.php', FILE_USE_INCLUDE_PATH);
        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser
        $dompdf->stream('hola.pdf',array('Attachment'=>0));
        // echo $html;
    }
    public function prueba()
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
                $colindancias .= "una superficie de {$superficie_ht} M<sup>2</sup>, con las Medidas y Colindancias Siguientes: ";
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
        $historial = new Historial($precio,$enganche,$abono,$fecha_init);
        $historial_pagos = "<table class='pagares-tabla'>";

        $historial_pagos .=    "<thead>";
        $historial_pagos .=         "<tr>";
        $historial_pagos .=             "<th>Concepto</th>";
        $historial_pagos .=             "<th>Monto a pagar</th>";
        $historial_pagos .=             "<th>Fecha de pago</th>";
        $historial_pagos .=         "</tr>";
        $historial_pagos .=    "</thead>";
        $historial_pagos .=    "<tbody>";
        foreach ($historial->getHistorial() as $key => $pago) {
            if($key == 1){
                $historial->setFechaPrimerPago($pago->getFecha());
            }else{
                $historial->setFechaUltimoPago($pago->getFecha());
            }
            $abonos = number_format($pago->getAbono(), 2);
            $historial_pagos .=     "<tr>";
            $historial_pagos .=         "<td>{$pago->getConcepto()}</td>";
            $historial_pagos .=         "<td>$ {$abonos}</td>";
            $historial_pagos .=         "<td>{$pago->getFecha()}</td>";
            $historial_pagos .=     "<tr>";
        }
        $pagado = number_format($historial->getPagado(),2);
        $historial_pagos .=    "<tfoot>";
        $historial_pagos .=         "<tr>";
        $historial_pagos .=             "<td><strong>TOTAL:</strong></td>";
        $historial_pagos .=             "<td>$ {$pagado}</td>";
        $historial_pagos .=             "<td>&nbsp;</td>";
        $historial_pagos .=         "</tr>";
        $historial_pagos .=    "</tfoot>";
        $historial_pagos .=    "</tbody>";
        $historial_pagos .= "</table>";
            
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

        $nombre_cliente = $this->input->post('first_name') . " " .$this->input->post('last_name');
        $domicilio_cliente = ($this->input->post('calle')) ? "Calle: ".$this->input->post('calle') : "";
        $domicilio_cliente .= ($this->input->post('no_ext')) ? " No. ext: ".$this->input->post('no_ext') : "";
        $domicilio_cliente .= ($this->input->post('no_int')) ? " No. int: ".$this->input->post('no_int') : "";
        $domicilio_cliente .= ($this->input->post('colonia')) ? " No. int: ".$this->input->post('colonia') : "";
        $domicilio_cliente .= ($this->input->post('municipio')) ? " Municipio: ".$this->input->post('municipio') : "";
        $domicilio_cliente .= ($this->input->post('estado')) ? " Estado: ".$this->input->post('estado') : "";
        $domicilio_cliente .= ($this->input->post('ciudad')) ? " Ciudad: ".$this->input->post('ciudad') : "";
        $domicilio_cliente .= ($this->input->post('cp')) ? " Codigo postal: ".$this->input->post('cp') : "";
        $ciudad = $this->input->post('ciudad');

        $testigo_1 = $this->input->post('testigo_1');
        $testigo_2 = $this->input->post('testigo_2');
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
                'colindancias_mz' => $colindancias_mz, //
                'colindancias_ht' => $colindancias_ht, //
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
        ];
        $contrato_template = file_get_contents('./application/views/templates/contrato/contrato.php', FILE_USE_INCLUDE_PATH);
        $output = $contrato_template;
        foreach ($vars as $key => $var) {
            $search = "[@{$key}]";
            $replace = $var;
            $output = str_replace($search, $replace, $output);
        }
        header("Content-type: application/json; charset=utf-8");
        $respuesta['html'] = $output;
        echo json_encode($respuesta);
        //$data['output'] = $output;
        //$this->load->view('./templates/contrato/prueba', $data);
    }
    private $generate=[];
    public function read()
    {
        ini_set('max_execution_time', 0);
        $cadena = file_get_contents('http://localhost/ceiba_negra/docs/Datos%20del%20predio/Datos%20a%20migrar/lotes.txt', FILE_USE_INCLUDE_PATH);
        $textos = explode("\r\n", $cadena);
        $i = 0;
        $consultas = [];
        $concat = "";
        echo "<pre>";
        foreach ($textos as $key => $texto) {
            $texto = trim($texto);
            if (preg_match('/^MANZANA\s*[0-9]*$/i', $texto)) {
                //echo $texto."Inicio de bloque<br/>";
                continue;
            } else {
                if (preg_match("/LOTE NO./", $texto)) {
                    if ($i == 0) {
                        $concat = $texto . " ";
                        $i++;
                    } else {
                        array_push($this->generate, $concat);
                        $concat = $texto . " ";
                    }
                } else {
                    $concat.= $texto  . " ";
                }
            }
        }
        if (!empty($concat)) {
             array_push($this->generate, $concat);
        }
        foreach ($this->generate as $key => $valor) {
            $string_clean  = preg_replace('/ +/', ' ', $valor);
            $string_clean = trim($string_clean);
            $this->generate[$key] = $string_clean;
        }
        foreach ($this->generate as $key => $valor) {
            $string_clean  = preg_replace('/M2 MEDIDAS Y COLINDANCIAS:/', '', $valor);
            $this->generate[$key] = $string_clean;
        }
        $where = "";
        foreach ($this->generate as $key => $valor) {
            $array = explode(" AL ", $valor);
            $encontrado = [];
            foreach ($array as $clave => $a) {
                if ($clave == 0) {
                    $array[$clave] = str_replace('LOTE NO.', 'huerto=\'', $array[$clave]);
                    $array[$clave] = str_replace('MANZANA ', 'manzana=', $array[$clave]);
                    $array[$clave] = str_replace('SUPERFICIE: ', 'superficie=', $array[$clave]);
                    $array2 = explode(' ', $array[$clave]);
                    $where = "";
                    $set = "";
                    foreach ($array2 as $c => $b) {
                        if ($c == 0) {
                            $where .= " WHERE huertos.".$b."'";
                        } elseif ($c == 1) {
                            $where .= " AND manzanas.".$b;
                        } elseif ($c == 2) {
                            $b = preg_replace('/M2/', '', $b);
                            $b = preg_replace('/,+/', '', $b);
                            $set .= " SET huertos.".$b.'';
                            $c_precio = explode('=', $b);
                            $calculo = (float) $c_precio[1] * (110000 / 312.5);
                            $set .= ', huertos.precio='.$calculo;
                        }
                    }
                } else {
                    $buscar = ['norte','noreste','este','sureste','sur','suroeste','oeste','noroeste'];
                    foreach ($buscar as $busca) {
                        if (preg_match("/\b{$busca}\b/i", $array[$clave])) {
                            $array[$clave] = str_replace('CHIT', 'CHIÍT', $array[$clave]);
                            $array[$clave] = str_replace('BAMBU', 'BAMBÚ', $array[$clave]);
                            $array[$clave] = str_replace('BALCHE', 'BALCHÉ', $array[$clave]);
                            $array[$clave] = preg_replace("/{$busca}\s*/i", "huertos.col_{$busca}=\"", $array[$clave]);
                            $array[$clave] = $array[$clave] .= "\"";
                            if (strlen(preg_replace("/huertos.col_{$busca}\s*/i", "", $array[$clave])) >= 399) {
                                echo preg_replace("/huertos.col_{$busca}\s*/i", "", $array[$clave]);
                                echo $array[$clave];
                                echo "<h1>ENORME ERROR</h1>";
                                echo strlen($array[$clave]);
                                die();
                            }
                            $set .= ', '. $array[$clave];
                            if (!in_array($busca, $encontrado)) {
                                array_push($encontrado, $busca);
                            }
                        } else {
                            //$set .=  ", huerto.col_{$busca} = ''";
                        }
                    }
                }
            }
            $dif = array_diff($buscar, $encontrado);
            foreach ($dif as $d) {
                $set .=  ", huertos.col_{$d} = ''";
            }

            $sql = "UPDATE huertos LEFT JOIN manzanas ON huertos.id_manzana = manzanas.id_manzana{$set}{$where}";
            array_push($consultas, $sql);
        }
        foreach ($consultas as $key => $consulta) {
            $afectar = $this->Huerto_model->run_sql($consulta);
             var_dump($key);
            var_dump($consulta);
            var_dump($afectar);
        }
        echo "</pre>";
    }
    public function read2()
    {
        ini_set('max_execution_time', 0);
        $cadena = file_get_contents('http://localhost/ceiba_negra/docs/Datos%20del%20predio/Datos%20a%20migrar/manzanas.txt', FILE_USE_INCLUDE_PATH);
        $textos = explode("\r\n", $cadena);
        $i = 0;
        $consultas = [];
        $concat = "";
        echo "<pre>";
        foreach ($textos as $key => $texto) {
            $texto = trim($texto);
            if (preg_match("/MANZANA \s*/", $texto)) {
                if ($i == 0) {
                    $concat = $texto . " ";
                    $i++;
                } else {
                    array_push($this->generate, $concat);
                    $concat = $texto . " ";
                }
            } else {
                $concat.= $texto  . " ";
            }
        }
        if (!empty($concat)) {
             array_push($this->generate, $concat);
        }
        //Quitar espacios en blanco de más
        foreach ($this->generate as $key => $valor) {
            $string_clean  = preg_replace('/ +/', ' ', $valor);
            $string_clean = trim($string_clean);
            $this->generate[$key] = $string_clean;
        }
        //Quitar palabra
        foreach ($this->generate as $key => $valor) {
            $string_clean  = preg_replace('/M2 MEDIDAS Y COLINDANCIAS:\s*/', ' ', $valor);
            $this->generate[$key] = $string_clean;
        }
        $where = "";
        foreach ($this->generate as $key => $valor) {
            $array = explode(" AL ", $valor);
            $encontrado = [];
            foreach ($array as $clave => $a) {
                if ($clave == 0) {
                    $array[$clave] = str_replace('MANZANA ', 'manzana=', $array[$clave]);
                    $array[$clave] = str_replace('SUPERFICIE: ', 'superficie=', $array[$clave]);
                    $array2 = explode(' ', $array[$clave]);
                    $where = "";
                    $set = "";
                    foreach ($array2 as $c => $b) {
                        if ($c == 0) {
                            $where .= " WHERE manzanas.".$b."";
                        } elseif ($c == 1) {
                            $b = preg_replace('/M2/', '', $b);
                            $b = preg_replace('/,+/', '', $b);
                            $set .= " SET manzanas.".$b.'';
                            /*$c_precio = explode('=',$b);
                            $calculo = (float) $c_precio[1] * (110000 / 312.5);
                            $set .= ', manzana.precio='.$calculo;*/
                        }
                    }
                } else {
                    $buscar = ['norte','noreste','este','sureste','sur','suroeste','oeste','noroeste'];
                    foreach ($buscar as $busca) {
                        if (preg_match("/\b{$busca}\b/i", $array[$clave])) {
                            $array[$clave] = preg_replace("/{$busca}\s*/i", "manzanas.col_{$busca}=\"", $array[$clave]);
                            $array[$clave] = $array[$clave] .= "\"";
                            $set .= ', '. $array[$clave];
                            if (!in_array($busca, $encontrado)) {
                                array_push($encontrado, $busca);
                            }
                        }
                    }
                }
            }
            $dif = array_diff($buscar, $encontrado);
            foreach ($dif as $d) {
                $set .=  ", manzanas.col_{$d} = ''";
            }

            $sql = "UPDATE manzanas {$set}{$where}";
            array_push($consultas, $sql);
        }
        foreach ($consultas as $key => $consulta) {
            $afectar = $this->Huerto_model->run_sql($consulta);
            var_dump($key);
            var_dump($consulta);
            var_dump($afectar);
        }
        echo "</pre>";
    }
    public function generate()
    {
        var_dump($this->generate);
    }
}
class Pago
{
    private $concepto;
    private $abono;
    private $pagado;
    public $fecha;
    public function __construct($concepto, $abono, $pagado, $fecha)
    {
        $this->concepto = $concepto;
        $this->abono = $abono;
        $this->pagado = $pagado;
        
        $this->fecha = $fecha;
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
    private $_fecha;

    private $fecha_primer_pago;
    private $fecha_ultimo_pago;
    public function __construct($total, $enganche, $abono, $fecha_inicial)
    {
        $this->total = $total;
        $this->enganche = $enganche;
        $this->abono = $abono;
        
        $this->fecha_inicial = Carbon::createFromFormat('d-m-Y', $fecha_inicial);
        $dia = $this->fecha_inicial->day;

        $this->_fecha = Carbon::createFromFormat('d-m-Y', $fecha_inicial);
        if ($dia < 15) {
            $this->_fecha = $this->_fecha->startOfMonth();
            $this->_fecha = $this->_fecha->subDay();
            $this->getQuincena = true;
        } else {
            $fin =  $this->_fecha->endOfMonth();
            $dia_f = $fin->day;
            if ($dia < $dia_f) {
                $this->getQuincena = false;
            } else {
                $this->_fecha = $this->_fecha->endOfMonth();
                $this->getQuincena = true;
            }
        }
        $this->generarHistorial();
    }

    public function generarHistorial()
    {
        $abono = $this->enganche;
        
        if ($this->n_pago == 0) {
            $fecha = $this->fecha_inicial;
            $concepto = "ENGANCHE";
            $this->pagado += $this->enganche;
            array_push($this->historial, new Pago($concepto, $abono, $this->pagado, $fecha->format('d-m-Y')));
        }
        if ($this->pagado > 0) {
            while ($this->pagado < $this->total) {
                $fecha_h = $this->getFecha();

                $this->n_pago++;
                $concepto = "PAGO ".$this->n_pago;
                $abono = $this->abono;
                $this->pagado+= $abono;
                if ($this->pagado > $this->total) {
                    $this->pagado-= $abono;
                    $abono = $this->total - $this->pagado;
                    $this->pagado+= $abono;
                    array_push($this->historial, new Pago($concepto, $abono, $this->pagado, $fecha_h->format('d-m-Y')));
                    break;
                }
                array_push($this->historial, new Pago($concepto, $abono, $this->pagado, $fecha_h->format('d-m-Y')));
            }
        }
    }

    public function getHistorial()
    {
        return $this->historial;
    }

    public function getFecha()
    {
        if ($this->getQuincena == true) {
            $this->getQuincena = false;
            return $this->_fecha->addDays(15);
        } else {
            $this->getQuincena = true;
            return $this->_fecha->endOfMonth();
        }
    }
    public function getNPago()
    {
        return $this->n_pago;
    }
    public function getPagado(){
        return $this->pagado;
    }
    public function setFechaPrimerPago($fecha){
        $this->fecha_primer_pago = $fecha;
    }
    public function getFechaPrimerPago(){
        return $this->fecha_primer_pago;
    }
    public function setFechaUltimoPago($fecha){
        $this->fecha_ultimo_pago = $fecha;
    }
    public function getFechaUltimoPago(){
        return $this->fecha_ultimo_pago;
    }
}
