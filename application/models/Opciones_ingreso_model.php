<?php 

class Opciones_ingreso_model extends CI_Model {
    private $tabla = "opciones_ingreso";
    public function __construct() {
        parent::__construct();
    }
    public function get(){
        $query = $this->db->get($this->tabla);
        return $query->result();
    }
}