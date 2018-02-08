<?php

class Huerto_model extends CI_Model
{
    //Precio por m^2 = 110000 / 312.5
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
        $nuevo_huerto = $this->getHuertosPM($where);
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
                            {$this->tabla}.`precio_x_m2`,
                            ({$this->tabla}.`superficie` * {$this->tabla}.`precio_x_m2`) as precio,
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
        $this->db->order_by("CONVERT( {$this->tabla}.huerto ".','."decimal ) ASC");
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
            return $this->getHuertosPM($where);
        } else {
            return [];
        }
    }
    public function getLevel($mz)
    {
        $description = $this->ion_auth->in_group('administrador') ? "<a href=\"./registros/pagos/',ventas.id_venta,'\"><i class=\"fa fa-user\" aria-hidden=\"true\"></i> ',users.first_name,' ',users.last_name,'</a>" : "";
        $this->db->select(" CONCAT('m',
                                    manzanas.manzana,
                                    'lote',
                                    huertos.huerto) AS id,
                            CONCAT('Huerto ', huertos.huerto) AS title,
                            CONCAT('mz', manzanas.manzana) AS category,
                            IF( manzanas.disponibilidad = 0,
                                '#7f8c8d',
                                IF(huertos.vendido = 0,
                                    '',
                                    IF(huertos.vendido = 1,
                                        '#2980b9',
                                        IF(huertos.vendido = 2,
                                            '#d35400',
                                            IF(huertos.vendido = 3,
                                                '#f1c40f',
                                                '#8e44ad'
                                            )
                                        )
                                    )
                                )
                            ) AS fill,
                            CONCAT('<div>Superficie: <span class=\"superficie\">',
                                    huertos.superficie,
                                    '</div>',
                                    '<div>Precio: <span class=\"currency\">',
                                    (huertos.precio_x_m2 * huertos.superficie),
                                    '</span></div>',
                                    IF(huertos.vendido = 0,
                                        '<div>Estado: Huerto disponible</div>',
                                        IF(huertos.vendido = 1,
                                            CONCAT('<div>Estado: Huerto vendido / en proceso de pago</div>','<div>{$description}</div>'),
                                            IF(huertos.vendido = 2,
                                                CONCAT('<div>Estado: Huerto vendido / saldado en venta</div>','<div>{$description}</div>'),
                                                IF(huertos.vendido = 3,
                                                    CONCAT('<div>Estado: Huerto vendido / saldado en pagos</div>','<div>{$description}</div>'),
                                                    '<div>Estado: Huerto reservado</div>'))))) AS description,
                            IF(manzanas.disponibilidad = 0,
                                '',
                                IF(huertos.vendido = 0,
                                    huertos.id_huerto,
                                    '')) AS link,
                            huertos.x,
                            huertos.y ");
        $this->db->from("{$this->tabla}");
        $this->db->join("manzanas", "{$this->tabla}.id_manzana = manzanas.id_manzana", 'left');
        $this->db->join("huertos_ventas", "huertos.id_huerto = huertos_ventas.id_huerto", 'left');
        $this->db->join("ventas", "huertos_ventas.id_venta = ventas.id_venta", 'left');
        $this->db->join("users", "ventas.id_cliente = users.id", 'left');
        $this->db->where("manzanas.manzana = {$mz}");
        $this->db->order_by("CONVERT( {$this->tabla}.huerto ".','."decimal ) ASC");
        $query = $this->db->get();
        return $query->result();
    }

    public function set_coordenadas($where, $update)
    {
        $this->db->set($update);
        $this->db->where($where);
        $this->db->update($this->tabla);
    }
    public function run_sql($sql)
    {
        $query = $this->db->query($sql);
        return $this->db->affected_rows();
    }
    public function where($condicion)
    {
        $this->db->where($condicion);
        return $this;
    }
    public function select($select)
    {
        $this->db->select($select);
        return $this;
    }
    public function join($table_join, $condicion, $type = 'left')
    {
        $this->db->join($table_join, $condicion, $type);
        return $this;
    }
    public function where_in($key, $values)
    {
        $this->db->where_in($key, $values);
        return $this;
    }
    public function get()
    {
        $this->db->from($this->tabla);
        $query = $this->db->get();
        return $query->result();
    }
    public function update_batch($data, $ref)
    {
        return $this->db->update_batch($this->tabla, $data, $ref);
    }
}
