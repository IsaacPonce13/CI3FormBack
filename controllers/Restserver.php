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

    // // Insertar un maestro (requiere JSON en el body)
    // public function teacher_post() {
    //     $data = json_decode(file_get_contents("php://input"), true);
    //     if ($this->Teachers_model->insert($data)) {
    //         $this->response(['message' => 'maestro agregado correctamente'], REST_Controller::HTTP_CREATED);
    //     } else {
    //         $this->response(['message' => 'Error al agregar maestro'], REST_Controller::HTTP_BAD_REQUEST);
    //     }
    // }

    public function teacher_post() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data || empty($data['name']) || empty($data['lastname'])) {
            $this->response(['message' => 'Datos inválidos'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // Insertar el maestro en la base de datos
        $teacher_id = $this->Teachers_model->insert_teacher($data);

        if ($teacher_id) {
            $this->response(['message' => 'Maestro agregado correctamente'], REST_Controller::HTTP_CREATED);
        } else {
            $this->response(['message' => 'Error al agregar maestro'], REST_Controller::HTTP_BAD_REQUEST);
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
