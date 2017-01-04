<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venta extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('Contrato_model');
    }
	public function index() {
        $data['title'] = "Venta";
		$data['body'] = "venta";
		$this->load->view('templates/template',$data);        
    }
    public function prueba() {
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
if(false){
    $n_manzana = 28;
    $complemento_manzana_ii = ', que a continuación se describe';
    $complemento_manzana_v = 'motivo de la Presente Operación, no ha sido Cedida, Vendida o Comprometida en forma con anterioridad a este Acto';
    $fraccion = 'la fracción marcada';
    $numero_manzana = "el Número {$n_manzana}";
    $manzana_txt = "LA MANZANA {$n_manzana}";
    $colindancias = '<strong>Al Norte</strong>, 125.000 MTS. CON Mz. 29; <strong>Al Sur</strong>, 125.000 MTS. CON Mz. 27; <strong>Al Este</strong>, 70.00 MTS. CON Mz. 27; <strong>Al Oeste</strong>, 70.00 MTS. Con Sendero La Ceiba; con una Superficie total de 8,750.00 M2';
    //Un huerto
    if(true){
        $huerto = 'una superficie de 312.50 m2, con las Medidas y Colindancias Siguientes: <strong>Al Norte</strong>, 46.012 MTS. CON Mz. 26 HUERTO. 15 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 10; <strong>Al Sur</strong>, 45.468 MTS. CON Mz. 26 HUERTO. 13 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 12; <strong>Al Este</strong>, 12.50 MTS. CON Mz. 27 HUERTO. 12; <strong>Al Oeste</strong>, 12.50 MTS. Con Sendero La Ceiba';
    //Dos huertos
    }else{
        $huerto = '<div>una superficie de 312.50 m2, con las Medidas y Colindancias Siguientes: <strong>Al Norte</strong>, 46.012 MTS. CON Mz. 26 HUERTO. 15 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 10; <strong>Al Sur</strong>, 45.468 MTS. CON Mz. 26 HUERTO. 13 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 12; <strong>Al Este</strong>, 12.50 MTS. CON Mz. 27 HUERTO. 12; <strong>Al Oeste</strong>, 12.50 MTS. Con Sendero La Ceiba<div>
        <div>una superficie de 312.50 m2, con las Medidas y Colindancias Siguientes: <strong>Al Norte</strong>, 46.012 MTS. CON Mz. 26 HUERTO. 15 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 10; <strong>Al Sur</strong>, 45.468 MTS. CON Mz. 26 HUERTO. 13 MÁS 35.00 MTS. CON Mz. 26 HUERTO. 12; <strong>Al Este</strong>, 12.50 MTS. CON Mz. 27 HUERTO. 12; <strong>Al Oeste</strong>, 12.50 MTS. Con Sendero La Ceiba<div>';
    }
}else{
    $n_manzana = '28, 29';
    $complemento_manzana_ii = ', que a continuación se describen';
    $complemento_manzana_v = 'motivos de la Presente Operación, no han sido Cedidas, Vendidas o Comprometidas en forma con anterioridad a este Acto';
    $fraccion = 'las fracciones marcadas';
    $numero_manzana = "los Números {$n_manzana}"; //str_replace la penúltima coma para poner una y
    $manzana_txt = "LAS MANZANAS {$n_manzana}"; //str_replace la última coma para poner una y
    $colindancias = '<div><strong>MANZANA 28 : </strong><strong>Al Norte</strong>, 125.000 MTS. CON Mz. 29; <strong>Al Sur</strong>, 125.000 MTS. CON Mz. 27; <strong>Al Este</strong>, 70.00 MTS. CON Mz. 27; <strong>Al Oeste</strong>, 70.00 MTS. Con Sendero La Ceiba; con una Superficie total de 8,750.00 M2</div>
    <div><strong>MANZANA 29 : </strong><strong>Al Norte</strong>, 125.000 MTS. CON Mz. 29; <strong>Al Sur</strong>, 125.000 MTS. CON Mz. 27; <strong>Al Este</strong>, 70.00 MTS. CON Mz. 27; <strong>Al Oeste</strong>, 70.00 MTS. Con Sendero La Ceiba; con una Superficie total de 8,750.00 M2</div>';

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
'colindancias' => $colindancias,
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
}