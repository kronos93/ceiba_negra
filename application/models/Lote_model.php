<?php 

class Lote_model extends CI_Model {
    private $tabla = "lotes";
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getAll(){
        $query = $this->db->get("{$this->tabla}");
        return $query->result();
    }
    public function insert($insert){
        $this->db->insert($this->tabla, $insert);
        return $this->db->insert_id();
    }
    //Lotes precio y manzanas
    public function lotesPM(){
        $this->db->select(" {$this->tabla}.`id_lote`,
                            `manzanas`.`manzana`,
                            {$this->tabla}.`lote`,
                            {$this->tabla}.`superficie`,
                            {$this->tabla}.`precio`,
                            {$this->tabla}.`vendido`,
                            {$this->tabla}.`col_norte`,
                            {$this->tabla}.`col_sur`,
                            {$this->tabla}.`col_este`,
                            {$this->tabla}.`col_oeste`");
        $this->db->from("{$this->tabla}");
        $this->db->join("manzanas", "$this->tabla.id_manzana = manzanas.id_manzana",'left');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getAllM($mz){
        $query = $this->db->query("SELECT concat('m',id_manzana,'lote',lote) as id, concat('Lote número ', lote) as title, concat('mz',id_manzana) as category, lotes.x,lotes.y FROM lotes WHERE id_manzana = {$mz}");
        return $query->result();
    }

    public function set_coordenadas($where,$update){
        $this->db->set($update);
        $this->db->where($where);
        $this->db->update($this->tabla);
    }

}