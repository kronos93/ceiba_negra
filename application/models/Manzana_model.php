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
    public function get($select,$where){
        $this->db->select($select);
        $query = $this->db->get_where("{$this->tabla}",$where);
        return $query->result();
    }
    public function insert($insert){
        $query = $this->db->insert($this->tabla, $insert);
        $id_nueva_manzana = $this->db->insert_id();
        $select = "*";
        $where = ['id_manzana' => $id_nueva_manzana];
        $nueva_manzana = $this->get($select,$where);
        return $nueva_manzana;
    }
    public function update($where,$set){
        $this->db->set($set);
        $this->db->where($where);
        $this->db->update($this->tabla);
        if($this->db->affected_rows()){
            $select = "calle, disponibilidad, col_norte, col_sur, col_este, col_oeste";            
            return $this->get($select,$where);
        }else{
            return [];
        }
    }
    public function mz_mapplic(){
        $this->db->select(" CONCAT('mz',manzana) AS id,
                            CONCAT('Manzana número ',manzana) AS title, 
                            IF(disponibilidad !=0,'#3fbb9b','#ccc') AS color
                        ");
        $query = $this->db->get($this->tabla);
        return $query->result();
    }
}