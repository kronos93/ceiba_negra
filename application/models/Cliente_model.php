<?php

class Cliente_model extends CI_Model {
    private $tabla = "clientes";
    private $select = "id_cliente, nombre, apellidos, correo, telefono, calle, no_ext, no_int, colonia, municipio, estado, ciudad, cp";
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function getAll($like = [])
    {
        $this->db->select($this->select);
        if(count($like)){
            $this->db->like($like);
        }
        $query = $this->db->get($this->tabla);
        return $query->result();
    }

    public function clientes_autocomplete($like)
    {
        $this->select .= ", CONCAT(nombre, ' ' ,apellidos) AS data, CONCAT(nombre, ' ' ,apellidos) AS value";
        $response = $this->getAll($like);
        return $response;
    }
}
