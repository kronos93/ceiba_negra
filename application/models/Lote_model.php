<?php 

class Lote_model extends CI_Model {
    private $tabla = "lotes";
    public function __construct() {
        parent::__construct();
         $this->load->database();
    }

    public function getAll(){
        $query = $this->db->query("SELECT * FROM {$this->tabla}");
        return $query->result();
    }

    public function getAllM($mz){
        //SELECT concat('m',id_manzana,'lote',lote) as id, concat('Lote número 1') as title, lotes.x,lotes.y FROM lotes WHERE id_manzana = 5
        $query = $this->db->query("SELECT concat('m',id_manzana,'lote',lote) as id, concat('Lote número ', lote) as title, concat('mz',id_manzana) as category, lotes.x,lotes.y FROM lotes WHERE id_manzana = {$mz}");
        return $query->result();
    }

}