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
    public function table(){
        return $this->table;
    }
    public function from(){
         $this->db->from($this->table); 
         return $this;
    }
    /*public function valid_opcion_pago($input){
        $enganche = $this->input->post('enganche');
        $abono = $this->input->post('abono');

        $same_op = $this->db->from($this->table)
                 ->where([
                    'enganche' => $enganche,
                    'abono' => $abono,
                    ])
                 ->count_all_results();
        if($same_op){
            return false;
        }else{
            return true;
        }        
        
    }*/
}