<?php

class Historial_model extends CI_Model {
    private $table = "historial";
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get(){       
        $this->db->from($this->table);        
        $query = $this->db->get();
        return $query->result();
    }
    public function from(){
        $this->db->from($this->table);
        return $this;
    }
    public function getArray(){       
        $this->db->from($this->table);        
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select($select){
        $this->db->select($select);
        return $this;
    }
    public function where($condicion){
        $this->db->where($condicion);
        return $this;
    }
    public function join($table_join,$condicion,$type='left'){
        $this->db->join($table_join,$condicion,$type);
        return $this;
    }
    public function insert($data){
        $query = $this->db->insert($this->table, $data);
        $id_historial = $this->db->insert_id();
        return $id_historial;
    }
    public function update($data)
    {
        $this->db->set($data);        
        $this->db->update($this->table);      
        return $this;  
    }
    public function order_by($order) {
        $this->db->order_by($order);
        return $this;
    }
    public function limit($limit){
        $this->db->limit($limit);
        return $this;
    }
    public function affected_rows() {
        return $this->db->affected_rows();
    }
    public function insert_batch($data) {
        $this->db->insert_batch($this->table, $data);
    }
    public function group_by($group){
        $this->db->group_by($group);
        return $this;
    }
}
