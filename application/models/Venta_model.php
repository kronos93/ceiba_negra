<?php

class Venta_model extends CI_Model {
    private $table = "ventas";
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get($condicion = array()){
        $this->db->select("{$this->table}.id_venta,
                           CONCAT(cliente.first_name,' ',cliente.last_name) AS nombre_cliente,
                           CONCAT(lider.first_name,' ',lider.last_name) AS nombre_lider");
        $this->db->from($this->table);
        if (count($condicion)) {
            $this->db->where($condicion);
        }
        $this->db->join('users as cliente','ventas.id_cliente = cliente.id','left');
        $this->db->join('users as lider','ventas.id_lider = lider.id','left');
        $query = $this->db->get();
        return $query->result();
    }
    public function insert($data){
        $query = $this->db->insert($this->table, $data);
        $id_venta = $this->db->insert_id();
        return $id_venta;
    }
}
