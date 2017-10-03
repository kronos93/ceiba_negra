<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Celebraciones {
    protected $CI;
    public function __construct()
	{
        $this->CI =& get_instance();

    }
    public function birthdays() {
        return $this->CI->ion_auth
                            ->where("DATE_FORMAT(fecha_nacimiento,'%m-%d') = DATE_FORMAT(NOW(),'%m-%d')")
                            ->users()
                            ->result();
    }
}
