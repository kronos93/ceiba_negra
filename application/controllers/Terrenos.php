<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terrenos extends CI_Controller {



	public function index()
	{		
		$data['title'] = "Terrenos";
		$data['body'] = "terrenos";
		$this->load->view('templates/template',$data);
	}
}
