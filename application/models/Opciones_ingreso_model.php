<?php
class Opciones_ingreso_model extends CI_Model {
    private $table = "opciones_ingreso";
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get(){
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result();
    }
}