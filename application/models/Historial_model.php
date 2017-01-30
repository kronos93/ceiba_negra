<?php

class Historial_model extends CI_Model {
    private $table = "historial";
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
        $this->db->join('ventas','historial.id_venta = ventas.id_venta','left');
        $this->db->join('users','ventas.id_cliente = users.id','left');
        $query = $this->db->get();
        return $query->result();
    }
    public function insert($data){
        $query = $this->db->insert($this->table, $data);
        $id_historial = $this->db->insert_id();
        return $id_historial;
    }
    public function insert_batch($data){
        $query = $this->db->insert_batch($this->table, $data);
        /*$id_historial = $this->db->insert_id();
        return $id_historial;*/
    }
}
