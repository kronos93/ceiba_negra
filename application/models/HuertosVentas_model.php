<?php
class HuertosVentas_model extends CI_Model {
    private $table = "huertos_ventas";
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
    public function insert_batch($data) {
        $this->db->insert_batch($this->table, $data);
    }
    public function insert_batch_2($data) {
        $this->db->insert_batch("historial", $data);
    }
}
