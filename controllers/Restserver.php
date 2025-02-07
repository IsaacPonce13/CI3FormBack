<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Restserver extends REST_Controller {
    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit; // Finaliza la solicitud preflight
        }
        $this->load->database();
        $this->load->model('Teachers_model');
    }

    public function test_get() {
        $maestros = $this->Teachers_model->findAll();
        if (!empty($maestros)) {
            $this->response($maestros, REST_Controller::HTTP_OK); // Código 200
        } else {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron maestros'
            ], REST_Controller::HTTP_NOT_FOUND); // Código 404
        }
    }
    // Obtener un maestro por ID
    public function teacher_get($id) {
        $Teachers = $this->Teachers_model->findById($id);
        if ($Teachers) {
            $this->response($Teachers, REST_Controller::HTTP_OK);
        } else {
            $this->response(['message' => 'maestro no encontrado'], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    // Agregar un maestro
    public function teacher_post() {
        // Obtener los datos enviados en la solicitud POST
        $data = json_decode($this->input->raw_input_stream, true);
    
        if (!$data) {
            $this->response(['message' => 'Datos no válidos'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    
        // Convertir el array de materias en JSON
        if (isset($data['materias']) && is_array($data['materias'])) {
            $data['materias'] = json_encode($data['materias']);
        }
    
        // Insertar los datos en la base de datos
        $inserted = $this->Teachers_model->insert_teacher($data);
    
        if ($inserted) {
            $this->response(['message' => 'Maestro agregado correctamente'], REST_Controller::HTTP_CREATED);
        } else {
            $this->response(['message' => 'Error al agregar maestro'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    

// Actualizar información de un maestro
public function teacher_put($id) {
    // Obtener los datos enviados en la solicitud PUT
    $data = json_decode($this->input->raw_input_stream, true);

    if (!$data) {
        $this->response(['message' => 'Datos no válidos'], REST_Controller::HTTP_BAD_REQUEST);
        return;
    }

    // Convertir el array de materias en JSON si existe
    if (isset($data['materias']) && is_array($data['materias'])) {
        $data['materias'] = json_encode($data['materias']);
    }

    // Intentar actualizar el maestro
    $updated = $this->Teachers_model->update_teacher($id, $data);

    if ($updated) {
        $this->response(['message' => 'Maestro actualizado correctamente'], REST_Controller::HTTP_OK);
    } else {
        $this->response(['message' => 'Error al actualizar maestro'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    }
}


    // Eliminar un maestro por ID
    public function teacher_delete($id) {
        if ($this->Teachers_model->delete($id)) {
            $this->response(['message' => 'maestro eliminado'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['message' => 'Error al eliminar maestro'], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
