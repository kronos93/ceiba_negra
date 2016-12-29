<?php 

class Contrato_model extends CI_Model {
    private $tabla = "pantillas_contrato";
    public function __construct() {
        parent::__construct();
    }
    public function get(){
        $query = $this->db->get($this->tabla);
        return $query->result();
    }
}