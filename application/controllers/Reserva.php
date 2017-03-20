<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Carbon\Carbon;
class Reserva extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reserva_model');
        $this->load->model('HuertosReserva_model');
        $this->load->model('Huerto_model');
        $this->load->model('Trans_model');
    }
    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
        // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else {
            $data['title'] = "Reserva";
            $data['body'] = "reserva";
            $this->load->view('templates/template', $data);
        }
    }
    public function guardar()
    {
        //Cabazera de respuesta JSON
        header("Content-type: application/json; charset=utf-8");
        //Validación de form
        $config = [
            [
                'field' => 'first_name',
                'label' => 'Nombre',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'last_name',
                'label' => 'Apellidos',
                'rules' => 'trim|required',
            ],
            [
                'field' => 'email',
                'label' => 'Correo electronico',
                'rules' => 'trim|required|is_unique[users.email]',
            ],
            [
                'field' => 'phone',
                'label' => 'Telefono',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'precio',
                'label' => 'Precio',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'enganche',
                'label' => 'Enganche',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'abono',
                'label' => 'Abono',
                'rules' => 'trim|required|numeric',
            ],
            [
                'field' => 'comment',
                'label' => 'Comentarios',
                'rules' => 'trim',
            ],
        ];
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run()) {
             $data = [
                'id_lider' => $this->ion_auth->get_user_id(),
                'first_name' => $this->input->post('first_name'),
                'last_name'   => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'precio' => $this->input->post('precio'),
                'enganche' => $this->input->post('enganche'),
                'abono' => $this->input->post('abono'),
                'comment' => $this->input->post('comment'),
                'fecha' =>  Carbon::today(),
                'expira' =>  Carbon::today()->addDays(7),
             ];
             $this->Trans_model->begin();
             $reserva = $this->Reserva_model->insert($data);
             if($reserva){
                $huertos_reserva = [];
                $huertos = [];
                foreach ($this->cart->contents() as $items) {
                    $huerto_reserva = new stdClass();
                    $huerto_reserva->id_reserva  = $reserva;
                    $huerto_reserva->id_huerto = $items['id_huerto'];
                    array_push($huertos_reserva, $huerto_reserva);
                    $huerto = new stdClass();
                    $huerto->id_huerto = $items['id_huerto'];
                    $huerto->vendido = 4;
                    array_push($huertos, $huerto);
                }
                $this->HuertosReserva_model->insert_batch($huertos_reserva);
                $updated_huertos = $this->Huerto_model->where(['vendido'=>0])
                                                      ->update_batch($huertos, 'id_huerto');
                if ($this->Trans_model->status() === false) {
                    $this->Trans_model->rollback();
                    echo "<p>Error fatal</p>";
                } else {
                    if($this->cart->total_items() != $updated_huertos){
                        echo "<p>No fue posible actualizar todos los registros, alguien más ha reservado.</p>";
                    }else{
                        $this->cart->destroy();
                        $this->Trans_model->commit();
                        echo json_encode(['status' => 200 ,'msg' => 'ok']);
                    }
                }
             }else{
                 echo "<p>Falló la inserción de datos, sí el problema persiste contactar al administrador.</p>";
             }
        } else {
            echo validation_errors();
        }
    }
}
