<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venta extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('Contrato_model');
        $this->load->model('Manzana_model');
        $this->load->model('Huerto_model');
    }
	public function index() {
        $data['title'] = "Venta";
		$data['body'] = "venta";
		$this->load->view('templates/template',$data);        
    }
    public function prueba() {
echo '<pre>';
$datosMz = new stdClass();
$datosMz->id_mz = [];
$datosMz->mz = [];
foreach ($this->cart->contents() as $items){

    
        $where = ['id_huerto' => $items['id_huerto']];
        $huertos = $this->Huerto_model->getHuertosPM($where,'object');
        
        foreach ($huertos as $key => $huerto) {
            if(!in_array($huerto->id_manzana, $datosMz->id_mz)){
                array_push($datosMz->id_mz, $huerto->id_manzana);
            }
        }
}

$multiple_mz = (count($datosMz->id_mz)>1) ? true : false;
$colindancias_mz = "";
$colindancias = "";
foreach($datosMz->id_mz as $id){

    $where = ['id_manzana' => $id];
    $manzanas = $this->Manzana_model->get('*',$where);
    foreach($manzanas as $manzana){
        if(!in_array($manzana->manzana, $datosMz->mz)){
            array_push($datosMz->mz, $manzana->manzana);
        }
        
        if(!empty($manzana->col_norte)){
            $colindancias .= "<strong>Al Norte</strong>, {$manzana->col_norte}; ";
        }
        if(!empty($manzana->col_noreste)){
            $colindancias .= "<strong>Al Noreste</strong>, {$manzana->col_noreste}; ";
        }
        if(!empty($manzana->col_este)){
            $colindancias .= "<strong>Al Este</strong>, {$manzana->col_este}; ";
        }
        if(!empty($manzana->col_sureste)){
            $colindancias .= "<strong>Al Sureste</strong>, {$manzana->col_sureste}; ";
        }
        if(!empty($manzana->col_sur)){
            $colindancias .= "<strong>Al Sur</strong>, {$manzana->col_sur}; ";
        }
        if(!empty($manzana->col_suroeste)){
            $colindancias .= "<strong>Al Suroeste</strong>, {$manzana->col_suroeste}; ";
        }
        if(!empty($manzana->col_noroeste)){
            $colindancias .= "<strong>Al Noroeste</strong>, {$manzana->col_noroeste}; ";
        }
        if(!$multiple_mz){
            $colindancias_mz = $colindancias;
        }else{
            $colindancias_mz .= "<div><strong>MANZANA {$manzana->manzana}</strong> {$colindancias}</div>";
            $colindancias = "";
        }

    }
}

$mz_txt = implode(',',$datosMz->mz);
echo '</pre>';

$tabla_pagare = '<table>
	<thead>
		<tr>
			<th>Concepto</th>
			<th>Monto a pagar</th>
			<th>Fecha de pago</th>
		</tr>
	</thead>
	<tbody>';
		$enganche = 10000; $pagos = 1500; $total = $enganche;
$tabla_pagare .= "<tr>"
			."<td>ENGANCHE</td>"
			."<td>$". number_format($enganche,2)."</td>"
			."<td>15-01-2017</td>"
		."</tr>";
		for($i=1; $i<=67; $i++) {
		$tabla_pagare .= '<tr>
			<td>PAGO '.$i.'</td>
			<td>$'.number_format($pagos,2).'</td>
			<td>15-0-2017</td>
		</tr>';
			$total+=$pagos;
		} 
		
$tabla_pagare .= '</tbody>
	<tfoot>
	<tr>
		<td>TOTAL:</td>
		<td>$'. number_format($total,2).'</td>
		<td></td>
	</tr>
	</tfoot>
</table>';
//Una manzana manzana
if(!$multiple_mz){

    $complemento_manzana_ii = ', que a continuación se describe';
    $complemento_manzana_v = 'motivo de la Presente Operación, no ha sido Cedida, Vendida o Comprometida en forma con anterioridad a este Acto';
    $fraccion = 'la fracción marcada';
    $numero_manzana = "el Número {$mz_txt}";
    $manzana_txt = "LA MANZANA {$mz_txt}";
    //Un huerto
    if(true){
        $huerto = 'una superficie de 312.50 m2, con las Medidas y Colindancias Siguientes: <strong>Al Norte</strong>, 46.012 MTS. CON Mz. 26 HUERTO. 15 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 10; <strong>Al Sur</strong>, 45.468 MTS. CON Mz. 26 HUERTO. 13 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 12; <strong>Al Este</strong>, 12.50 MTS. CON Mz. 27 HUERTO. 12; <strong>Al Oeste</strong>, 12.50 MTS. Con Sendero La Ceiba';
    //Dos huertos
    }else{
        $huerto = '<div>una superficie de 312.50 m2, con las Medidas y Colindancias Siguientes: <strong>Al Norte</strong>, 46.012 MTS. CON Mz. 26 HUERTO. 15 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 10; <strong>Al Sur</strong>, 45.468 MTS. CON Mz. 26 HUERTO. 13 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 12; <strong>Al Este</strong>, 12.50 MTS. CON Mz. 27 HUERTO. 12; <strong>Al Oeste</strong>, 12.50 MTS. Con Sendero La Ceiba<div>
        <div>una superficie de 312.50 m2, con las Medidas y Colindancias Siguientes: <strong>Al Norte</strong>, 46.012 MTS. CON Mz. 26 HUERTO. 15 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 10; <strong>Al Sur</strong>, 45.468 MTS. CON Mz. 26 HUERTO. 13 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 12; <strong>Al Este</strong>, 12.50 MTS. CON Mz. 27 HUERTO. 12; <strong>Al Oeste</strong>, 12.50 MTS. Con Sendero La Ceiba<div>';
    }
}else{

    $complemento_manzana_ii = ', que a continuación se describen';
    $complemento_manzana_v = 'motivos de la Presente Operación, no han sido Cedidas, Vendidas o Comprometidas en forma con anterioridad a este Acto';
    $fraccion = 'las fracciones marcadas';
    $numero_manzana = "los Números {$mz_txt}"; //str_replace la penúltima coma para poner una y
    $manzana_txt = "LAS MANZANAS {$mz_txt}"; //str_replace la última coma para poner una y
    

    //Si son dos más manzanas por ende son dos o más huertos
    $huerto = '<div><strong>EN MANZANA 28</strong> una superficie de 312.50 m2, con las Medidas y Colindancias Siguientes: <strong>Al Norte</strong>, 46.012 MTS. CON Mz. 26 HUERTO. 15 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 10; <strong>Al Sur</strong>, 45.468 MTS. CON Mz. 26 HUERTO. 13 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 12; <strong>Al Este</strong>, 12.50 MTS. CON Mz. 27 HUERTO. 12; <strong>Al Oeste</strong>, 12.50 MTS. Con Sendero La Ceiba<div>
    <div><strong>EN MANZANA 29</strong> una superficie de 312.50 m2, con las Medidas y Colindancias Siguientes: <strong>Al Norte</strong>, 46.012 MTS. CON Mz. 26 HUERTO. 15 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 10; <strong>Al Sur</strong>, 45.468 MTS. CON Mz. 26 HUERTO. 13 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 12; <strong>Al Este</strong>, 12.50 MTS. CON Mz. 27 HUERTO. 12; <strong>Al Oeste</strong>, 12.50 MTS. Con Sendero La Ceiba<div>';

}
$vars = [
'fecha_contrato_txt' => 'el día martes, 03 de enero del año 2017',
'nombre_cliente' => 'Samuel Rojas Too',
'domicilio_cliente' => 'Calle 51, No ext. 21, No. int S/N, Colonia Reg. 233, Benito Juarez, Quintana Roo, México, C.P. 77510',
'fraccion' => $fraccion,
'numero_manzana' => $numero_manzana,
'complemento_manzana_ii' => $complemento_manzana_ii,
'complemento_manzana_v' => $complemento_manzana_v,
'manzana_txt' => $manzana_txt,
'colindancias_mz' => $colindancias_mz,
'huerto' => $huerto,
'precio' => '$'.number_format(110000.00,2),
'precio_txt' => 'CIENTO DIEZ MIL PESOS 00/100 MN',
'enganche' => '$'.number_format(10000.00,2),
'enganche_txt' => 'DIEZ MIL PESOS 00/100 MN',
'n_pagos' => 67,
'abono' => '$'.number_format(1500.00,2), 
'abono_txt' => 'UN MIL QUINIENTOS PESOS 00/100 MN',
'fecha_primer_pago_txt' => 'el día martes, 15 de enero del año 2017',
'fecha_ultimo_pago_txt' => 'el día martes, 15 de enero del año 2020',
'porcentaje_penalizacion' => 1,
'porcentaje_penalizacion_txt' => 'UNO',
'maximo_retrasos_permitidos' => 3,
'testigo_1' => 'Testigo1 Testigo1 Testigo1',
'testigo_2' => 'Testigo2 Testigo2 Testigo2',
'tabla_pagare' => $tabla_pagare,
'ciudad' => 'México', 
];
        $contrato_template = file_get_contents('./application/views/templates/contrato/contrato.php', FILE_USE_INCLUDE_PATH);
        $output = $contrato_template;
        foreach($vars as $key => $var){            
            $search = "[@{$key}]";
            $replace = $var;
            $output = str_replace($search,$replace,$output);
        }
        $data['output'] = $output;
        $this->load->view('./templates/contrato/prueba',$data);
        
    }
    
    public function read_docx(){
        $cadena = file_get_contents('http://localhost/ceiba_negra/docs/Datos%20del%20predio/Datos%20a%20migrar/datos.txt', FILE_USE_INCLUDE_PATH);
        $textos = explode("\r\n", $cadena);

        foreach($textos as $key => $texto) {
            $texto = trim($texto);
            if (preg_match('/^MANZANA\s*[0-9]*$/i',$texto))
            {
                echo $texto."Inicio de bloque<br/>";

            } 
            else { 
                echo var_dump($texto)."<br/>";
            }
        }

    }
}