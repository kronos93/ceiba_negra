<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Utils {
    protected $CI;
    public function __construct()
	{
        $this->CI =& get_instance();
       /* 
        $this->CI->load->model('Reserva_model');*/

    }
    public function getInitials($name){
        //split name using spaces
        $words=explode(" ",trim($name));
        $inits='';
        //loop through array extracting initial letters
            foreach($words as $word){
            $inits.=strtoupper(substr($word,0,1));
            }
        return $inits;	
    }
}