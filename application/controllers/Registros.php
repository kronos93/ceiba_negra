<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Carbon\Carbon;

class Registros extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Manzana_model');
        $this->load->model('Precio_model');
        $this->load->model('Historial_model');
        $this->load->model('Opciones_ingreso_model');
    }
    public function manzanas()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['title'] = "Manzanas"; //Titulo de la página -> require
            $data['body'] = "registros/manzanas";  //Nombre de la vista de cuerpo -> require
            $this->load->view('templates/template', $data);  //Combina header y footer con body
        }
    }
    public function huertos()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['title'] = "Huertos";
            $data['body'] = "registros/huertos";
            $data['manzanas'] = $this->Manzana_model->select('id_manzana,manzana')
                                                    ->get_();
            $data['precios'] = $this->Precio_model->select('id_precio,enganche,abono')
                                                    ->get();
            $this->load->view('templates/template', $data);
        }
    }
    public function opciones_de_pago()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['title'] = "Opciones de pago";
            $data['body'] = "registros/opciones_de_pago";
            $this->load->view('templates/template', $data);
        }
    }
    public function opciones_de_ingreso()
    {
        if (!$this->ion_auth->logged_in()) {
        // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else {
            $data['title'] = "Opciones de ingreso";
            $data['body'] = "opciones_ingreso";
            $this->load->view('templates/template', $data);
        }
    }
    public function ingresos($name = '', $id = '', $init_date = '', $end_date = '', $option = '')
    {

        if (!$this->ion_auth->logged_in()) {
        // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else {
            if (empty($id) && empty($name)) {
                $data['title'] = "Ingreso";
                $data['body'] = "opciones_ingreso";
                $this->load->view('templates/template', $data);
            } else {
                if (empty($id) || empty($name)) {
                    show_404();
                }
                $data['title'] = "Ingresos";
                $data['body'] = "ingresos";
                $ingreso = $this->Opciones_ingreso_model->from()->db
                                                        ->select('nombre, cuenta, tarjeta')
                                                        ->where(['id_opcion_ingreso' => $id])
                                                        ->get()
                                                        ->result();
                $ingreso = array_pop($ingreso);
                if (!empty($init_date) && !empty($end_date)) {
                    if (empty($init_date) || empty($end_date)) {
                        show_404();
                    }
                    $data['init_date'] = Carbon::createFromFormat('d-m-Y', $init_date);
                    $data['end_date'] = Carbon::createFromFormat('d-m-Y', $end_date);
                    $data['ingresos'] = $this->Historial_model->select('CONCAT(users.first_name, " ", users.last_name) as nombre_cliente,
                                                                        opciones_ingreso.nombre,
                                                                        historial.id_historial,
                                                                        historial.id_venta,
                                                                        historial.pago,
                                                                        historial.penalizacion,
                                                                        historial.comision,
                                                                        DATE_FORMAT(historial.fecha_pago,"%d-%m-%Y") AS fecha_pago,
                                                                        GROUP_CONCAT(DISTINCT manzanas.manzana ORDER BY  manzanas.manzana ASC) as manzanas,
                                                                        GROUP_CONCAT("Mz. ",manzanas.manzana, " Ht. ", huertos.huerto ORDER BY  manzanas.manzana ASC) as descripcion ')
                                                                ->join('ventas', 'historial.id_venta = ventas.id_venta', 'left')
                                                                ->join('users', 'ventas.id_cliente = users.id', 'left')
                                                                ->join('opciones_ingreso', 'historial.id_ingreso = opciones_ingreso.id_opcion_ingreso', 'left')

                                                                ->join('huertos_ventas', 'ventas.id_venta = huertos_ventas.id_venta', 'left')
                                                                ->join('huertos', 'huertos_ventas.id_huerto = huertos.id_huerto', 'left')
                                                                ->join('manzanas', 'huertos.id_manzana = manzanas.id_manzana', 'left')

                                                                ->where(['historial.id_ingreso' => $id,"ventas.estado !=" => 3])
                                                                ->where("historial.fecha_pago BETWEEN '{$data['init_date']->format('Y-m-d')}' AND '{$data['end_date']->format('Y-m-d')}'")
                                                                ->order_by('historial.fecha_pago ASC,historial.id_historial ASC')
                                                                ->group_by('historial.id_historial')
                                                                ->get();
                } else {
                    $data['ingresos'] = $this->Historial_model->select('
                                                                        CONCAT(users.first_name, " ", users.last_name) as nombre_cliente,
                                                                        CONCAT(u.first_name, " ", u.last_name) as nombre_cobrador,
                                                                        opciones_ingreso.nombre,
                                                                        historial.id_historial,
                                                                        historial.id_venta,
                                                                        historial.pago,
                                                                        historial.penalizacion,
                                                                        historial.comision,
                                                                        DATE_FORMAT(historial.fecha_pago,"%d-%m-%Y") AS fecha_pago')
                                                            ->join('ventas', 'historial.id_venta = ventas.id_venta', 'left')
                                                            ->join('users as u', 'historial.id_usuario = u.id', 'left')
                                                            ->join('users', 'ventas.id_cliente = users.id', 'left')
                                                            ->join('opciones_ingreso', 'historial.id_ingreso = opciones_ingreso.id_opcion_ingreso', 'left')
                                                            ->where(['historial.id_ingreso' => $id,"ventas.estado !=" => 3])
                                                            ->order_by('historial.fecha_pago ASC,historial.id_historial ASC')
                                                            ->get();

                    $data['init_date'] = $this->Historial_model->select('fecha')
                                                               ->order_by('fecha ASC')
                                                               ->limit(1)
                                                               ->get();
                    $data['init_date'] = array_pop($data['init_date']);
                    $data['init_date'] = Carbon::createFromFormat('Y-m-d', $data['init_date']->fecha);
                    $data['end_date'] = Carbon::today();
                }

                if (empty($option)) {
                    $data['id'] = $id;
                    $data['name'] = $name;
                    $this->load->view('templates/template', $data);
                } elseif ($option == 'download_excel') {
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
                    ->setCellValue('E5', 'INFORMDE DE: '.$ingreso->nombre)
                    ->setCellValue('F6', 'Fecha')
                    ->setCellValue('G6', $init_date)
                    ->setCellValue('H6', 'al')
                    ->setCellValue('I6', $end_date);
                    if($ingreso->cuenta || $ingreso->tarjeta){
                         $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('G3', 'Banco: ')
                        ->setCellValue('G4', 'Cuenta: ')
                        ->setCellValue('G5', 'Tarjeta: ')
                        ->setCellValue('H3', $ingreso->nombre)
                        ->setCellValue('H4', $ingreso->cuenta)
                        ->setCellValue('H5', $ingreso->tarjeta);
                    }
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('E3:E5')->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('G3:G5')->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('F6')->applyFromArray($styleArray);
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle('H6')->applyFromArray($styleArray);
                    //Draw logo
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setName('Logo');
                    $objDrawing->setDescription('Logo');

                    try{
                        $logo = dirname(__FILE__) . '/../../assets/img/logos/logo.png'; // Provide path to your logo file
                    }
                    catch(Exception $e){
                        $logo = dirname(__FILE__) . '\..\..\assets\img\logos\logo.png'; // Provide path to your logo file
                    }
                    catch (Exception $e) {
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

                    /*$objD = new PHPExcel_Worksheet_HeaderFooterDrawing();
                    $objD->setName('PHPExcel logo');
                    $objD->setPath(dirname(__FILE__) . '\..\..\assets\img\logos\logo.png');
                    $objD->setHeight(36);
                    $objPHPExcel->getActiveSheet()->getHeaderFooter()->addImage($objD, PHPExcel_Worksheet_HeaderFooter::IMAGE_HEADER_LEFT);*/

                    $i = 9;
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", "Fecha");
                    $objPHPExcel->getActiveSheet()->setCellValue("B{$i}", "Contrato");
                    $objPHPExcel->getActiveSheet()->setCellValue("C{$i}", "Manzanas");
                    $objPHPExcel->getActiveSheet()->setCellValue("D{$i}", "Huertos/Descripción");
                    $objPHPExcel->getActiveSheet()->setCellValue("E{$i}", "Nombre del cliente");
                    $objPHPExcel->getActiveSheet()->setCellValue("F{$i}", "Importe");
                    $objPHPExcel->getActiveSheet()->setCellValue("G{$i}", "Comisión");
                    $objPHPExcel->getActiveSheet()->setCellValue("H{$i}", "Penalización");
                    $objPHPExcel->getActiveSheet()->setCellValue("I{$i}", "Saldo");
                    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A{$i}:I{$i}")->applyFromArray($styleArray);
                    foreach ($data['ingresos'] as $ingreso) {
                        $i++;
                        $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", $ingreso->fecha_pago);
                        $objPHPExcel->getActiveSheet()->setCellValue("B{$i}", $this->getInitials($ingreso->nombre_cliente).'-'.$ingreso->id_venta);
                        $objPHPExcel->getActiveSheet()->setCellValue("C{$i}", $ingreso->manzanas);
                        $objPHPExcel->getActiveSheet()->setCellValue("D{$i}", $ingreso->descripcion);
                        $objPHPExcel->getActiveSheet()->setCellValue("E{$i}", $ingreso->nombre_cliente);
                        $objPHPExcel->getActiveSheet()->setCellValue("F{$i}", $ingreso->pago);
                        $objPHPExcel->getActiveSheet()->getStyle("F{$i}")->getNumberFormat()
                                                                         ->setFormatCode('$#,##0.00');
                        $objPHPExcel->getActiveSheet()->setCellValue("G{$i}", $ingreso->comision);
                        $objPHPExcel->getActiveSheet()->getStyle("G{$i}")->getNumberFormat()
                                                                         ->setFormatCode('$#,##0.00');
                        $objPHPExcel->getActiveSheet()->setCellValue("H{$i}", $ingreso->penalizacion);
                        $objPHPExcel->getActiveSheet()->getStyle("H{$i}")->getNumberFormat()
                                                                         ->setFormatCode('$#,##0.00');
                        $objPHPExcel->getActiveSheet()->setCellValue("I{$i}", $ingreso->pago - $ingreso->comision + $ingreso->penalizacion);
                        $objPHPExcel->getActiveSheet()->getStyle("I{$i}")->getNumberFormat()
                                                                         ->setFormatCode('$#,##0.00');
                    }


                    // Rename worksheet
                    $objPHPExcel->getActiveSheet()->setTitle('Informe-'.$ingreso->nombre);
                    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                    $objPHPExcel->setActiveSheetIndex(0);


                    // Redirect output to a client’s web browser (Excel5)
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="Informe-'.strtolower($ingreso->nombre).'.xls"');
                    header('Cache-Control: max-age=0');
                    // If you're serving to IE 9, then the following may be needed
                    header('Cache-Control: max-age=1');
                    // If you're serving to IE over SSL, then the following may be needed
                    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                    header ('Pragma: public'); // HTTP/1.0
                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                    $objWriter->save('php://output');
                } else {
                    show_404();
                }
            }
        }
    }
    public function pagos($id_venta)
    {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else {
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
                                                            DATE_FORMAT(historial.fecha,"%d-%m-%Y") AS fecha,
                                                            historial.estado,
                                                            DATE_FORMAT(historial.fecha_pago,"%d-%m-%Y") AS fecha_pago,
                                                            IF( historial.estado = 0 , DATEDIFF( CURRENT_DATE() , historial.fecha ) , DATEDIFF( historial.fecha_pago ,historial.fecha ) ) as daysAccumulated,
                                                            historial.pago,
                                                            historial.comision,
                                                            historial.penalizacion,
                                                            (historial.pago + historial.penalizacion - historial.comision) as total,
                                                            ventas.estado AS estado_venta')
                                                    ->join('ventas', 'historial.id_venta = ventas.id_venta', 'left')
                                                    ->join('users AS cliente', 'ventas.id_cliente = cliente.id', 'left')
                                                    ->join('users AS lider', 'historial.id_lider = lider.id', 'left')
                                                    ->join('opciones_ingreso', 'historial.id_ingreso = opciones_ingreso.id_opcion_ingreso', 'left')
                                                    ->where(['historial.id_venta' => $id_venta])
                                                    ->where(['historial.estado !=' => 2])
                                                    ->get();
            foreach ($data['pagos'] as $key => $ingreso) {
                if ($ingreso->estado == 0) {
                    $fecha = Carbon::createFromFormat('d-m-Y', $ingreso->fecha);
                    $today = Carbon::createFromFormat('Y-m-d', Carbon::today()->format('Y-m-d'));
                    $data['pagos'][$key]->diff = $fecha->diff($today);
                } else {
                    $fecha = Carbon::createFromFormat('d-m-Y', $ingreso->fecha);
                    $fecha_pago = Carbon::createFromFormat('d-m-Y', $ingreso->fecha_pago);
                    $data['pagos'][$key]->diff = $fecha->diff($fecha_pago);
                }
            }
            $data['lideres'] = $this->ion_auth->users(3)->result();
            $data['ingresos'] = $this->Opciones_ingreso_model->get();
            $this->load->view('templates/template', $data);
        }
    }
    public function comisiones()
    {
        $data['title'] = "Comisiones";
        $data['body'] = "comisiones";
        $this->load->view('templates/template', $data);
    }
    public function reservas()
    {
        if (!$this->ion_auth->logged_in()) {
        // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else {
            $data['title'] = "Reservas"; //Titulo de la página -> require
            $data['body'] = "reservas";  //Nombre de la vista de cuerpo -> require
            $this->load->view('templates/template', $data);  //Combina header y footer con body
        }
    }
    public function reservas_vencidas()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $data['title'] = "Reservas vencidas"; //Titulo de la página -> require
            $data['body'] = "reservas_eliminadas";  //Nombre de la vista de cuerpo -> require
            $this->load->view('templates/template', $data);  //Combina header y footer con body
        }
    }

    public function excel()
    {

        $objPHPExcel = new PHPExcel();
        $creator = base_url();
        $lastModifiedBy = base_url();
        $styleArray = [
                        'font' => [
                            'bold' => true,
                        ]
                    ];
        // Set document properties
        $objPHPExcel->getProperties()
                                    ->setCreator($creator)
                                    ->setLastModifiedBy($lastModifiedBy)
                                    ->setTitle("Office 2007 XLSX Test Document")
                                    ->setSubject("Office 2007 XLSX Test Document")
                                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                    ->setKeywords("office 2007 openxml php")
                                    ->setCategory("Test result file");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('E3', 'HUERTOS LA CEIBA')
                    ->setCellValue('E4', 'FRANCISCO ENRIQUE MARTINEZ CORDERO')
                    ->setCellValue('E5', 'INFORMDE DE: ');
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('E3:E5')->applyFromArray($styleArray);
        //Draw logo
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo');

        $logo = dirname(__FILE__) . '\..\..\assets\img\logos\logo.png'; // Provide path to your logo file
        $objDrawing->setPath($logo);
        /*$objDrawing->setOffsetX(8);    // setOffsetX works properly
        $objDrawing->setOffsetY(300);  //setOffsetY has no effect*/
        $objDrawing->setCoordinates('A1');
        $objDrawing->setHeight(94); // logo height
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Registros');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    public function getInitials($name){
        //split name using spaces
        $words=explode(" ",trim($name));
        $inits='';
        //loop through array extracting initial letters
            foreach($words as $word){
            $inits.=strtoupper(substr($word,0,1));
            }
        return $inits;
    }
    public function celebraciones() {
      if (!$this->ion_auth->logged_in()) {
        redirect('auth/login', 'refresh');
      } else {
          $data['title'] = "Cumpleaños"; //Titulo de la página -> require
          $data['body'] = "registros/birthdays";  //Nombre de la vista de cuerpo -> require
          $this->load->view('templates/template', $data);  //Combina header y footer con body
      }
    }
}
