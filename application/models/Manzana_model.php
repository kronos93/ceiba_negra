<?php 

class Manzana_model extends CI_Model {
    private $tabla = "manzanas";
    public function __construct() {
        parent::__construct();
    }
    public function getAll(){
        $query = $this->db->query("SELECT * FROM {$this->tabla}");
        return $query->result();
    }

    public function mz_mapplic(){
        $query = $this->db->query("SELECT concat('mz',id_manzana) AS id,concat('Manzana número ',manzana) AS title, '#3fbb9b' AS color  FROM manzanas ");
        return $query->result();
    }
}