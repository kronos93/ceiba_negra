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
    public function select($select){
        $this->db->select($select);
        return $this;
    }
    public function where($condicion){
        $this->db->where($condicion);
        return $this;
    }
    public function insert($data){
        $query = $this->db->insert($this->table, $data);
        $id_venta = $this->db->insert_id();
        return $id_venta;
    }
}