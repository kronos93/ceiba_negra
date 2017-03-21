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
    public function get(){       
        $this->db->from($this->table);        
        $query = $this->db->get();
        return $query->result();
    }
    public function delete(){
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
    public function group_by($group){
        $this->db->group_by($group);
        return $this;
    }
    public function count_all(){
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
}