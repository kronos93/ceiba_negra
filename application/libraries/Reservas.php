<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reservas {
    protected $CI;
    public function __construct()
	{
        $this->CI =& get_instance();
        $this->CI->load->model('Reserva_model');

    }
    public function total() {
        if ($this->CI->ion_auth->in_group(['administrador'])) {
            return $this->CI->Reserva_model->count_all();
        }else if ($this->CI->ion_auth->in_group(['lider'])) {
            return $this->CI->Reserva_model->where(['id_lider' => $this->CI->ion_auth->get_user_id()])
                                           ->count_all();
        }
        
    }
}