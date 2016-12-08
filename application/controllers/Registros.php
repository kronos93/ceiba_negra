<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registros extends CI_Controller {

	public function manzanas()
	{		
		$data['title'] = "Terrenos";
		$data['body'] = "terrenos";
		$this->load->view('templates/template',$data);
	}
}
