<?php

class Venta_model extends CI_Model {
    private $table = "ventas";
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get($condicion){
       
        $this->db->from($this->table);
        if (count($condicion)) {
            $this->db->where($condicion);
        }
        $query = $this->db->get();
        return $query->result();
    }
    public function insert($data){
        $query = $this->db->insert($this->table, $data);
        $id_venta = $this->db->insert_id();
        return $id_venta;
    }
}
