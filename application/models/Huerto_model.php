<?php 

class Huerto_model extends CI_Model {
    private $tabla = "huertos";
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
        $id_nuevo_huerto = $this->db->insert_id();
        $where = ['id_huerto' => $id_nuevo_huerto];
        $nuevo_huerto = $this->huertosPM($where);
        return $nuevo_huerto;
    }
    //Huertos precio y manzanas
    public function huertosPM($where = []){
        $this->db->select(" {$this->tabla}.`id_huerto`,
                            {$this->tabla}.`id_precio`,
                            `manzanas`.`id_manzana`, 
                            `manzanas`.`manzana`,
                            {$this->tabla}.`huerto`,
                            {$this->tabla}.`superficie`,
                            `precios`.`precio`,
                            `precios`.`enganche`,
                            `precios`.`abono`,
                            {$this->tabla}.`vendido`,
                            {$this->tabla}.`col_norte`,
                            {$this->tabla}.`col_sur`,
                            {$this->tabla}.`col_este`,
                            {$this->tabla}.`col_oeste`");
        $this->db->from("{$this->tabla}");
        $this->db->join("precios", "$this->tabla.id_precio = precios.id_precio",'left');
        $this->db->join("manzanas", "$this->tabla.id_manzana = manzanas.id_manzana",'left');
        if(count($where)){
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getAllM($mz){
        //Hacer esto un JOIN
        $query = $this->db->query("
                SELECT 
                    concat('m',id_manzana,'lote',huerto) as id, 
                    concat('Huerto número ', huerto) as title, 
                    concat('mz',id_manzana) as category, 
                    huertos.x,
                    huertos.y 
                FROM 
                    huertos 
                WHERE id_manzana = {$mz}");
        return $query->result();
    }

    public function set_coordenadas($where,$update){
        $this->db->set($update);
        $this->db->where($where);
        $this->db->update($this->tabla);
    }

}