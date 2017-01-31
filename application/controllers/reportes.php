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
    }
    public function contrato($id){
        ini_set('memory_limit', '1024M');
        set_time_limit(60);
        $condicion = ['id_venta' => $id];
        $ventas = $this->Venta_model->get($condicion);
        $html = "";
        foreach($ventas as $venta){
            $html.= "<html><head><title>Contrato</title></head><body>";
            $html.= "<link rel='stylesheet' type='text/css' href='".base_url().'assets/css/tinymce.css'."' />";
            $html.= "<div class='mce-content-body'";
            $html.= $venta->contrato_html;
            $html.= "</div>";
            $html.= "</body></html>";
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
        set_time_limit(60);
        $condicion = ['historial.id_venta' => $id];
        $historial = $this->Historial_model->get($condicion);
        $pagares = "";
        $count = 1;
        $n = 1;
        $n_historial = count($historial);
        foreach($historial as $key => $historial){
            if($count == 1){
                $pagares.="<tr>
                                <td>
                                    <div class='pagare'>                            
                                        <div class='pagare__header'>
                                            <h3>Recibo de Dinero {$n} de {$n_historial} <strong>de fecha : {$historial->fecha}</strong></h3>
                                            <p><strong>FOLIO:MT-{$n}-{$historial->fecha}</strong></p>
                                        </div>
                                        <div class='pagare__body'>
                                            <p>
                                                RECIBI: DEL(A) C. <strong>{$historial->first_name} {$historial->last_name}</strong> LA CANTIDAD DE <strong>$ ".number_format($historial->abono,2)." PESOS 00/100 M.N</strong> POR CONCEPTO DE PAGO PARCIAL DE LA CESION PRIVADA , DE DERECHOS EN CO-PROPIEDAD DEL TERRENO EN BREÑA No. 21 MZ. 15B DEL PREDIO 'LA CEIBA', UBICADO EN EL MUNICIPIO DE LAZARO CARDENAS, QUINTANA ROO.
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
                                            <h3>Recibo de Dinero {$n} de {$n_historial} <strong>de fecha{$historial->fecha}</strong></h3>
                                            <p><strong>FOLIO:MT-{$n}-{$historial->fecha}</strong></p>
                                        </div>
                                        <div class='pagare__body'>
                                            <p>
                                                RECIBI: DEL(A) C. <strong>{$historial->first_name} {$historial->last_name}</strong> LA CANTIDAD DE <strong>$ ".number_format($historial->abono,2)." PESOS 00/100 M.N</strong> POR CONCEPTO DE PAGO PARCIAL DE LA CESION PRIVADA , DE DERECHOS EN CO-PROPIEDAD DEL TERRENO EN BREÑA No. 21 MZ. 15B DEL PREDIO 'LA CEIBA', UBICADO EN EL MUNICIPIO DE LAZARO CARDENAS, QUINTANA ROO.
                                            </p>
                                        </div>
                                        <div class='pagare__footer'>
                                            <p>RECIBI:</p>
                                            <br>
                                            <p>FRANCISCO ENRIQUE MARTINEZ CORDERO</p>
                                            <p class='copy'>Original</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>";
                $count = 1;
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
