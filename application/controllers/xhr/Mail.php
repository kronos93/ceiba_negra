<?php
use PHPMailer\PHPMailerAutoload;
class Mail extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Venta_model');
    $this->load->model('Historial_model');
  }
  public function send_mail($id) {
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
    $venta = array_pop($ventas);
    $mail = new PHPMailer;
      //Tell PHPMailer to use SMTP
      $mail->isSMTP();
      $mail->CharSet = 'UTF-8';
      //Enable SMTP debugging
      // 0 = off (for production use)
      // 1 = client messages
      // 2 = client and server messages
      $mail->SMTPDebug = (ENVIRONMENT === 'production') ? 0 : 2;
      //Set the hostname of the mail server
      //$mail->Host = 'smtp.gmail.com';
      $mail->Host = 'smtp.mailtrap.io';
      // use
      // $mail->Host = gethostbyname('smtp.gmail.com');
      // if your network does not support SMTP over IPv6
      //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
      $mail->Port = 2525;
      //$mail->Port = 587;
      //Set the encryption system to use - ssl (deprecated) or tls
      $mail->SMTPSecure = 'tls';
      //Whether to use SMTP authentication
      $mail->SMTPAuth = true;
      //Username to use for SMTP authentication - use full email address for gmail
      $mail->Username = "78e69e91145c7d";
      //$mail->Username = "samuel.rojas.t93@gmail.com";
      //Password to use for SMTP authentication
      $mail->Password = "014438d467469c";
      //$mail->Password = "s919397a";
      //Set who the message is to be sent from
      $mail->setFrom('samuel.rojas.t93@gmail.com', 'Huertos la ceiba');
      //Set an alternative reply-to address
      $mail->addReplyTo('samuel.rojas.t93@gmail.com', 'Huertos la ceiba');
      //Set who the message is to be sent to
      $mail->addAddress('samuel_-_rojas@hotmail.com', 'Samuel Rojas');
      //Set the subject line
      $mail->Subject = 'Notificación de retraso en pagos';
      //Read an HTML message body from an external file, convert referenced images to embedded,
      //convert HTML into a basic plain-text alternative body
      //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
      $mail->msgHTML("
        <h1>RECORDATORIO</h1>
        <p><strong>Apreciable cliente:</strong> {$venta->nombre_cliente}</p>
        <p>Por este medio le informamos que tiene pagos atrasados con respecto a su huerto del proyecto La Ceiba, le invitamos a realizar sus pagos para no generar recargos y/o intereses que estipula en el apartado \"penalizaciones\" del Contrato Privado de Cesión de Derechos. Cualquier duda o aclaración puede acercarse a la oficina y con gusto uno de nuestros asistentes administrativos le atenderá.</p>
        <p>A continuación, le proporcionamos la siguiente dirección: </p>
        <p>Del mismo modo, puede realizar los depósitos o transferencias a la siguiente cuenta Scotiabank (sucursal 005), a nombre de Francisco Enrique Martínez Cordero:</p>
        <ul>
          <li>Número de cuenta: 237 0052 4891</li>
          <li>Número de tarjeta (Para depositar en OXXO): 5579 2090 8864 2019</li>
          <li>Clabe Interbancaria: 0446 9423 7005 2489 18</li>
        </ul>
        <p>Atentamente:</p>
        <p><strong>Francisco Enrique Martínez Cordero</strong></p>
      ");

      //Replace the plain text body with one created manually
      //$mail->AltBody = 'This is a plain-text message body';
      //Attach an image file
      //$mail->addAttachment('images/phpmailer_mini.png');
      //send the message, check for errors
      if (!$mail->send()) {
          echo "Mailer Error: " . $mail->ErrorInfo;
      } else {
          echo "Message sent!";
          //Section 2: IMAP
          //Uncomment these to save your message in the 'Sent Mail' folder.
          #if (save_mail($mail)) {
          #    echo "Message saved!";
          #}
      }
  }
}
