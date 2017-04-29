<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Carbon\Carbon;
use PHPMailer\PHPMailerAutoload;
use Sendinblue\Mailin;
class Mail extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Notification_model');
        $this->load->model('Venta_model');
    }
    /*public function index()
    {
        $date = Carbon::now(); //Hoy
        $limit_per_minute = 10;   //Limite por minuto
        $limit_per_hour = 100;   //Limite por hora
        $limit_per_mounth = 10000;//Limite por mes
        $last_insert = $this->Notification_model->limit(1)
                                                ->order_by('id_notification DESC')
                                                ->get();
        //Sí ya ha habido notificación entonces:
        if (count($last_insert)) {
            //Fecha de la última inserción
            $date_last_insert = Carbon::createFromFormat('Y-m-d H:i:s', "{$last_insert[0]->date} {$last_insert[0]->time}");
            //Número de los ultimos enviados por hora
            $last_enviados_por_minuto = $this->Notification_model->where("MINUTE(time) = {$date_last_insert->format('i')} && 
                                                                          YEAR(date) = {$date_last_insert->format('Y')} && 
                                                                          MONTH(date) = {$date_last_insert->format('m')}
                                                                          ")
                                                                        ->get();
            $last_enviados_por_hora = $this->Notification_model->where("HOUR(time) = {$date_last_insert->format('H')} &&                                                                         
                                                                        YEAR(date) = {$date_last_insert->format('Y')} && 
                                                                        MONTH(date) = {$date_last_insert->format('m')}
                                                                        ")
                                                                        ->get();
            $last_enviados_por_mes = $this->Notification_model->where(" YEAR(date) = {$date_last_insert->format('Y')} && 
                                                                        MONTH(date) = {$date_last_insert->format('m')}
                                                                        ")
                                                                        ->get();
           /* var_dump((int) $date->format('m') );
            var_dump((int) $date_last_insert->format('m') );
            var_dump(((int) $date->format('m') > (int) $date_last_insert->format('m')));/
            if (count($last_enviados_por_mes) <  $limit_per_mounth || ((int) $date->format('m') > (int) $date_last_insert->format('m'))) {
                //Este mes te sobran correos
                echo "<p>Este mes puedes enviar correos</p>";
                if (count($last_enviados_por_hora) == $limit_per_hour && $date->diffInHours($date_last_insert) < 1) {
                    echo "<p>Ya no puedes enviar :(, hasta {$date_last_insert->addHour()->format('H:i:s')}</p>";
                } else {
                    /*$enviados_por_hora = $this->Notification_model->where(" HOUR(time) = {$date->format('H')} &&
                                                                            YEAR(date) = {$date->format('Y')} && 
                                                                            MONTH(date) = {$date->format('m')}
                                                                            ")
                                                                        ->get();
                    if (count($enviados_por_hora) == $limit_per_hour) {
                        //No podrá enviar hasta futuro
                        echo "<p>No puedes enviar hasta el siguiente minuto</p>";
                    } else {
                        
                    }/
                    if (count($last_enviados_por_minuto) == $limit_per_minute && $date->diffInHours($date_last_insert) < 1) {
                        echo "<p>Ya no puedes enviar :(, hasta {$date_last_insert->addMinutes(10)->format('H:i:s')}</p>";
                    } else {
                    }
                }
            } else {
                echo "<p>Agotaste tu cuota mensual, espera hasta {$date->endOfMonth()->addDays(1)->format('m-Y')}  para seguir enviando correos</p>";
            }
        //Sino se ha notificado
        } else {
            //Puede envir mensajes
            $limit = 10;
            $valid_send = true;
            $msg = "";
        }
        $venta = new $this->Venta_model();
        $ventas = $venta->select("SUM(IF(historial.estado = 0 && DATE(historial.fecha) <= NOW(),1,0)) AS retraso,
                                          CONCAT(cliente.first_name,' ',cliente.last_name) AS nombre_cliente,
                                          cliente.email
                                          ")
                                ->join('historial', 'ventas.id_venta = historial.id_venta', 'left')
                                ->join('users as cliente', 'ventas.id_cliente = cliente.id', 'left')
                                ->where("ventas.estado = 0 OR ventas.estado = 1")
                                ->group_by('ventas.id_venta')
                                ->get();
                               
        for ($i = 0; $i < $limit && $i < count($ventas); $i++) {
            if ($ventas[$i]->retraso > 0) {
                /*echo ;
                echo ;/
                $mail = new PHPMailer;
                //Tell PHPMailer to use SMTP
                $mail->isSMTP();
                //Enable SMTP debugging
                // 0 = off (for production use)
                // 1 = client messages
                // 2 = client and server messages
                $mail->SMTPDebug = 0;
                //Ask for HTML-friendly debug output
                $mail->Debugoutput = 'html';
                //Set the hostname of the mail server
                //$mail->Host = 'smtp.gmail.com';
                $mail->Host = 'mail.huertoslaceiba.com';
                //$mail->Port = 465;
                $mail->Port = 25;
                //$mail->SMTPSecure = 'ssl';
                //Whether to use SMTP authentication
                $mail->SMTPAuth = true;
                //Username to use for SMTP authentication - use full email address for gmail
                $mail->Username = "admin@huertoslaceiba.com";
                //Password to use for SMTP authentication
                $mail->Password = "Admin1r2d2";
                //Set who the message is to be sent from
                $mail->setFrom('admin@huertoslaceiba.com', 'Huertos la ceiba');
                //Set an alternative reply-to address
                //$mail->addReplyTo('replyto@example.com', 'First Last');
                //Set who the message is to be sent to
                $mail->addAddress($ventas[$i]->email, $ventas[$i]->nombre_cliente);
                //Set the subject line
                $mail->Subject = 'PHPMailer GMail SMTP test';
                //Read an HTML message body from an external file, convert referenced images to embedded,
                //convert HTML into a basic plain-text alternative body
                $mail->msgHTML("<h1>Aviso de retraso en pagos</h1>");
                //Replace the plain text body with one created manually
                $mail->AltBody = 'Estimado cliente, le recordamos que usted tiene un retrado de: '.$ventas[$i]->retraso.' pagos para sus huertos, en caso de incumplir 3 meses su contrato se dará como cancelado. Favor de saldar su cuenta lo antes posible para que su inversión este asegurada. Buen día. Atte. la dministración de Huertos la ceiba.com';
                //Attach an image file
                //$mail->addAttachment('images/phpmailer_mini.png');
                //send the message, check for errors
                if (!$mail->send()) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                } else {
                    /*echo "Message sent!";/
                    $this->Notification_model->insert(
                                                        [
                                                            "id_notification" => null,
                                                            "date" => $date->format('Y-m-d'),
                                                            "time" => $date->format('H:i:s')
                                                        ]
                                                    );
                }
                
            }
        }
    }*/
    public function index()
    {
        $date = Carbon::now(); //Hoy
        $limit_per_minute = 10;   //Limite por minuto
        $limit_per_hour = 100;   //Limite por hora
        $limit_per_mounth = 10000;//Limite por mes
        $last_insert = $this->Notification_model->limit(1)
                                                ->order_by('id_notification DESC')
                                                ->get();
        //Sí ya ha habido notificación entonces:
        if (count($last_insert)) {
            //Fecha de la última inserción
            $date_last_insert = Carbon::createFromFormat('Y-m-d H:i:s', "{$last_insert[0]->date} {$last_insert[0]->time}");
            //Número de los ultimos enviados por hora
            $last_enviados_por_minuto = $this->Notification_model->where("MINUTE(time) = {$date_last_insert->format('i')} && 
                                                                          YEAR(date) = {$date_last_insert->format('Y')} && 
                                                                          MONTH(date) = {$date_last_insert->format('m')}
                                                                          ")
                                                                        ->get();
            $last_enviados_por_hora = $this->Notification_model->where("HOUR(time) = {$date_last_insert->format('H')} &&                                                                         
                                                                        YEAR(date) = {$date_last_insert->format('Y')} && 
                                                                        MONTH(date) = {$date_last_insert->format('m')}
                                                                        ")
                                                                        ->get();
            $last_enviados_por_mes = $this->Notification_model->where(" YEAR(date) = {$date_last_insert->format('Y')} && 
                                                                        MONTH(date) = {$date_last_insert->format('m')}
                                                                        ")
                                                                        ->get();
           /* var_dump((int) $date->format('m') );
            var_dump((int) $date_last_insert->format('m') );
            var_dump(((int) $date->format('m') > (int) $date_last_insert->format('m')));*/
            if (count($last_enviados_por_mes) <  $limit_per_mounth || ((int) $date->format('m') > (int) $date_last_insert->format('m'))) {
                //Este mes te sobran correos
                echo "<p>Este mes puedes enviar correos</p>";
                if (count($last_enviados_por_hora) == $limit_per_hour && $date->diffInHours($date_last_insert) < 1) {
                    echo "<p>Ya no puedes enviar :(, hasta {$date_last_insert->addHour()->format('H:i:s')}</p>";
                } else {
                    /*$enviados_por_hora = $this->Notification_model->where(" HOUR(time) = {$date->format('H')} &&
                                                                            YEAR(date) = {$date->format('Y')} && 
                                                                            MONTH(date) = {$date->format('m')}
                                                                            ")
                                                                        ->get();
                    if (count($enviados_por_hora) == $limit_per_hour) {
                        //No podrá enviar hasta futuro
                        echo "<p>No puedes enviar hasta el siguiente minuto</p>";
                    } else {
                        
                    }*/
                    if (count($last_enviados_por_minuto) == $limit_per_minute && $date->diffInHours($date_last_insert) < 1) {
                        echo "<p>Ya no puedes enviar :(, hasta {$date_last_insert->addMinutes(10)->format('H:i:s')}</p>";
                    } else {
                    }
                }
            } else {
                echo "<p>Agotaste tu cuota mensual, espera hasta {$date->endOfMonth()->addDays(1)->format('m-Y')}  para seguir enviando correos</p>";
            }
        //Sino se ha notificado
        } else {
            //Puede envir mensajes
            $limit = 10;
            $valid_send = true;
            $msg = "";
        }
        $venta = new $this->Venta_model();
        $ventas = $venta->select("SUM(IF(historial.estado = 0 && DATE(historial.fecha) <= NOW(),1,0)) AS retraso,
                                          CONCAT(cliente.first_name,' ',cliente.last_name) AS nombre_cliente,
                                          cliente.email
                                          ")
                                ->join('historial', 'ventas.id_venta = historial.id_venta', 'left')
                                ->join('users as cliente', 'ventas.id_cliente = cliente.id', 'left')
                                ->where("ventas.estado = 0 OR ventas.estado = 1")
                                ->group_by('ventas.id_venta')
                                ->get();
        $mail = new PHPMailer;
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'huertoslaceiba.com';                     // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'admin@huertoslaceiba.com';   // SMTP username
        $mail->Password = 'Admin1r2d2';                           // SMTP password
       // $mail->SMTPSecure = 'tls';                            // Enable encryption, only 'tls' is accepted
        $mail->Port = 25;                                    // TCP port to connect to
        $mail->From = 'admin@huertoslaceiba.com';
        $mail->FromName = 'Huertos la ceiba';
 
           
        for ($i = 0; $i < 10 && $i < count($ventas); $i++) {
            if ($ventas[$i]->retraso > 0) {
                $mail->addAddress($ventas[$i]->email);                 // Add a recipient
                //Set the subject line
                $mail->Subject = 'Aviso de incumplimiento de pago';
                //Read an HTML message body from an external file, convert referenced images to embedded,
                //convert HTML into a basic plain-text alternative body
                $mail->msgHTML("<h1>Aviso de retraso en pagos</h1><p>Estimado cliente, le recordamos que usted tiene un retrado de: {$ventas[$i]->retraso} pagos para sus huertos, en caso de incumplir 3 meses su contrato se dará como cancelado. Favor de saldar su cuenta lo antes posible para que su inversión este asegurada. Buen día. Atte. la dministración de Huertos la ceiba.com</p>");
                //Replace the plain text body with one created manually
                $mail->AltBody = '';

                //Attach an image file
                //$mail->addAttachment('images/phpmailer_mini.png');
                //send the message, check for errors
                if (!$mail->send()) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                } else {
                    echo "Message sent!";
                    $this->Notification_model->insert(
                                                        [
                                                            "id_notification" => null,
                                                            "date" => $date->format('Y-m-d'),
                                                            "time" => $date->format('H:i:s')
                                                        ]
                                                    );
                }
            }
        }
    }

    public function sendmail(){
        
        $emailFrom = "samuel.rojas.t93@gmail.com";
        $mailin = new Mailin('https://api.sendinblue.com/v2.0','OBFvYxVH6s7qSDAN');
        $data = array( 
                    "to" => array("samuel_-_rojas@hotmail.com"=>"Samuel R."),
                    "from" => array("samuel.rojas.t93@gmail.com","Samuel R."),
                    "replyto" => array("samuel.rojas.t93@gmail.com","Samuel R."),
                    "subject" => "My subject",
                    "html" => "This is the <h1>HTML</h1><br/>This is inline image 1.<br/>",
                    "headers" => array(
                        "MIME-Version" => "1.0",
                        "Content-Type"=> "Content-type: text/html; charset=iso-8859-1",     
                        "From" => "samuel.rojas.t93@gmail.com",
                        "Reply-to" => "samuel.rojas.t93@gmail.com",
                        "To" => "samuel_-_rojas@hotmail.com", 
                        "X-Priority" => 1,
                        "X-MSMail-Priority" => "High",                        
                        "X-Mailer" => "PHP/".phpversion()
                        ),
                    
        );
        var_dump($mailin->send_email($data));
        
    }
}
