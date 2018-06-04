<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Huertos extends CI_Controller
{
    public function __construct()
    {
      parent::__construct();
      $this->load->model('Huerto_model');
      header("Content-type: application/json; charset=utf-8");
    }
    public function index()
    {

    }

    public function get_huertos() {
      $response = new stdClass();
      $huertos = $this->Huerto_model->getHuertosPM();
      $response->data = $huertos;
      echo json_encode($response);
    }
}
