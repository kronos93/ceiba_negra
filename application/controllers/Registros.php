<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registros extends CI_Controller {

	public function manzanas()
	{		
		$data['title'] = "Manzanas";
		$data['body'] = "manzanas";
		$this->load->view('templates/template',$data);
	}
	public function lotes()
	{		
		$data['title'] = "Lotes";
		$data['body'] = "lotes";
		$this->load->view('templates/template',$data);
	}
}
