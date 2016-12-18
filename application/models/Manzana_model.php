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
    public function get($id){
        $this->db->select("calle, disponibilidad, col_norte, col_sur, col_este, col_oeste");
        $query = $this->db->get_where($this->tabla, $id);
        return $query->result();
    }
    public function insert($insert){
        $query = $this->db->insert($this->tabla, $insert);
        return $this->db->insert_id();
    }
    public function update($where,$set){
        $this->db->set($set);
        $this->db->where($where);
        $this->db->update($this->tabla);
        if($this->db->affected_rows()){
            return $this->get($where);
        }else{
            return [];
        }
    }
    public function mz_mapplic(){
        $query = $this->db->query("SELECT concat('mz',id_manzana) AS id, concat('Manzana número ',manzana) AS title, IF(disponibilidad !=0,'#3fbb9b','#ccc') AS color  FROM manzanas");
        return $query->result();
    }
}