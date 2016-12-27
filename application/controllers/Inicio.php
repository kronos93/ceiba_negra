<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Inicio extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		/*
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		*/
		$this->lang->load('auth');
	}
	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else
		{
			if(!$this->session->has_userdata('lotes')){
            	$this->session->set_userdata('lotes', 0);
				$data['huertos'] = 0;
        	}else{
				$data['huertos'] = $this->session->lotes;
			}
			$data['title'] = "Inicio";
			$data['body'] = "inicio";
			
			$this->load->view('templates/template',$data);
		}
	}
}
