<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Carbon\Carbon;
use Dompdf\Dompdf;
use zz\Html\HTMLMinify;
use Dompdf\Options as Options;
class Reportes extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Contrato_model');
        $this->load->model('Opciones_ingreso_model');
        $this->load->model('Manzana_model');
        $this->load->model('Huerto_model');
        $this->load->model('Venta_model');
        $this->load->model('Historial_model');
        $this->load->model('HuertosVentas_model');
    }
    public function contrato($id){
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);
        set_time_limit(300);
        $condicion = ['ventas.id_venta' => $id];
        $ventas = $this->Venta_model
                                    ->select("contrato_html")
                                    ->where($condicion)
                                    ->get();
        $html = "";
        foreach($ventas as $venta){
            $html.= "<html><head><meta charset='utf-8'><title>Contrato</title></head><body>";
            $html.= "<link rel='stylesheet' type='text/css' href='".base_url().'assets/css/tinymce.min.css'."' />";
            $html.= "<div class='mce-content-body'>";
            $html.= $venta->contrato_html;
            $html.= "</div>";
            $html.= "</body></html>";
                      
            $option = array('optimizationLevel' => HTMLMinify::OPTIMIZATION_ADVANCED);
            $HTMLMinify = new HTMLMinify($html,$option);
            $output = $HTMLMinify->process();
            
            $dompdf = new Dompdf();
            $dompdf->loadHtml($output);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('legal', 'portrait');
            // Render the HTML as PDF
            $dompdf->render();
            // Output the generated PDF to Browser
            // Output the generated PDF (1 = download and 0 = preview)
            $dompdf->stream('contrato',array('Attachment'=>1));
        }
            
    }
    public function pagares($id){
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);
        set_time_limit(300);
        /*define(DOMPDF_ENABLE_CSS_FLOAT,true);*/
        $condicion = ['historial.id_venta' => $id];

        $historials = $this->Historial_model ->select("historial.abono,historial.fecha,CONCAT(users.first_name,' ',users.last_name) as nombre_cliente,ventas.porcentaje_penalizacion, ventas.version")
                                            ->join('ventas','historial.id_venta = ventas.id_venta','left')
                                            ->join('users','ventas.id_cliente = users.id','left')
                                            //->join('huertos_ventas','historial.id_venta = huertos_ventas.id_venta','left')
                                            //->join('huertos','huertos_ventas.id_venta = huertos.id_huerto','left')
                                            ->where($condicion)
                                            ->get();       
        
        if(count($historials)){
            $condicion = ['huertos_ventas.id_venta' => $id];
            $huertos_from_venta = $this->HuertosVentas_model->select('huertos.huerto,manzanas.manzana')
                                                        ->join('huertos','huertos_ventas.id_huerto = huertos.id_huerto','left')
                                                        ->join('manzanas',' manzanas.id_manzana = huertos.id_manzana','left')
                                                        ->where($condicion)
                                                        ->get();
            $txt_huertos = "";
            if(count($huertos_from_venta)){
                foreach($huertos_from_venta as $key => $huerto){
                    if($key > 0){
                        $txt_huertos.= ',';
                    }
                    $txt_huertos.= "No. {$huerto->huerto} MZ. {$huerto->manzana}";
                }
            }       
                                     
            $pagares = "";
            $count = 1;
            $n = 1;
            $n_historial = count($historials);
            
            foreach ($historials as $key => $historial) {
                $fecha = Carbon::createFromFormat('Y-m-d', $historial->fecha);
                $pagares .= "<div style='page-break-after: avoid;'></div>
                                <div class='pagarecontainer'>
                                <div class='pagarecontainer_wrap'>
                                    <div class='num_pagare'>
                                        <strong>PAGARE No. {$n}</strong>
                                    </div>
                                    <div class='fecha_pagare'>
                                        <!--<div class='folio_pagare'>
                                            <strong>PAGARE No. 145785</strong>
                                        </div>-->
                                        <ul>
                                            <li><strong>Fecha de pago:</strong></li>
                                            <li><span>{$fecha->format('d-m-Y')}</span></li>
                                        </ul>
                                        <ul>
                                            <li><strong>Lugar de Pago:</strong></li>
                                            <li><span>Playa del Carmen, Solidaridad, Q. Roo</span></li>
                                        </ul>
                                    </div>
                                    <p class='texto_pagare'>
                                        Debo y pagaré incondicionalmente por este Pagaré a la orden de <strong>FRANCISCO ENRIQUE MARTINEZ CORDERO</strong>,
                                        <strong>&nbsp;$".number_format($historial->abono,2)." PESOS 00/100 M.N</strong>,valor recibido a mi satisfacción.
                                    </p>
                                    <table>
                                        <tr>
                                            <td><strong>{$historial->nombre_cliente}</strong></td>
                                            <td>
                                                <p> Este pagaré forma pare te una serie numerdada de 1 al {$n_historial}  y todos estan sujetos a la condición de que, al no pagarse cualquiera de ellos a su vencimiento, serán exigibles todos los que le sigan en número, además de los ya vencidos,
                                                    desde la fecha de vencimiento de este documento hasta el día de su liquidación, causará intereses moratorios al tipo de {$historial->porcentaje_penalizacion}% por cada día de de pago incumplido, pagado en esta ciudad.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>";
                $n++;
                /*if ($count == 1) {
                        $pagares.="<tr>
                                        <td>
                                            <div class='pagare'>
                                                <div class='pagare__header'>
                                                    <h3>RECIBO DE DINERO No.  &nbsp; <span>FECHA: </span></h3>
                                                </div>
                                                <div class='pagare__body'>
                                                    <p>Debe(mos) y pagare(mos) incondicionalmente por este Pagaré a la orden de FRANCISCO ENRIQUE MARTINEZ CORDERO en Playa del carmen, municipio de Solidaridad, estado de Quintana Roo el día {$fecha->format('d-m-Y')} la cantidad de: </p><p>Valor recibido a mi (nuestra entera satisfacción). Este pagaré forma pare te una serie numerdada y todos estan sujetos a la condición de que, al no pagarse cualquiera de ellos a su vencimiento, serán exigibles todos los que le sigan en número, además de los ya vencidos, desde la fecha de vencimiento de este documento hasta el día de su liquidación, causará intereses moratorios al tipo de por cada día de de pago incumplido, pagado en esta ciudad.</p>
                                                </div>
                                                <div class='pagare__footer'>
                                                    <p>DEUDOR:</p>
                                                    <br>
                                                    <p><strong></strong></p>
                                                    <p class='copy'>Original</p>
                                                </div>
                                            </div>
                                        </td>";
                        $count++;
                    } else if($count == 2) {
                        $pagares.="     <td>
                                            <div class='pagare'>
                                                <div class='pagare__header'>
                                                    <h3> RECIBO DE DINERO No. {$n} &nbsp;<span>FECHA: {$fecha->format('d-m-Y')}</span></h3>
                                                </div>
                                                <div class='pagare__body'>
                                                    <p>Debe(mos) y pagare(mos) incondicionalmente por este Pagaré a la orden de FRANCISCO ENRIQUE MARTINEZ CORDERO en Playa del carmen, municipio de Solidaridad, estado de Quintana Roo el día {$fecha->format('d-m-Y')} la cantidad de:<strong>&nbsp;$".number_format($historial->abono,2)." PESOS 00/100 M.N</strong></p><p>Valor recibido a mi (nuestra entera satisfacción). Este pagaré forma pare te una serie numerdada de 1 al {$n_historial} y todos estan sujetos a la condición de que, al no pagarse cualquiera de ellos a su vencimiento, serán exigibles todos los que le sigan en número, además de los ya vencidos, desde la fecha de vencimiento de este documento hasta el día de su liquidación, causará intereses moratorios al tipo de {$historial->porcentaje_penalizacion}% por cada día de de pago incumplido, pagado en esta ciudad.</p>
                                                </div>
                                                <div class='pagare__footer'>
                                                    <p>DEUDOR:</p>
                                                    <br>
                                                    <p><strong>{$historial->nombre_cliente}</strong></p>
                                                    <p class='copy'>Original</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>";
                            $count = 1;
                            
                    }
                 */                    
            }
            //Si al terminar no se han cerrado todas las etiquetas de td, cerrar
            /*if($count==2){
                $pagares.="<td></td></tr>";
            }*/
            $html = "<html><head><title>Pagarés</title><link rel='stylesheet' type='text/css' href='".base_url().'assets/css/pagares.min.css'."' /></head><body>
                
                    {$pagares}                
                
                </body>
            </html>";

            $option = array('optimizationLevel' => HTMLMinify::OPTIMIZATION_ADVANCED);
            $HTMLMinify = new HTMLMinify($html,$option);
            $output = $HTMLMinify->process();            
            $options = new Options();
            $options->set('isRemoteEnabled', TRUE);
            /*$options->set('DOMPDF_ENABLE_CSS_FLOAT', TRUE);*/
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($output);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('letter', 'portrait');
            // Render the HTML as PDF
            $dompdf->render();
            // Output the generated PDF to Browser
            $dompdf->stream('historial',array('Attachment'=>0));
        }                                            
        
    }
    public function recibos($id){
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);
        set_time_limit(300);
        $condicion = ['historial.id_venta' => $id];
        $historials = $this->Historial_model ->select("historial.abono,historial.concepto,historial.fecha,CONCAT(users.first_name,' ',users.last_name) as nombre_cliente,  ventas.version")
                                            ->join('ventas','historial.id_venta = ventas.id_venta','left')
                                            ->join('users','ventas.id_cliente = users.id','left')
                                            //->join('huertos_ventas','historial.id_venta = huertos_ventas.id_venta','left')
                                            //->join('huertos','huertos_ventas.id_venta = huertos.id_huerto','left')
                                            ->where($condicion)
                                            ->get();               
        if(count($historials)){
            $condicion = ['huertos_ventas.id_venta' => $id];
            $huertos_from_venta = $this->HuertosVentas_model->select('huertos.huerto,manzanas.manzana')
                                                        ->join('huertos','huertos_ventas.id_huerto = huertos.id_huerto','left')
                                                        ->join('manzanas',' manzanas.id_manzana = huertos.id_manzana','left')
                                                        ->where($condicion)
                                                        ->get();
            $txt_huertos = "";
            if(count($huertos_from_venta)){
                foreach($huertos_from_venta as $key => $huerto){
                    if($key > 0){
                        $txt_huertos.= ',';
                    }
                    $txt_huertos.= "No. {$huerto->huerto} MZ. {$huerto->manzana}";
                }
            }       
                                     
            $recibos = "";
            $count = 1;
            $n = "";
            $n_historial = "";
            $n_h = count($historials);
            $de = "";
            foreach ($historials as $key => $historial) {
                $fecha = Carbon::createFromFormat('Y-m-d', $historial->fecha);
                $words = explode(" ", trim($historial->nombre_cliente));
                $acronym = "";         
                if($historial->concepto == "ENGANCHE"){
                    $n = "";
                    $de = "";
                }else{
                    $n = str_replace("PAGO","",$historial->concepto);
                    if($key == 0){
                        $n_historial = $n_h - 1;
                        $n_historial += $n;
                        $de = "de";
                    }
                    if($key == 1){
                        $n_historial = $n_h - 2;
                        $n_historial += $n;
                        $de = "de";
                    }
                }
                foreach ($words as $w) {
                    if(isset($w[0]) && !empty($w[0]) ) {
                        $acronym .= $w[0];
                    }                   
                }
                if ($count == 1) {                    
                    $recibos.="<tr>
                                    <td>
                                        <div class='pagare'>                            
                                        <div class='pagare__header'>
                                            <h3>Recibo de Dinero {$n} {$de} {$n_historial} <strong>&nbsp;de fecha : {$fecha->format('d-m-Y')}</strong></h3>
                                            <p><strong>FOLIO:".strtoupper($acronym)."-{$n}-{$historial->fecha}</strong></p>
                                        </div>
                                        <div class='pagare__body'>
                                            <p>
                                                RECIBI: DEL(A) C. <strong>{$historial->nombre_cliente}</strong> LA CANTIDAD DE<strong>&nbsp;$ ".number_format($historial->abono,2)." PESOS 00/100 M.N</strong> POR CONCEPTO DE PAGO PARCIAL DE LA CESION PRIVADA , DE DERECHOS EN CO-PROPIEDAD DEL TERRENO EN BREÑA {$txt_huertos} DEL PREDIO 'LA CEIBA', UBICADO EN EL MUNICIPIO DE LAZARO CARDENAS, QUINTANA ROO.
                                            </p>
                                        </div>
                                        <div class='pagare__footer'>
                                            <p>RECIBI:</p>
                                            <br>
                                            <p><strong>FRANCISCO ENRIQUE MARTINEZ CORDERO</strong></p>
                                            <p class='copy'>Original</p>
                                        </div>
                                    </div>
                                    </td>";
                    $count++;
                } else if($count == 2) {
                    $recibos.="     <td>
                                        <div class='pagare'>                            
                                        <div class='pagare__header'>
                                            <h3>Recibo de Dinero {$n} {$de} {$n_historial} <strong>&nbsp;de fecha : {$fecha->format('d-m-Y')}</strong></h3>
                                            <p><strong>FOLIO:".strtoupper($acronym)."-{$n}-{$historial->fecha}</strong></p>
                                        </div>
                                        <div class='pagare__body'>
                                            <p>
                                                RECIBI: DEL(A) C. <strong>{$historial->nombre_cliente}</strong> LA CANTIDAD DE <strong>$ ".number_format($historial->abono,2)." PESOS 00/100 M.N</strong> POR CONCEPTO DE PAGO PARCIAL DE LA CESION PRIVADA , DE DERECHOS EN CO-PROPIEDAD DEL TERRENO EN BREÑA {$txt_huertos} DEL PREDIO 'LA CEIBA', UBICADO EN EL MUNICIPIO DE LAZARO CARDENAS, QUINTANA ROO.
                                            </p>
                                        </div>
                                        <div class='pagare__footer'>
                                            <p>RECIBI:</p>
                                            <br>
                                            <p><strong>FRANCISCO ENRIQUE MARTINEZ CORDERO</strong></p>
                                            <p class='copy'>Original</p>
                                        </div>
                                    </div>
                                    </td>
                                </tr>";
                    $count = 1;
                        
                }                
                         
            }
            if($count==2){
                $recibos.="<td></td></tr>";
            }
           
            
            $html = "<html><head><title>Recibos</title></head><body>
                <link rel='stylesheet' type='text/css' href='".base_url().'assets/css/pagares.min.css'."' />
                <table>       
                    {$recibos}                
                </table>
                </body>
            </html>";

            $option = array('optimizationLevel' => HTMLMinify::OPTIMIZATION_ADVANCED);
            $HTMLMinify = new HTMLMinify($html,$option);
            $output = $HTMLMinify->process();
            
            $options = new Options();
            $options->set('isRemoteEnabled', TRUE);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($output);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('letter', 'portrait');
            // Render the HTML as PDF
            $dompdf->render();
            // Output the generated PDF to Browser
            $dompdf->stream('recibos',array('Attachment'=>1));
        }
    }
}
