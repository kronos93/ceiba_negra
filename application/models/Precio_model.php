<?php 

class Precio_model extends CI_Model {
    private $tabla = "precios";
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getAll(){
        $query = $this->db->query("SELECT * FROM {$this->tabla}");
        return $query->result();
    }
}