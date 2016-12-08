<?php 

class Manzana_model extends CI_Model {
    private $tabla = "manzanas";
    public function __construct() {
        parent::__construct();
    }
    public function getAll($type="object"){
        $query = $this->db->query("SELECT * FROM {$this->tabla}");
        if($type === "object"){
            return $query->result();
        }
        else if($type==="array"){
            return $query->result_array();
        }
    }

    public function mz_mapplic(){
        $query = $this->db->query("SELECT concat('mz',id_manzana) AS id, concat('Manzana número ',manzana) AS title, IF(estado !=0,'#3fbb9b','#ccc') AS color  FROM manzanas");
        return $query->result();
    }
}