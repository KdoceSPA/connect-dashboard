<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once dirname(__FILE__) . '/../models/InstallationsModel.php';

    $controller = new InstallationsController();
    
    class InstallationsController {
        private $server;
        private $endpoint;
        private $model;
        
        public function __construct() {
            $server = $_SERVER["SERVER_NAME"];
            if ($server == 'localhost') {
                $this->endpoint = 'http://localhost:3000';
                $server = 'http://localhost/' . basename($_SERVER['DOCUMENT_ROOT']) . '/master/';
            }
            else {
                $this->endpoint = 'https://classtalk.masterclass.cl:3000';
            }
            $this->server = $server;
            $this->model = new InstallationsModel();
        }
        
        public function list() {
            $token = $this->model->getToken($this->endpoint, '1', '1');
            $installations = $this->model->list($this->endpoint, '1', '1', $token);
            require_once 'views/installations/installations_list.php';
        }

        public function searchSchoolByRbd($data) {
            $response = $this->model->searchSchoolByRbd($this->endpoint, '1', $data['rbd'], $data['token']);
            echo $response;
        }

        public function saveInstallation($data) {
            $response = $this->model->saveInstallation($this->endpoint, '1', $data['arrFields'], $data['token']);
            echo $response;
        }

        public function deleteInstallation($data) {
            $response = $this->model->deleteInstallation($this->endpoint, $data['id'], $data['token']);
            echo $response;
        }
    }
?>