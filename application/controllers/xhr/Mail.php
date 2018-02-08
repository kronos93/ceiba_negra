<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;

class Mail extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Venta_model');
        $this->load->model('Historial_model');
    }
    public function send_mail($id)
    {
        $historials = $this->Historial_model->from()->db
                                            ->select("
                                                concepto, fecha, abono, estado, pago
                                            ")
                                            ->where([
                                              'id_venta' => $id,
                                              //'fecha_pago  <=' => Carbon::now()->format('Y-m-d'),
                                            ])
                                            ->get()
                                            ->result();
        $ventas = $this->Venta_model->from()->db
                                  ->select("
                                    CONCAT(users.first_name, ' ', users.last_name) AS nombre_cliente,
                                    ventas.id_venta,
                                    ventas.porcentaje_penalizacion,
                                    GROUP_CONCAT(DISTINCT manzanas.manzana ORDER BY  manzanas.manzana ASC) as manzanas,
                                    GROUP_CONCAT('Mz. ',manzanas.manzana,   ' Ht. ', huertos.huerto ORDER BY  manzanas.manzana ASC) as descripcion,
                                    users.email AS email
                                  ")
                                  ->join('users', 'ventas.id_cliente = users.id', 'left')

                                  ->join('huertos_ventas', 'ventas.id_venta = huertos_ventas.id_venta', 'left')
                                  ->join('huertos', 'huertos_ventas.id_huerto = huertos.id_huerto', 'left')
                                  ->join('manzanas', 'huertos.id_manzana = manzanas.id_manzana', 'left')

                                  ->where(['ventas.id_venta' => $id])
                                  /*->group_by('ventas.id_vental')*/
                                  ->get()
                                  ->result();
        $venta = array_pop($ventas);

        $lastPay = new stdClass();
        $lastPay->date = 'No ha realizado pago alguno';
        $lastPay->concepto = 'Sin registro de pago';
        $data = array(
          'now' => Carbon::now(),
          'total_p' => 0,
          'total_a' => 0,
          'lastPay' => $lastPay,
          'totalAbonado' => 0,
          'pendienteASaldar' => 0,
          'quincena' => 0,
          'hasPay' => false,

          'venta' => $venta,
          'historials' => $historials,
        );

        $html = $this->load->view('mailing',$data, TRUE);


        $mail = new PHPMailer;
        $mail->SMTPOptions = array(
          'ssl' => array(
              'verify_peer' => false,
              'verify_peer_name' => false,
              'allow_self_signed' => true
          )
        );
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;
        $mail->Priority = 1;
        $mail->SMTPAutoTLS = false;
        //Set the hostname of the mail server
        $mail->Host = gethostbyname('smtp.gmail.com');
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission

        $mail->Port = 587;
        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
          //Username to use for SMTP authentication - use full email address for gmail
          // //$mail->Username = "78e69e91145c7d";
          // $mail->Username = "samuel.rojas.t93@gmail.com";
          // //Password to use for SMTP authentication
          // $mail->Password = "s919397a";
          // //Set who the message is to be sent from
          // $mail->setFrom('samuel.rojas.t93@gmail.com', 'Huertos la ceiba');
          // //Set an alternative reply-to address
          // $mail->addReplyTo('samuel.rojas.t93@gmail.com', 'Huertos la ceiba');
          // //Set who the message is to be sent to
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "huertoslaceiba@gmail.com";
        //Password to use for SMTP authentication
        $mail->Password = "12345Huertos";
        //Set who the message is to be sent from
        $mail->setFrom('huertoslaceiba@gmail.com', 'Huertos la ceiba');
        //Set an alternative reply-to address
        $mail->addReplyTo('huertoslaceiba@gmail.com', 'Huertos la ceiba');
        $mail->addAddress($venta->email, $venta->nombre_cliente);
        //Set the subject line
        $mail->Subject = 'Notificación de retraso en pagos';

        $mail->msgHTML($html);

        //send the message, check for errors
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }
    }
}
