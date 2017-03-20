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
    public function contrato($id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);
        set_time_limit(300);
        $condicion = ['ventas.id_venta' => $id];
        $ventas = $this->Venta_model
                                    ->select("contrato_html, CONCAT(users.first_name,' ',users.last_name) as nombre_cliente")
                                    ->where($condicion)
                                    ->join('users', 'ventas.id_cliente = users.id', 'left')
                                    ->get();
        $html = "";
        foreach ($ventas as $venta) {
            $html.= "<html><head><meta charset='utf-8'><title>Contrato-{$venta->nombre_cliente}</title></head><body>";
            $html.= "<link rel='stylesheet' type='text/css' href='".base_url().'assets/css/tinymce.min.css'."' />";
            $html.= "<div class='mce-content-body'>";
            $html.= $venta->contrato_html;
            $html.= "</div>";
            $html.= "</body></html>";
                      
            $option = array('optimizationLevel' => HTMLMinify::OPTIMIZATION_ADVANCED);
            $HTMLMinify = new HTMLMinify($html, $option);
            $output = $HTMLMinify->process();
            
            $dompdf = new Dompdf();
            $dompdf->loadHtml($output);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('legal', 'portrait');
            // Render the HTML as PDF
            $dompdf->render();
            // Output the generated PDF to Browser
            // Output the generated PDF (1 = download and 0 = preview)
            $dompdf->stream("Contrato-{$venta->nombre_cliente}", array('Attachment'=>1));
        }
    }
    public function pagares($id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);
        set_time_limit(300);
        $condicion = ['historial.id_venta' => $id];
        $historials = $this->Historial_model ->select("historial.abono,historial.fecha,CONCAT(users.first_name,' ',users.last_name) as nombre_cliente,ventas.porcentaje_penalizacion, ventas.version")
                                            ->join('ventas', 'historial.id_venta = ventas.id_venta', 'left')
                                            ->join('users', 'ventas.id_cliente = users.id', 'left')
                                            ->where($condicion)
                                            ->get();
        if (count($historials)) {
            $condicion = ['huertos_ventas.id_venta' => $id];
            $pagares = "";
            $count = 1;
            $n = 1;
            $n_historial = count($historials);
            $nombre_cliente = "";
            foreach ($historials as $key => $historial) {
                $fecha = Carbon::createFromFormat('Y-m-d', $historial->fecha);
                $nombre_cliente = $historial->nombre_cliente;
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
                                        <strong>&nbsp;$".number_format($historial->abono, 2)." PESOS 00/100 M.N</strong>,valor recibido a mi satisfacción.
                                    </p>
                                    <table>
                                        <tr>
                                            <td><strong>{$nombre_cliente}</strong></td>
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
            }
            $html = "<html>
                        <head>
                            <title>Pagarés - {$nombre_cliente}</title>
                            <link rel='stylesheet' type='text/css' href='".base_url().'assets/css/pagares.min.css'."' />
                        </head>
                        <body>
                            {$pagares}                
                        </body>
                     </html>";
            $option = array('optimizationLevel' => HTMLMinify::OPTIMIZATION_ADVANCED);
            $HTMLMinify = new HTMLMinify($html, $option);
            $output = $HTMLMinify->process();
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            /*$options->set('DOMPDF_ENABLE_CSS_FLOAT', TRUE);*/
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($output);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('letter', 'portrait');
            // Render the HTML as PDF
            $dompdf->render();
            // Output the generated PDF to Browser
            $dompdf->stream("Pagarés-{$nombre_cliente}", array('Attachment'=>1));
        }
    }
    public function recibos($id)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);
        set_time_limit(300);
        $condicion = ['historial.id_venta' => $id];
        $historials = $this->Historial_model ->select("historial.abono,historial.concepto,historial.fecha,CONCAT(users.first_name,' ',users.last_name) as nombre_cliente,  ventas.version")
                                            ->join('ventas', 'historial.id_venta = ventas.id_venta', 'left')
                                            ->join('users', 'ventas.id_cliente = users.id', 'left')
                                            ->where($condicion)
                                            ->get();
        if (count($historials)) {
            $condicion = ['huertos_ventas.id_venta' => $id];
            $huertos_from_venta = $this->HuertosVentas_model->select('huertos.huerto,manzanas.manzana')
                                                        ->join('huertos', 'huertos_ventas.id_huerto = huertos.id_huerto', 'left')
                                                        ->join('manzanas', ' manzanas.id_manzana = huertos.id_manzana', 'left')
                                                        ->where($condicion)
                                                        ->get();
            $txt_huertos = "";
            if (count($huertos_from_venta)) {
                foreach ($huertos_from_venta as $key => $huerto) {
                    if ($key > 0) {
                        $txt_huertos.= ',';
                    }
                    $txt_huertos.= "Huerto No. {$huerto->huerto} MZ. {$huerto->manzana}";
                }
            }
                                     
            $recibos = "";
            $n = "";
            $n_historial = "";
            $n_h = count($historials);
            $de = "";
            $page_break = 0;
            $nombre_cliente = "";
            foreach ($historials as $key => $historial) {
                $nombre_cliente = $historial->nombre_cliente;
                $page_break++;
                $fecha = Carbon::createFromFormat('Y-m-d', $historial->fecha);
                $words = explode(" ", trim($historial->nombre_cliente));
                $acronym = "";
                if ($historial->concepto == "ENGANCHE") {
                    $n = "";
                    $de = "";
                } else {
                    $n = str_replace("PAGO", "", $historial->concepto);
                    if ($key == 0) {
                        $n_historial = $n_h - 1;
                        $n_historial += $n;
                        $de = "de";
                    }
                    if ($key == 1) {
                        $n_historial = $n_h - 2;
                        $n_historial += $n;
                        $de = "de";
                    }
                }
                foreach ($words as $w) {
                    if (isset($w[0]) && !empty($w[0])) {
                        $acronym .= $w[0];
                    }
                }
                $recibos .= "<div class='recibo_container'>
                                <div class='pagare_talon'>
                                    <div class='pagare_talon__numero'><strong>RECIBO {$n} {$de} {$n_historial}</strong></div>
                                    <div class='pagare_talon__folio'>FOLIO: ".strtoupper($acronym)."-{$n}-{$fecha->format('d-m-Y')}</strong></div>
                                        <p><strong>Fecha de Pago:</strong><span>{$fecha->format('d-m-Y')}</span></p>
                                        <p class='texto_pagare'>
                                            RECIBI: DEL(A) <strong>C. {$nombre_cliente}</strong> LA CANTIDAD DE <strong>&nbsp;$ ".number_format($historial->abono, 2)." PESOS 00/100 M.N</strong>
                                        </p>
                                        <p>Por concepto de pago parcial de la cesion privada , de derechos en co-propiedad.</p>
                                    </div>
                                <div class='pagarecontainer recibo_body'>
                                <div class='pagarecontainer_wrap'>
                                    <div class='num_pagare'>
                                        <strong>RECIBO {$n} {$de} {$n_historial}</strong>
                                    </div>
                                    <div class='fecha_pagare'>
                                        <div class='folio_pagare'>
                                            <strong>FOLIO: ".strtoupper($acronym)."-{$n}-{$fecha->format('d-m-Y')}</strong>
                                        </div>
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
                                        RECIBI: DEL(A) <strong>{$nombre_cliente}</strong> LA CANTIDAD DE <strong>&nbsp;$ ".number_format($historial->abono, 2)." PESOS 00/100 M.N</strong>
                                    </p>
                                    <table>
                                        <tr>
                                            <td><strong>BELGIO ELIAS PINELO CASANOVA</strong></td>
                                            <td>
                                                <p> POR CONCEPTO DE PAGO PARCIAL DE LA CESION PRIVADA , DE DERECHOS EN CO-PROPIEDAD DEL TERRENO EN BREÑA {$txt_huertos} DEL PREDIO 'LA CEIBA', UBICADO EN EL MUNICIPIO DE LAZARO CARDENAS, QUINTANA ROO.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>";
                if ($page_break == 4) {
                    $recibos .= "<div style='page-break-before:always;'></div>";
                    $page_break = 0;
                }
            }
            
            $html = "<html>
                        <head>
                            <title>Recibos - {$nombre_cliente}</title>
                            <link rel='stylesheet' type='text/css' href='".base_url().'assets/css/pagares.min.css'."' />
                        </head>
                        <body>
                            {$recibos}
                        </body>
                    </html>";
            $option = array('optimizationLevel' => HTMLMinify::OPTIMIZATION_ADVANCED);
            $HTMLMinify = new HTMLMinify($html, $option);
            $output = $HTMLMinify->process();
            
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($output);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('letter', 'portrait');
            // Render the HTML as PDF
            $dompdf->render();
            // Output the generated PDF to Browser
            $dompdf->stream("Recibos-{$nombre_cliente}", array('Attachment'=>1));
        }
    }
}
