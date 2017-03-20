<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Mailgun\Mailgun;
use PHPMailer\PHPMailerAutoload;

class Inicio extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        /*$mg = new Mailgun("key-f83e0ef7bbb58012524a8c1ee0feb699");
        $domain = "huertoslaceiba.com";
        $mg->setSslEnabled(false);
        # Now, compose and send your message.
        $mg->sendMessage($domain, array('from'    => 'postmaster@huertoslaceiba.com', 
                                        'to'      => 'samuel_-_rojas@hotmail.com', 
                                        'subject' => 'The PHP SDK is awesome!', 
                                        'text'    => 'It is so simple to send a message.'));*/
        

       /* $mail = new PHPMailer;

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.visalcap.com';                     // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'admin@visalcap.com';   // SMTP username
        $mail->Password = 'Admin1r2d2';                           // SMTP password
       // $mail->SMTPSecure = 'tls';                            // Enable encryption, only 'tls' is accepted
        $mail->Port = 25;                                    // TCP port to connect to
        $mail->From = 'admin@visalcap.com';
        $mail->FromName = 'Mailer';
        $mail->addAddress('samuel_-_rojas@hotmail.com');                 // Add a recipient

        $mail->WordWrap = 50;                                 // Set word wrap to 50 characters

        $mail->Subject = 'Hello';
        $mail->Body    = 'Testing some Mailgun awesomness';*/
        /*$this->load->library('email');
        $config = [];
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.visalcap.com';
        $config['smtp_user'] = 'admin@visalcap.com';
        $config['smtp_pass'] = 'Admin1r2d2';
        $config['smtp_port'] = '25';

        $this->email->initialize($config);
        $this->email->from('admin@visalcap.com', 'Visalcap');
        $this->email->to('samuel_-_rojas@hotmail.com');
        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');

        if ( ! $this->email->send())
        {
            echo $this->email->print_debugger(array('headers'));
            echo "falló";
            die();
            
        }else{
            echo 'envió';
        }*/

        if (!$this->ion_auth->logged_in()) {
        // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else {
            $data['title'] = "Inicio";
            $data['body'] = "inicio";
            $this->load->view('templates/template', $data);
        }
    }
}
