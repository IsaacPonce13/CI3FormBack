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

    // Agregar un  maestro
    public function insert_teacher($data) {
        return $this->db->insert('maestros', $data);
    }

    public function update_teacher($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('maestros', $data); // Retorna true si la actualización fue exitosa
    }    

    // Eliminar maestro por ID
    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }
}
