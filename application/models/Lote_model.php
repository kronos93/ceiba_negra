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
    public function lotesPM(){
        $query =  $this->db->query("SELECT 
                                            `lotes`.`id_lote`,`manzanas`.`manzana`,`lotes`.`lote`,`lotes`.`superficie`,`precios`.`precio`,`precios`.`enganche`,`precios`.`pagos`,`lotes`.`vendido`,`lotes`.`col_norte`,`lotes`.`col_sur`,`lotes`.`col_este`,`lotes`.`col_oeste` FROM {$this->tabla} 
                                    LEFT JOIN `manzanas`
                                    ON lotes.id_manzana = manzanas.id_manzana
                                    LEFT JOIN `precios` 
                                    ON lotes.id_precio = precios.id_precio");
        return $query->result_array();
    }
    public function getAllM($mz){
        $query = $this->db->query("SELECT concat('m',id_manzana,'lote',lote) as id, concat('Lote número ', lote) as title, concat('mz',id_manzana) as category, lotes.x,lotes.y FROM lotes WHERE id_manzana = {$mz}");
        return $query->result();
    }

}