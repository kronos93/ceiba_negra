<?php
class HuertosVentas_model extends CI_Model {
    private $table = "huertos_ventas";
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function insert_batch($data) {
        $this->db->insert_batch($this->table, $data);
    }
}
