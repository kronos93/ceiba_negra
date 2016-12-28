<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Inicio extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
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
