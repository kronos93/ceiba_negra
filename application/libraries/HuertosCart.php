<?php  defined('BASEPATH') OR exit('No direct script access allowed');
class HuertosCart
{
    protected $CI;
    public function __construct()
    {        
        $this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->create_cart();
    }

    public function create_cart(){       
        if (!$this->CI->session->has_userdata('huertos')) {
            $this->CI->session->set_userdata('huertos', 0);
        }
    }
    public function add_cart(){
        $huerto = $this->session->userdata('huertos') + 1;
        $this->session->set_userdata('huertos', $huerto);
    }
}
