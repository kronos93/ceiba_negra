<?php
class Opciones_ingreso_model extends CI_Model {
    private $table = "opciones_ingreso";
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function join($table_join,$condicion,$type='left'){
        $this->db->join($table_join,$condicion,$type);
        return $this;
    }
    public function select($select){
        $this->db->select($select);
        return $this;
    }
    public function where($where) {
        $this->db->where($where);
        return $this;
    }
    public function get() {
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result();
    }
    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this;
    }
    public function update($data)
    {
        $this->db->set($data);        
        $this->db->update($this->table);      
        return $this;  
    }
    public function group_by($group){
        $this->db->group_by($group);
        return $this;
    }
    public function insert_id() {
        return $this->db->insert_id();
    }
    public function affected_rows() {
        return $this->db->affected_rows();
    }
}