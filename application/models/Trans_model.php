<?php

class Trans_model extends CI_Model {
    public function __construct()
    {
        parent::__construct () ;
        $this->load->database();
    }
    public function start () {
        $this->db->trans_start();
    }
    public function complete ()  {
        $this->db->trans_complete();
    }
    public function status () {
        return $this->db->trans_status();
    }
}