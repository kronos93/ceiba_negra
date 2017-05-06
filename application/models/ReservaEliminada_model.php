<?php 
class ReservaEliminada_model extends CI_Model {
    private $table = "reservas_eliminadas";
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        
    }
 
}