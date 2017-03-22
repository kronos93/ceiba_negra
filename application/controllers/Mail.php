<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Carbon\Carbon;

class Mail extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Notification_model');
    }
    public function index()
    {
        $date = Carbon::now(); //Hoy
        $limit_per_hour = 5;   //Limite por hora
        $limit_per_mounth = 10;//Limite por mes
        $last_insert = $this->Notification_model->limit(1)
                                                ->order_by('id_notification DESC')
                                                ->get();
        //Sí ya ha habido notificación entonces:
        if (count($last_insert)) {
            //Fecha de la última inserción
            $date_last_insert = Carbon::createFromFormat('Y-m-d H:i:s', "{$last_insert[0]->date} {$last_insert[0]->time}");
            //Número de los ultimos enviados por hora
            $last_enviados_por_hora = $this->Notification_model->where("#HOUR(time) = {$date_last_insert->format('H')} && 
                                                                        MINUTE(time) = {$date_last_insert->format('i')} && 
                                                                        YEAR(date) = {$date_last_insert->format('Y')} && 
                                                                        MONTH(date) = {$date_last_insert->format('m')}
                                                                        ")
                                                                        ->get();
            $last_enviados_por_mes = $this->Notification_model->where("#HOUR(time) = {$date_last_insert->format('H')} && 
                                                                       #MINUTE(time) = {$date_last_insert->format('i')} && 
                                                                        YEAR(date) = {$date_last_insert->format('Y')} && 
                                                                        MONTH(date) = {$date_last_insert->format('m')}
                                                                        ")
                                                                        ->get();
           /* var_dump((int) $date->format('m') );
            var_dump((int) $date_last_insert->format('m') );
            var_dump(((int) $date->format('m') > (int) $date_last_insert->format('m')));*/
            if(count($last_enviados_por_mes) <  $limit_per_mounth || ((int) $date->format('m') > (int) $date_last_insert->format('m'))){
                //Este mes te sobran correos
                echo "<p>Este mes puedes enviar correos</p>";
                if (count($last_enviados_por_hora) == $limit_per_hour && $date->diffInMinutes($date_last_insert) < 1) {
                    echo "<p>Ya no puedes enviar :(, hasta {$date_last_insert->addMinute()->format('H:i:s')}</p>";
                } else {
                    $enviados_por_hora = $this->Notification_model->where("#HOUR(time) = {$date->format('H')} && 
                                                                            MINUTE(time) = {$date->format('i')} && 
                                                                            YEAR(date) = {$date->format('Y')} && 
                                                                            MONTH(date) = {$date->format('m')}
                                                                            ")
                                                                        ->get();
                    if (count($enviados_por_hora) == $limit_per_hour) {
                        //No podrá enviar hasta futuro
                        echo "<p>No puedes enviar hasta el siguiente minuto</p>";
                    } else {
                        $this->Notification_model->insert(
                                                            [
                                                                "id_notification" => null,
                                                                "date" => $date->format('Y-m-d'),
                                                                "time" => $date->format('H:i:s')
                                                            ]
                                                        );
                    }
                }
            }else{
                echo "<p>Agotaste tu cuota mensual, espera hasta {$date->endOfMonth()->addDays(1)->format('m-Y')}  para seguir enviando correos</p>";
            }
        //Sino se ha notificado
        } else {
            $this->Notification_model->insert(
                [
                    "id_notification" => null,
                    "date" => $date->format('Y-m-d'),
                    "time" => $date->format('H:i:s')
                ]
            );
        }
    }
}
