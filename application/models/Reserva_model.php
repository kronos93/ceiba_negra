<?php 
class Reserva_model extends CI_Model {
    private $table = "reservas";
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function insert($data){
        $query = $this->db->insert($this->table, $data);
        $id_reserva = $this->db->insert_id();
        return $id_reserva;
    }
     public function where($condicion){
        $this->db->where($condicion);
        return $this;
    }
    public function count_all(){
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
}