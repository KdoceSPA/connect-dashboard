<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once dirname(__FILE__) . '/../models/SchoolModel.php';

    $controller = new SchoolController();
    
    // Redirect functions
    $controller->list();
    
    class SchoolController {
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
            $this->model = new SchoolModel();
        }
        
        public function list() {
            $token = $this->model->getToken($this->endpoint, '1', '1');
            $schools = $this->model->list($this->endpoint, '1', '1', $token);
            $totalRbds = 0;
            $totalGroups = 0;
            $totalDevices = 0;
            foreach ($schools['data']['data'] as $school) {
                $totalRbds++;
                $totalGroups += $school['grupos'];
                $totalDevices += $school['dispositivos'];
            }
            require_once 'views/home/inicio.php';
        }
    }
?>