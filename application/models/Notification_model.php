<?php 
class Notification_model extends CI_Model {
    private $table = "notifications";
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
    public function get(){       
        $this->db->from($this->table);        
        $query = $this->db->get();
        return $query->result();
    }
    public function limit($number){
        $this->db->limit($number);
        return $this;
    }
    public function order_by($order){
        $this->db->order_by($order);
        return $this;
    }
}