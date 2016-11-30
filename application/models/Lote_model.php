<?php 

class Lote_model extends CI_Model {
    private $tabla = "lotes";
    public function __construct() {
        parent::__construct();
         $this->load->database();
    }

    public function getAll(){
        $query = $this->db->query("SELECT * FROM {$this->tabla}");
        return $query->result();
    }

}