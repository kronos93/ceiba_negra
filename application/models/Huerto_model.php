<?php

class Huerto_model extends CI_Model
{
    private $tabla = "huertos";
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAll()
    {
        $query = $this->db->get("{$this->tabla}");
        return $query->result();
    }
    public function insert($insert)
    {
        $this->db->insert($this->tabla, $insert);
        $id_nuevo_huerto = $this->db->insert_id();
        $where = ['id_huerto' => $id_nuevo_huerto];
        $nuevo_huerto = $this->huertosPM($where);
        return $nuevo_huerto;
    }
    //Huertos precio y manzanas
    public function getHuertosPM($where = [], $type_return = "array")
    {
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
                            {$this->tabla}.`col_noreste`,
                            {$this->tabla}.`col_este`,
                            {$this->tabla}.`col_sureste`,
                            {$this->tabla}.`col_sur`,
                            {$this->tabla}.`col_suroeste`,
                            {$this->tabla}.`col_oeste`,
                            {$this->tabla}.`col_noroeste`");
        $this->db->from("{$this->tabla}");
        $this->db->join("precios", "$this->tabla.id_precio = precios.id_precio", 'left');
        $this->db->join("manzanas", "$this->tabla.id_manzana = manzanas.id_manzana", 'left');
        if (count($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        if ($type_return == "array") {
            return $query->result_array();
        } elseif ($type_return == "object") {
            return $query->result();
        }
    }
    public function update($set, $where)
    {
        $this->db->set($set);
        $this->db->where($where);
        $this->db->update($this->tabla);
        if ($this->db->affected_rows()) {
            return $this->huertosPM($where);
        } else {
            return [];
        }
    }
    public function getLevel($mz)
    {
        $this->db->select(" CONCAT('m',manzanas.manzana,'lote',{$this->tabla}.huerto) as id,         
                            CONCAT('Huerto número ', {$this->tabla}.huerto) as title, 
                            CONCAT('mz',manzanas.manzana) as category, 
                            {$this->tabla}.id_huerto as link, 
                            {$this->tabla}.x,
                            {$this->tabla}.y ");
        $this->db->join("manzanas", "{$this->tabla}.id_manzana = manzanas.id_manzana", 'left');
        $this->db->from("{$this->tabla}");
        $this->db->where( ["manzanas.manzana" => "{$mz}"] );
        $query = $this->db->get();
        return $query->result();
    }

    public function set_coordenadas($where, $update)
    {
        $this->db->set($update);
        $this->db->where($where);
        $this->db->update($this->tabla);
    }
}