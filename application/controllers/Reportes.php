<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Carbon\Carbon;
use Dompdf\Dompdf;
use zz\Html\HTMLMinify;
use Dompdf\Options as Options;

class Reportes extends CI_Controller
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
                                            ->where(['historial.estado !=' => 2])
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
                                                <p> EEste pagaré forma parte de una serie numerada de 1 al {$n_historial}  y todos estan sujetos a la condición de que, al no pagarse cualquiera de ellos a su vencimiento, serán exigibles todos los que le sigan en número, además de los ya vencidos,
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
            $options->set('defaultFont', 'Helvetica');
            $dompdf = new Dompdf($options);
            $dompdf->set_option('isHtml5ParserEnabled', true);
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
        $historials = $this->Historial_model->select("historial.abono,historial.concepto,historial.fecha,CONCAT(users.first_name,' ',users.last_name) as nombre_cliente,  ventas.version")
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
                                            <td><strong>FRANCISCO ENRIQUE MARTINEZ CORDERO</strong></td>
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
                    /*$recibos .= "<div style='page-break-before:always;'></div>";*/
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
            $options->set('defaultFont', 'Helvetica');
            $dompdf = new Dompdf($options);
            $dompdf->set_option('isHtml5ParserEnabled', true);
            $dompdf->loadHtml($output);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('letter', 'portrait');
            // Render the HTML as PDF
            $dompdf->render();
            // Output the generated PDF to Browser
            $dompdf->stream("Recibos-{$nombre_cliente}", array('Attachment'=>1));
        }
    }
    public function estado_de_cuenta($id)
    {
        $historials = $this->Historial_model->from()->db
                                            ->select("
                                                concepto, fecha, abono
                                            ")
                                            ->where(['id_venta' => $id])
                                            ->where(['estado' => 0])
                                            ->get()
                                            ->result();
        $venta = $this->Venta_model->from()->db
                                     ->select("
                                        CONCAT(users.first_name, ' ', users.last_name) AS nombre_cliente, 
                                        ventas.id_venta,
                                        ventas.porcentaje_penalizacion,
                                        GROUP_CONCAT(DISTINCT manzanas.manzana ORDER BY  manzanas.manzana ASC) as manzanas, 
                                        GROUP_CONCAT('Mz. ',manzanas.manzana,   ' Ht. ', huertos.huerto ORDER BY  manzanas.manzana ASC) as descripcion
                                     ")
                                     ->join('users', 'ventas.id_cliente = users.id', 'left')
                                     
                                     ->join('huertos_ventas', 'ventas.id_venta = huertos_ventas.id_venta', 'left')
                                     ->join('huertos', 'huertos_ventas.id_huerto = huertos.id_huerto', 'left')
                                     ->join('manzanas', 'huertos.id_manzana = manzanas.id_manzana', 'left')

                                     ->where(['ventas.id_venta' => $id])
                                     /*->group_by('ventas.id_vental')*/
                                     ->get()
                                     ->result();

        $venta = array_pop($venta);
        $objPHPExcel = new PHPExcel();
                   
        $creator = base_url();
        $lastModifiedBy = base_url();
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ];
        // Set document properties
        $objPHPExcel->getProperties()
                        ->setCreator($creator)
                        ->setLastModifiedBy($lastModifiedBy)
                        ->setTitle("")
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('C3', 'HUERTOS LA CEIBA')
        ->setCellValue('C4', 'ESTADO DE CUENTA')
        ->setCellValue('A7', 'Cliente: '.$venta->nombre_cliente)
        ->setCellValue('A8', 'Contrato: '.$this->utils->getInitials($venta->nombre_cliente).'-'.$venta->id_venta)
        ->setCellValue('A9', 'Manzana (s): '.$venta->manzanas)
        ->setCellValue('E7', 'Fecha de emisión: '.Carbon::today()->format('d-m-Y'))
        ->setCellValue('E9', 'Huertos (s): '.$venta->descripcion);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('C3:C4')->applyFromArray($styleArray);
        
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:D7');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A8:D8');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A9:D9');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E7:G7');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E9:G9');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:E3');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C4:E4');
        
        $i=11;
        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            )
        );
        $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", "Quincena");
        $objPHPExcel->getActiveSheet()->setCellValue("B{$i}", "Fecha");
        $objPHPExcel->getActiveSheet()->setCellValue("C{$i}", "Cantidad a pagar");
        $objPHPExcel->getActiveSheet()->setCellValue("D{$i}", "Días retrasados");
        $objPHPExcel->getActiveSheet()->setCellValue("E{$i}", "% de penalización por día retrasado");
        $objPHPExcel->getActiveSheet()->setCellValue("F{$i}", "Penalización por día retrasado");
        $objPHPExcel->getActiveSheet()->setCellValue("G{$i}", "Penalización por días retrasado");
        $objPHPExcel->getActiveSheet()->setCellValue("H{$i}", "Total a pagar");
        $objPHPExcel->getActiveSheet()->getStyle("A{$i}:H{$i}")->applyFromArray($style);
        $now =Carbon::now();
        $total_p = 0;
        $total_a = 0;
        foreach ($historials as $historial) {
            $fecha = Carbon::createFromFormat('Y-m-d', $historial->fecha);
            $diff = $fecha->diffInDays($now, false);
            if ($diff > 0) {
                $i++;
                $porcentaje_p = $venta->porcentaje_penalizacion/100; //Porcentaje de la penalización
                $p_x_dia = $historial->abono * ($porcentaje_p);      //Cálculo de la penalización por día
                $p_t = $p_x_dia*$diff;                               //Cálculo de la penalización acumulada por días
                $total_p+= $p_t;
                $deuda = $p_t + $historial->abono;                   //Deuda total de penalizaciones más el pago pendiente
                $total_a += $historial->abono;
                $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", $historial->concepto);
                $objPHPExcel->getActiveSheet()->setCellValue("B{$i}", Carbon::createFromFormat('Y-m-d', $historial->fecha)->format('d-m-Y'));
                /*$objPHPExcel->getActiveSheet()->getStyle("B{$i}")->getNumberFormat()
                                                                 ->setFormatCode('dd/mm/yyyy');*/
                $objPHPExcel->getActiveSheet()->setCellValue("C{$i}", $historial->abono);
                $objPHPExcel->getActiveSheet()->getStyle("C{$i}")->getNumberFormat()
                                                                 ->setFormatCode('$#,##0.00');
                $objPHPExcel->getActiveSheet()->setCellValue("D{$i}", $diff);
                $objPHPExcel->getActiveSheet()->setCellValue("E{$i}", $venta->porcentaje_penalizacion);
                $objPHPExcel->getActiveSheet()->setCellValue("F{$i}", $p_x_dia);
                $objPHPExcel->getActiveSheet()->getStyle("F{$i}")->getNumberFormat()
                                                                 ->setFormatCode('$#,##0.00');
                $objPHPExcel->getActiveSheet()->setCellValue("G{$i}", $p_t);
                $objPHPExcel->getActiveSheet()->getStyle("G{$i}")->getNumberFormat()
                                                                 ->setFormatCode('$#,##0.00');
                $objPHPExcel->getActiveSheet()->setCellValue("H{$i}", $deuda);
                $objPHPExcel->getActiveSheet()->getStyle("H{$i}")->getNumberFormat()
                                                                 ->setFormatCode('$#,##0.00');
            }
        }
        $i = $i+2;
        $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", "Total de pagos retrasados: ");
        $objPHPExcel->getActiveSheet()->setCellValue("C{$i}", $total_a);
        $objPHPExcel->getActiveSheet()->getStyle("C{$i}")->getNumberFormat()
                                                                 ->setFormatCode('$#,##0.00');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A{$i}:B{$i}");
        $objPHPExcel->getActiveSheet()->getStyle("A{$i}:C{$i}")->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(40);
        
        $i = $i+1;
        $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", "Total de las penalidades: ");
        $objPHPExcel->getActiveSheet()->setCellValue("C{$i}", $total_p);
        $objPHPExcel->getActiveSheet()->getStyle("C{$i}")->getNumberFormat()
                                                                 ->setFormatCode('$#,##0.00');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A{$i}:B{$i}");
        $objPHPExcel->getActiveSheet()->getStyle("A{$i}:C{$i}")->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(40);

        $i = $i+1;
        $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", "Total a pagar: ");
        $objPHPExcel->getActiveSheet()->setCellValue("C{$i}", $total_p + $total_a);
        $objPHPExcel->getActiveSheet()->getStyle("C{$i}")->getNumberFormat()
                                                                 ->setFormatCode('$#,##0.00');
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells("A{$i}:B{$i}");
        $objPHPExcel->setActiveSheetIndex(0)->getStyle("A{$i}:C{$i}")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle("A{$i}:C{$i}")->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(40);
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Estado_de_cuenta');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo');
        try {
            $logo = dirname(__FILE__) . '/../../assets/img/logos/logo.png'; // Provide path to your logo file
        } catch (Exception $e) {
            $logo = dirname(__FILE__) . '\..\..\assets\img\logos\logo.png'; // Provide path to your logo file
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        }
        
        $objDrawing->setPath($logo);
        $objDrawing->setCoordinates('A1');
        $objDrawing->setWidthAndHeight(90,90);
        $objDrawing->setResizeProportional(false);
        $objDrawing->getShadow()->setVisible(true);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Estado_de_cuenta_'.strtolower(str_replace(' ', '_', $venta->nombre_cliente)).'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function huertos_disponibles()
    {
        $huertos_disponibles = $this->Manzana_model->from()->db
                                                   ->select('manzanas.manzana, huertos.huerto,huertos.superficie, huertos.precio_x_m2, (huertos.superficie * huertos.precio_x_m2) AS precio')
                                                   ->join('huertos', 'manzanas.id_manzana = huertos.id_manzana', 'inner')
                                                   ->where(['manzanas.disponibilidad >' => 0]) //Manzana disponible
                                                   ->where(['huertos.vendido' => 0]) //Huerto disponible
                                                   ->order_by('manzanas.manzana ASC, huertos.huerto ASC')
                                                   ->get()
                                                   ->result();

        $objPHPExcel = new PHPExcel();
                   
        $creator = base_url();
        $lastModifiedBy = base_url();
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ];
        // Set document properties
        $objPHPExcel->getProperties()
                        ->setCreator($creator)
                        ->setLastModifiedBy($lastModifiedBy)
                        ->setTitle("")
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('E3', 'HUERTOS LA CEIBA')
        ->setCellValue('E4', 'FRANCISCO ENRIQUE MARTINEZ CORDERO')
        ->setCellValue('E6', 'Huertos disponibles')
        ->setCellValue('F6', 'Fecha de emisión')
        ->setCellValue('G6', Carbon::today()->format('d-m-Y'));
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E3:E6')->applyFromArray($styleArray);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('F6')->applyFromArray($styleArray);
        //Draw logo
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo');
        
        try {
            $logo = dirname(__FILE__) . '/../../assets/img/logos/logo.png'; // Provide path to your logo file
        } catch (Exception $e) {
            $logo = dirname(__FILE__) . '\..\..\assets\img\logos\logo.png'; // Provide path to your logo file
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        }
        
        $objDrawing->setPath($logo);
        /*$objDrawing->setOffsetX(8);    // setOffsetX works properly
        $objDrawing->setOffsetY(300);  //setOffsetY has no effect*/
        $objDrawing->setCoordinates('A1');
        $objDrawing->setHeight(160); // logo height
        $objDrawing->setOffsetX(8);
        $objDrawing->getShadow()->setVisible(true);
        $objDrawing->getShadow()->setDirection(45);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

        $i = 11;
        $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", "Manzana");
        $objPHPExcel->getActiveSheet()->setCellValue("B{$i}", "Huerto");
        $objPHPExcel->getActiveSheet()->setCellValue("C{$i}", "Superficie");
        $objPHPExcel->getActiveSheet()->setCellValue("D{$i}", "Precio por m^2");
        $objPHPExcel->getActiveSheet()->setCellValue("E{$i}", "Precio");
        $objPHPExcel->setActiveSheetIndex(0)->getStyle("A{$i}:E{$i}")->applyFromArray($styleArray);
        foreach ($huertos_disponibles as $huerto) {
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", $huerto->manzana);
            $objPHPExcel->getActiveSheet()->setCellValue("B{$i}", $huerto->huerto);
            $objPHPExcel->getActiveSheet()->getStyle("B{$i}")->getNumberFormat()
                                          ->setFormatCode(
                                                PHPExcel_Style_NumberFormat::FORMAT_TEXT
                                            );
            $objPHPExcel->getActiveSheet()->setCellValue("C{$i}", $huerto->superficie);
            $objPHPExcel->getActiveSheet()->setCellValue("D{$i}", $huerto->precio_x_m2);
            $objPHPExcel->getActiveSheet()->getStyle("D{$i}")->getNumberFormat()
                                          ->setFormatCode('$#,##0.00');
            $objPHPExcel->getActiveSheet()->setCellValue("E{$i}", $huerto->precio);
            $objPHPExcel->getActiveSheet()->getStyle("E{$i}")->getNumberFormat()
                                          ->setFormatCode('$#,##0.00');
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('huertos_disponibles');
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="huertos_disponibles.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function huertos_cancelados()
    {
        $ventas_canceladas = $this->Venta_model->from()->db
                                               ->select('ventas.id_venta, 
                                                         CONCAT(users.first_name, " ", users.last_name) AS nombre,
                                                         IF(historial.estado = 1, SUM(historial.pago),SUM(0)) AS pagado,
                                                         IF(historial.estado = 1, SUM(historial.comision),SUM(0)) AS comision,
                                                         IF(historial.estado = 1, SUM(historial.penalizacion),SUM(0)) AS penalizacion,
                                                         historial.fecha')
                                               ->join('users','ventas.id_cliente = users.id','inner')
                                               ->join('historial','ventas.id_venta = historial.id_venta','inner')
                                               ->where(["ventas.estado" => 2])
                                               ->group_by('ventas.id_venta')
                                               ->order_by('historial.id_historial ASC')
                                               ->get_compiled_select();
        $huertos_cancelados = $this->Venta_model->db
                                                ->select('
                                                            ventas.id_venta, 
                                                            ventas.nombre, 
                                                            ventas.pagado, 
                                                            ventas.comision, 
                                                            ventas.penalizacion, 
                                                            ventas.fecha,
                                                            GROUP_CONCAT(DISTINCT manzanas.manzana ORDER BY  manzanas.manzana ASC) as manzanas, 
                                                            GROUP_CONCAT("Mz. ",manzanas.manzana, " Ht. ", huertos.huerto ORDER BY  manzanas.manzana ASC) as descripcion ')
                                                ->from("({$ventas_canceladas}) AS ventas")
                                                ->join('huertos_ventas' ,'ventas.id_venta = huertos_ventas.id_venta','inner')
                                                ->join('huertos' ,'huertos_ventas.id_huerto = huertos.id_huerto','inner')
                                                ->join('manzanas' ,'huertos.id_manzana = manzanas.id_manzana','inner')
                                                ->group_by('ventas.id_venta')
                                                ->get()
                                                ->result();                                   
        $objPHPExcel = new PHPExcel();
                   
        $creator = base_url();
        $lastModifiedBy = base_url();
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ];
        // Set document properties
        $objPHPExcel->getProperties()
                        ->setCreator($creator)
                        ->setLastModifiedBy($lastModifiedBy)
                        ->setTitle("")
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('E3', 'HUERTOS LA CEIBA')
        ->setCellValue('E4', 'FRANCISCO ENRIQUE MARTINEZ CORDERO')
        ->setCellValue('E6', 'Huertos cancelados')
        ->setCellValue('F6', 'Fecha de emisión')
        ->setCellValue('G6', Carbon::today()->format('d-m-Y'));
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E3:E6')->applyFromArray($styleArray);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('F6')->applyFromArray($styleArray);
        //Draw logo
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo');
        
        try {
            $logo = dirname(__FILE__) . '/../../assets/img/logos/logo.png'; // Provide path to your logo file
        } catch (Exception $e) {
            $logo = dirname(__FILE__) . '\..\..\assets\img\logos\logo.png'; // Provide path to your logo file
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        }
        
        $objDrawing->setPath($logo);
        /*$objDrawing->setOffsetX(8);    // setOffsetX works properly
        $objDrawing->setOffsetY(300);  //setOffsetY has no effect*/
        $objDrawing->setCoordinates('A1');
        $objDrawing->setHeight(160); // logo height
        $objDrawing->setOffsetX(8);
        $objDrawing->getShadow()->setVisible(true);
        $objDrawing->getShadow()->setDirection(45);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

        $i=11;
        $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", "Fecha");
        $objPHPExcel->getActiveSheet()->setCellValue("B{$i}", "Nombre");
        $objPHPExcel->getActiveSheet()->setCellValue("C{$i}", "Contrato");
        $objPHPExcel->getActiveSheet()->setCellValue("D{$i}", "Manzanas");
        $objPHPExcel->getActiveSheet()->setCellValue("E{$i}", "Huertos");
        $objPHPExcel->getActiveSheet()->setCellValue("F{$i}", "Pagado");    
        $objPHPExcel->getActiveSheet()->setCellValue("G{$i}", "Comisiones");
        $objPHPExcel->getActiveSheet()->setCellValue("H{$i}", "Penalizaciones");
        $objPHPExcel->getActiveSheet()->setCellValue("I{$i}", "Ingreso total");
        $objPHPExcel->setActiveSheetIndex(0)->getStyle("A{$i}:I{$i}")->applyFromArray($styleArray);
        foreach($huertos_cancelados as $huertos){
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", Carbon::createFromFormat('Y-m-d', $huertos->fecha)->format('d-m-Y'));
            $objPHPExcel->getActiveSheet()->setCellValue("B{$i}",  $huertos->nombre);
            $objPHPExcel->getActiveSheet()->setCellValue("C{$i}",  $this->utils->getInitials($huertos->nombre).'-'.$huertos->id_venta);
            $objPHPExcel->getActiveSheet()->setCellValue("D{$i}",  $huertos->manzanas);
            $objPHPExcel->getActiveSheet()->setCellValue("E{$i}",  $huertos->descripcion);
            $objPHPExcel->getActiveSheet()->setCellValue("F{$i}",  $huertos->pagado);
            $objPHPExcel->getActiveSheet()->getStyle("F{$i}")->getNumberFormat()
                                          ->setFormatCode('$#,##0.00');
            $objPHPExcel->getActiveSheet()->setCellValue("G{$i}",  $huertos->comision);
            $objPHPExcel->getActiveSheet()->getStyle("G{$i}")->getNumberFormat()
                                          ->setFormatCode('$#,##0.00');
            $objPHPExcel->getActiveSheet()->setCellValue("H{$i}",  $huertos->penalizacion);
            $objPHPExcel->getActiveSheet()->getStyle("H{$i}")->getNumberFormat()
                                          ->setFormatCode('$#,##0.00');
            $objPHPExcel->getActiveSheet()->setCellValue("I{$i}",  $huertos->pagado - $huertos->comision + $huertos->penalizacion);
            $objPHPExcel->getActiveSheet()->getStyle("I{$i}")->getNumberFormat()
                                          ->setFormatCode('$#,##0.00');
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('huertos_cancelados');
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="huertos_cancelados.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function huertos_vendidos()
    {
        $ventas_canceladas = $this->Venta_model->from()->db
                                               ->select('ventas.id_venta, 
                                                         CONCAT(users.first_name, " ", users.last_name) AS nombre,
                                                         IF(historial.estado = 1, SUM(historial.pago),SUM(0)) AS pagado,
                                                         IF(historial.estado = 1, SUM(historial.comision),SUM(0)) AS comision,
                                                         IF(historial.estado = 1, SUM(historial.penalizacion),SUM(0)) AS penalizacion,
                                                         historial.fecha')
                                               ->join('users','ventas.id_cliente = users.id','inner')
                                               ->join('historial','ventas.id_venta = historial.id_venta','inner')
                                               ->where("ventas.estado = 0 OR ventas.estado = 1")
                                               ->group_by('ventas.id_venta')
                                               ->order_by('historial.id_historial ASC')
                                               ->get_compiled_select();
        $huertos_cancelados = $this->Venta_model->db
                                                ->select('
                                                            ventas.id_venta, 
                                                            ventas.nombre, 
                                                            ventas.pagado, 
                                                            ventas.comision, 
                                                            ventas.penalizacion, 
                                                            ventas.fecha,
                                                            GROUP_CONCAT(DISTINCT manzanas.manzana ORDER BY  manzanas.manzana ASC) as manzanas, 
                                                            GROUP_CONCAT("Mz. ",manzanas.manzana, " Ht. ", huertos.huerto ORDER BY  manzanas.manzana ASC) as descripcion ')
                                                ->from("({$ventas_canceladas}) AS ventas")
                                                ->join('huertos_ventas' ,'ventas.id_venta = huertos_ventas.id_venta','inner')
                                                ->join('huertos' ,'huertos_ventas.id_huerto = huertos.id_huerto','inner')
                                                ->join('manzanas' ,'huertos.id_manzana = manzanas.id_manzana','inner')
                                                ->group_by('ventas.id_venta')
                                                ->get()
                                                ->result();       
        $objPHPExcel = new PHPExcel();
                   
        $creator = base_url();
        $lastModifiedBy = base_url();
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ];
        // Set document properties
        $objPHPExcel->getProperties()
                        ->setCreator($creator)
                        ->setLastModifiedBy($lastModifiedBy)
                        ->setTitle("")
                        ->setSubject("")
                        ->setDescription("")
                        ->setKeywords("")
                        ->setCategory("");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('E3', 'HUERTOS LA CEIBA')
        ->setCellValue('E4', 'FRANCISCO ENRIQUE MARTINEZ CORDERO')
        ->setCellValue('E6', 'Huertos vendidos')
        ->setCellValue('F6', 'Fecha de emisión')
        ->setCellValue('G6', Carbon::today()->format('d-m-Y'));
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E3:E6')->applyFromArray($styleArray);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('F6')->applyFromArray($styleArray);
        //Draw logo
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo');
        
        try {
            $logo = dirname(__FILE__) . '/../../assets/img/logos/logo.png'; // Provide path to your logo file
        } catch (Exception $e) {
            $logo = dirname(__FILE__) . '\..\..\assets\img\logos\logo.png'; // Provide path to your logo file
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        }
        
        $objDrawing->setPath($logo);
        /*$objDrawing->setOffsetX(8);    // setOffsetX works properly
        $objDrawing->setOffsetY(300);  //setOffsetY has no effect*/
        $objDrawing->setCoordinates('A1');
        $objDrawing->setHeight(160); // logo height
        $objDrawing->setOffsetX(8);
        $objDrawing->getShadow()->setVisible(true);
        $objDrawing->getShadow()->setDirection(45);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

        $i=11;
        $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", "Fecha");
        $objPHPExcel->getActiveSheet()->setCellValue("B{$i}", "Nombre");
        $objPHPExcel->getActiveSheet()->setCellValue("C{$i}", "Contrato");
        $objPHPExcel->getActiveSheet()->setCellValue("D{$i}", "Manzanas");
        $objPHPExcel->getActiveSheet()->setCellValue("E{$i}", "Huertos");
        $objPHPExcel->getActiveSheet()->setCellValue("F{$i}", "Pagado");    
        $objPHPExcel->getActiveSheet()->setCellValue("G{$i}", "Comisiones");
        $objPHPExcel->getActiveSheet()->setCellValue("H{$i}", "Penalizaciones");
        $objPHPExcel->getActiveSheet()->setCellValue("I{$i}", "Ingreso total");
        $objPHPExcel->setActiveSheetIndex(0)->getStyle("A{$i}:I{$i}")->applyFromArray($styleArray);
        foreach($huertos_cancelados as $huertos){
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", Carbon::createFromFormat('Y-m-d', $huertos->fecha)->format('d-m-Y'));
            $objPHPExcel->getActiveSheet()->setCellValue("B{$i}",  $huertos->nombre);
            $objPHPExcel->getActiveSheet()->setCellValue("C{$i}",  $this->utils->getInitials($huertos->nombre).'-'.$huertos->id_venta);
            $objPHPExcel->getActiveSheet()->setCellValue("D{$i}",  $huertos->manzanas);
            $objPHPExcel->getActiveSheet()->setCellValue("E{$i}",  $huertos->descripcion);
            $objPHPExcel->getActiveSheet()->setCellValue("F{$i}",  $huertos->pagado);
            $objPHPExcel->getActiveSheet()->getStyle("F{$i}")->getNumberFormat()
                                          ->setFormatCode('$#,##0.00');
            $objPHPExcel->getActiveSheet()->setCellValue("G{$i}",  $huertos->comision);
            $objPHPExcel->getActiveSheet()->getStyle("G{$i}")->getNumberFormat()
                                          ->setFormatCode('$#,##0.00');
            $objPHPExcel->getActiveSheet()->setCellValue("H{$i}",  $huertos->penalizacion);
            $objPHPExcel->getActiveSheet()->getStyle("H{$i}")->getNumberFormat()
                                          ->setFormatCode('$#,##0.00');
            $objPHPExcel->getActiveSheet()->setCellValue("I{$i}",  $huertos->pagado - $huertos->comision + $huertos->penalizacion);
            $objPHPExcel->getActiveSheet()->getStyle("I{$i}")->getNumberFormat()
                                          ->setFormatCode('$#,##0.00');
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('huertos_vendidos');
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="huertos_vendidos.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}