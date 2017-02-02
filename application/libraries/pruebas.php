<?php   
class Puebas { 
    private $generate=[];
    public function read()
    {
        ini_set('max_execution_time', 0);
        $cadena = file_get_contents('http://localhost/ceiba_negra/docs/Datos%20del%20predio/Datos%20a%20migrar/lotes.txt', FILE_USE_INCLUDE_PATH);
        $textos = explode("\r\n", $cadena);
        $i = 0;
        $consultas = [];
        $concat = "";
        echo "<pre>";
        foreach ($textos as $key => $texto) {
            $texto = trim($texto);
            if (preg_match('/^MANZANA\s*[0-9]*$/i', $texto)) {
                //echo $texto."Inicio de bloque<br/>";
                continue;
            } else {
                if (preg_match("/LOTE NO./", $texto)) {
                    if ($i == 0) {
                        $concat = $texto . " ";
                        $i++;
                    } else {
                        array_push($this->generate, $concat);
                        $concat = $texto . " ";
                    }
                } else {
                    $concat.= $texto  . " ";
                }
            }
        }
        if (!empty($concat)) {
             array_push($this->generate, $concat);
        }
        foreach ($this->generate as $key => $valor) {
            $string_clean  = preg_replace('/ +/', ' ', $valor);
            $string_clean = trim($string_clean);
            $this->generate[$key] = $string_clean;
        }
        foreach ($this->generate as $key => $valor) {
            $string_clean  = preg_replace('/M2 MEDIDAS Y COLINDANCIAS:/', '', $valor);
            $this->generate[$key] = $string_clean;
        }
        $where = "";
        foreach ($this->generate as $key => $valor) {
            $array = explode(" AL ", $valor);
            $encontrado = [];
            foreach ($array as $clave => $a) {
                if ($clave == 0) {
                    $array[$clave] = str_replace('LOTE NO.', 'huerto=\'', $array[$clave]);
                    $array[$clave] = str_replace('MANZANA ', 'manzana=', $array[$clave]);
                    $array[$clave] = str_replace('SUPERFICIE: ', 'superficie=', $array[$clave]);
                    $array2 = explode(' ', $array[$clave]);
                    $where = "";
                    $set = "";
                    foreach ($array2 as $c => $b) {
                        if ($c == 0) {
                            $where .= " WHERE huertos.".$b."'";
                        } elseif ($c == 1) {
                            $where .= " AND manzanas.".$b;
                        } elseif ($c == 2) {
                            $b = preg_replace('/M2/', '', $b);
                            $b = preg_replace('/,+/', '', $b);
                            $set .= " SET huertos.".$b.'';
                            $c_precio = explode('=', $b);
                            $calculo = (float) $c_precio[1] * (110000 / 312.5);
                            $set .= ', huertos.precio='.$calculo;
                        }
                    }
                } else {
                    $buscar = ['norte','noreste','este','sureste','sur','suroeste','oeste','noroeste'];
                    foreach ($buscar as $busca) {
                        if (preg_match("/\b{$busca}\b/i", $array[$clave])) {
                            $array[$clave] = str_replace('CHIT', 'CHIÍT', $array[$clave]);
                            $array[$clave] = str_replace('BAMBU', 'BAMBÚ', $array[$clave]);
                            $array[$clave] = str_replace('BALCHE', 'BALCHÉ', $array[$clave]);
                            $array[$clave] = preg_replace("/{$busca}\s*/i", "huertos.col_{$busca}=\"", $array[$clave]);
                            $array[$clave] = $array[$clave] .= "\"";
                            if (strlen(preg_replace("/huertos.col_{$busca}\s*/i", "", $array[$clave])) >= 399) {
                                echo preg_replace("/huertos.col_{$busca}\s*/i", "", $array[$clave]);
                                echo $array[$clave];
                                echo "<h1>ENORME ERROR</h1>";
                                echo strlen($array[$clave]);
                                die();
                            }
                            $set .= ', '. $array[$clave];
                            if (!in_array($busca, $encontrado)) {
                                array_push($encontrado, $busca);
                            }
                        } else {
                            //$set .=  ", huerto.col_{$busca} = ''";
                        }
                    }
                }
            }
            $dif = array_diff($buscar, $encontrado);
            foreach ($dif as $d) {
                $set .=  ", huertos.col_{$d} = ''";
            }

            $sql = "UPDATE huertos LEFT JOIN manzanas ON huertos.id_manzana = manzanas.id_manzana{$set}{$where}";
            array_push($consultas, $sql);
        }
        foreach ($consultas as $key => $consulta) {
            $afectar = $this->Huerto_model->run_sql($consulta);
             var_dump($key);
            var_dump($consulta);
            var_dump($afectar);
        }
        echo "</pre>";
    }
    public function read2()
    {
        ini_set('max_execution_time', 0);
        $cadena = file_get_contents('http://localhost/ceiba_negra/docs/Datos%20del%20predio/Datos%20a%20migrar/manzanas.txt', FILE_USE_INCLUDE_PATH);
        $textos = explode("\r\n", $cadena);
        $i = 0;
        $consultas = [];
        $concat = "";
        echo "<pre>";
        foreach ($textos as $key => $texto) {
            $texto = trim($texto);
            if (preg_match("/MANZANA \s*/", $texto)) {
                if ($i == 0) {
                    $concat = $texto . " ";
                    $i++;
                } else {
                    array_push($this->generate, $concat);
                    $concat = $texto . " ";
                }
            } else {
                $concat.= $texto  . " ";
            }
        }
        if (!empty($concat)) {
             array_push($this->generate, $concat);
        }
        //Quitar espacios en blanco de más
        foreach ($this->generate as $key => $valor) {
            $string_clean  = preg_replace('/ +/', ' ', $valor);
            $string_clean = trim($string_clean);
            $this->generate[$key] = $string_clean;
        }
        //Quitar palabra
        foreach ($this->generate as $key => $valor) {
            $string_clean  = preg_replace('/M2 MEDIDAS Y COLINDANCIAS:\s*/', ' ', $valor);
            $this->generate[$key] = $string_clean;
        }
        $where = "";
        foreach ($this->generate as $key => $valor) {
            $array = explode(" AL ", $valor);
            $encontrado = [];
            foreach ($array as $clave => $a) {
                if ($clave == 0) {
                    $array[$clave] = str_replace('MANZANA ', 'manzana=', $array[$clave]);
                    $array[$clave] = str_replace('SUPERFICIE: ', 'superficie=', $array[$clave]);
                    $array2 = explode(' ', $array[$clave]);
                    $where = "";
                    $set = "";
                    foreach ($array2 as $c => $b) {
                        if ($c == 0) {
                            $where .= " WHERE manzanas.".$b."";
                        } elseif ($c == 1) {
                            $b = preg_replace('/M2/', '', $b);
                            $b = preg_replace('/,+/', '', $b);
                            $set .= " SET manzanas.".$b.'';
                            /*$c_precio = explode('=',$b);
                            $calculo = (float) $c_precio[1] * (110000 / 312.5);
                            $set .= ', manzana.precio='.$calculo;*/
                        }
                    }
                } else {
                    $buscar = ['norte','noreste','este','sureste','sur','suroeste','oeste','noroeste'];
                    foreach ($buscar as $busca) {
                        if (preg_match("/\b{$busca}\b/i", $array[$clave])) {
                            $array[$clave] = preg_replace("/{$busca}\s*/i", "manzanas.col_{$busca}=\"", $array[$clave]);
                            $array[$clave] = $array[$clave] .= "\"";
                            $set .= ', '. $array[$clave];
                            if (!in_array($busca, $encontrado)) {
                                array_push($encontrado, $busca);
                            }
                        }
                    }
                }
            }
            $dif = array_diff($buscar, $encontrado);
            foreach ($dif as $d) {
                $set .=  ", manzanas.col_{$d} = ''";
            }

            $sql = "UPDATE manzanas {$set}{$where}";
            array_push($consultas, $sql);
        }
        foreach ($consultas as $key => $consulta) {
            $afectar = $this->Huerto_model->run_sql($consulta);
            var_dump($key);
            var_dump($consulta);
            var_dump($afectar);
        }
        echo "</pre>";
    }
    public function generate()
    {
        var_dump($this->generate);
    }
    public function correos()
    {
        $this->load->library('email');

        $this->email->from('admin@huertoslaceiba.com', 'Administrador');
        $this->email->to('samuel_-_rojas@hotmail.com');
       // $this->email->cc('another@another-example.com');
       // $this->email->bcc('them@their-example.com');

        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');

        $this->email->send();

        $this->email->clear();
         $this->email->from('huertosl@huertoslaceiba.com', 'Administrador 2');
        $this->email->to('samuel_-_rojas@hotmail.com');
       // $this->email->cc('another@another-example.com');
       // $this->email->bcc('them@their-example.com');

        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');

        $this->email->send();
    }
}