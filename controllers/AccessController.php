<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once dirname(__FILE__) . '/../models/AccessModel.php';

    $controller = new AccessController();
    
    // Redirect functions
    if (isset($_GET['f'])) {
        if ($_GET['f'] == 'access') {
            $controller->access($_POST['user'], $_POST['password']);
        }
    }
    
    class AccessController {
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
            $this->model = new AccessModel();
        }
        
        public function access($user, $pass) {
            $token = $this->model->getToken($this->endpoint, '1', '1');
            $response = $this->model->access($this->endpoint, $user, $pass, $token);
            if ($response['data'] != null) {
                session_name('connect_session');
                session_start();
                $_SESSION['connect_id_user'] = $response['data']['id'];
                $_SESSION['connect_user'] = $user;
                $_SESSION['connect_type'] = $response['data']['type'];
                echo 1;
            }
            else {
                echo 0;
            }
        }
    }
?>