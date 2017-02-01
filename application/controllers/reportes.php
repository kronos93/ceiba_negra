<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options as Options;
class Reportes extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Contrato_model');
        $this->load->model('Ingreso_model');
        $this->load->model('Manzana_model');
        $this->load->model('Huerto_model');
        $this->load->model('Venta_model');
        $this->load->model('Historial_model');
        $this->load->model('HuertosVentas_model');
    }
    public function contrato($id){
        ini_set('memory_limit', '1024M');
        set_time_limit(60);
        $condicion = ['ventas.id_venta' => $id];
        $ventas = $this->Venta_model
                                    ->select("contrato_html")
                                    ->where($condicion)
                                    ->get();
        $html = "";
        foreach($ventas as $venta){
            $html.= "<html><head><title>Contrato</title></head><body>";
            $html.= "<link rel='stylesheet' type='text/css' href='".base_url().'assets/css/tinymce.css'."' />";
            $html.= "<div class='mce-content-body'>";
            $html.= $venta->contrato_html;
            $html.= "</div>";
            $html.= "</body></html>";
            header("Content-type: application/json; charset=utf-8");
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'portrait');
            // Render the HTML as PDF
            $dompdf->render();
            // Output the generated PDF to Browser
            $dompdf->stream('contrato.pdf',array('Attachment'=>0));
        }
            
    }
    public function pagares($id){
        ini_set('memory_limit', '1024M');
        set_time_limit(180);
        $condicion = ['historial.id_venta' => $id];

        $historial = $this->Historial_model ->select("historial.abono,historial.fecha,CONCAT(users.first_name,' ',users.last_name) as nombre_cliente,huertos_ventas.id_venta")
                                            ->join('ventas','historial.id_venta = ventas.id_venta','left')
                                            ->join('users','ventas.id_cliente = users.id','left')
                                            ->join('huertos_ventas','historial.id_venta = huertos_ventas.id_venta','left')
                                            ->join('huertos','huertos_ventas.id_venta = huertos.id_huerto','left')
                                            ->where($condicion)
                                            ->get();
       
        
        if(count($historial)){
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
            $n_historial = count($historial);
            foreach($historial as $key => $historial){
                $words = explode(" ", $historial->nombre_cliente);
                $acronym="";
                $fecha = Carbon::createFromFormat('Y-m-d', $historial->fecha);
                foreach ($words as $w) {
                  $acronym .= $w[0];
                }
                if($count == 1){
                    $pagares.="<tr>
                                    <td>
                                        <div class='pagare'>                            
                                            <div class='pagare__header'>
                                                <h3>Recibo de Dinero {$n} de {$n_historial} <strong>de fecha : {$historial->fecha}</strong></h3>
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
                                                <p>FRANCISCO ENRIQUE MARTINEZ CORDERO</p>
                                                <p class='copy'>Original</p>
                                            </div>
                                        </div>
                                    </td>";
                    $count++;
                } else if($count == 2){
                    $pagares.="     <td>
                                        <div class='pagare'>
                                            <div class='pagare__header'>
                                                <h3>No. {$n}</h3>
                                                <p>En Playa del carmen, municipio de Solidaridad, estado de Quintana Roo el día{$fecha->format('d-m-Y')}</p>
                                            </div>
                                            <div class='pagare__body'>
                                                <p>
                                                    Debe(mos) y pagare(mos) incondicionalmente por este Pagaré a la orden de FRANCISCO ENRIQUE MARTINEZ CORDERO en Playa del carmen, municipio de Solidaridad, estado de Quintana Roo el día {$fecha->format('d-m-Y')} la cantidad de: <strong>$ ".number_format($historial->abono,2)." PESOS 00/100 M.N</strong>

                                                </p>
                                            </div>
                                            <div class='pagare__footer'>
                                                <p>RECIBI:</p>
                                                <br>
                                                <p></p>
                                                <p class='copy'>Original</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>";
                    $count = 1;
                    break;
                }
                $n++;
               
            }
            if($count==2){
                $pagares.="<td></td></tr>";
            }
            
            $html = "<html><head><title>Pagarés</title></head><body>
                <style>
                    h1,h2,h3,h4,h5,h6,p{
                        margin: 0px;
                    }
                    table {
                        width:100%;
                    }
                    table td{
                        position: relative;
                        border: 1px solid #ff0000;
                        padding: 5px;
                        border:1px dashed #ccc;
                        background: url('".base_url()."assets/img/logos/logo-small.png');
                        background-position: center center;
                        background-repeat: no-repeat;
                        width: 50%;
                    }
                 
                    body{
                        font-family: 'Helvetica';
                    }
                   .pagare__header h3 {
                        font-size: 9pt;
                        text-transform:uppercase;
                        text-align:right;
                    }
                    .pagare__header p{
                        color: #B88D2C;
                        font-size: 9pt;
                        padding: 5px 0px 10px;
                        text-align:right;
                    }
                    .pagare__body p {
                        font-size: 8pt;
                        text-align: justify;
                    }
                    .pagare__footer p {
                        font-size: 9pt;
                        text-align: center;
                    }
                    .pagare__footer .copy {
                        font-size: 9pt;
                        text-align: right;
                        color: #ccc;
                        text-transform: uppercase;
                    }
                </style>
                <table>       
                    {$pagares}                
                </table>
                </body>
            </html>";

            $options = new Options();
            $options->set('isRemoteEnabled', TRUE);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'portrait');
            // Render the HTML as PDF
            $dompdf->render();
            // Output the generated PDF to Browser
            $dompdf->stream('historial.pdf',array('Attachment'=>0));

        }                                            
        
    }
}
