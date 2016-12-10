<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	public function index()
	{
		
		$data['title'] = "Iniciar sesión";
		$data['body'] = "login";
		$this->load->view('./templates/template',$data);	
	}
}
