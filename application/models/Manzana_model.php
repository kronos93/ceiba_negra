<?php

class Manzana_model extends CI_Model
{
    private $tabla = "manzanas";
    private $select = "*";
    private $where = [];
    public function __construct()
    {
        parent::__construct();
    }
    public function getAll($type = "object")
    {
        $query = $this->db->query("SELECT * FROM {$this->tabla}");
        if ($type === "object") {
            return $query->result();
        } elseif ($type==="array") {
            return $query->result_array();
        }
    }
    public function get($select, $where)
    {
        $this->db->select($select);
        $query = $this->db->get_where("{$this->tabla}", $where);
        return $query->result();
    }
    public function insert($insert)
    {
        $query = $this->db->insert($this->tabla, $insert);
        $id_nueva_manzana = $this->db->insert_id();
        $select = "*";
        $where = ['id_manzana' => $id_nueva_manzana];
        $nueva_manzana = $this->get($select, $where);
        return $nueva_manzana;
    }
    public function update($set, $where)
    {
        $this->db->set($set);
        $this->db->where($where);
        $this->db->update($this->tabla);
        if ($this->db->affected_rows()) {
            $select = "calle, superficie, disponibilidad, col_norte, col_noreste, col_este, col_sureste, col_sur, col_suroeste, col_oeste, col_noroeste";
            return $this->get($select, $where);
        } else {
            return [];
        }
    }
    public function categories_mz()
    {
        $this->db->select(" CONCAT('mz',manzana) AS id,
                            CONCAT('Manzana número ',manzana) AS title, 
                            IF(disponibilidad !=0,'#3fbb9b','#ccc') AS color,
                            manzana
                        ");
        $this->db->order_by('manzana', 'ASC');
        $query = $this->db->get($this->tabla);
        return $query->result();
    }
    public function _get(){
        $this->db->select($this->select);
        $this->db->where($this->where);
        $query = $this->db->get($this->tabla);
        return $query->result();
    }
    public function where($where){        
        $this->where = $where;
        return $this;
    }
    public function select($select){
        $this->select = $select;
        return $this;
    }
}