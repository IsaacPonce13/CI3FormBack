<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teachers_model extends CI_Model {
    public $table = 'maestros'; // Definimos la tabla como privada para evitar modificaciones externas

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Obtener todos los registros
    public function findAll() {
        return $this->db->get($this->table)->result();
    }

    // Obtener un maestro por ID
    public function findById($id) {
        $query = $this->db->get_where($this->table, ['id' => $id]);
        return $query->row(); // Retorna un solo resultado
    }

    // Agregar un nuevo maestro
    public function insert($data) {
        $this->db->insert('teachers', [
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'subjects' => implode(',', $data['subjects']),
            'docs' => $data['docs']
        ]);

        return $this->db->insert_id();
        // return $this->db->insert($this->table, $data);
    }

    // Eliminar maestro por ID
    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }
}
