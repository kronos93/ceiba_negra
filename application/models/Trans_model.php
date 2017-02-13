<?php

class Trans_model extends CI_Model {
    public function __construct()
    {
        parent::__construct () ;
        $this->load->database();
    }
    public function begin () {
        $this->db->trans_begin();
    }
    public function start () {
        $this->db->trans_start();
    }
    public function complete ()  {
        $this->db->trans_complete();
    }
    public function commit () {
        $this->db->trans_commit();
    }        
    public function rollback(){
        $this->db->trans_rollback();
    }
    public function status () {
        return $this->db->trans_status();
    }
}