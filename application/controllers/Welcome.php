<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {



	public function index()
	{
		$data['titulo'] = "Bienvenido";
		$this->load->view('./templates/template',$data);
		
	}
}
