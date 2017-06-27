<?php

class Venta_model extends CI_Model {
    private $table = "ventas";
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get($condicion = array()){
        $this->db->from($this->table);
        ///$this->db->join('users as cliente','ventas.id_cliente = cliente.id','left');
        ///$this->db->join('users as lider','ventas.id_lider = lider.id','left');
        $query = $this->db->get();
        return $query->result();
    }
    public function from(){
        $this->db->from($this->table);
        return $this;
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
        $id_venta = $this->db->insert_id();
        return $id_venta;
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
    public function order_by($order){
        $this->db->order_by($order);
        return $this;
    }
}