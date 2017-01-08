<?php 

class Ingreso_model extends CI_Model {
    private $tabla = "ingresos";
    public function __construct() {
        parent::__construct();
    }
    public function get(){
        $query = $this->db->get($this->tabla);
        return $query->result();
    }
}