<?php 

class Precio_model extends CI_Model {
    private $table = "precios";
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getAll(){
        $query = $this->db->get("{$this->table}");
        return $query->result();
    }
    public function select($select){
        $this->select = $select;
        return $this;
    }
    public function get() {       
        $this->db->from($this->table);        
        $query = $this->db->get();
        return $query->result();
    }
}