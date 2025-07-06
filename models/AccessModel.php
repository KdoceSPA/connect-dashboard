<?php
    class AccessModel {
        private $ipConn;
        private $userConn;
        private $passConn;
        private $databaseConn;
        private $conn;

        public function __construct() {
            require realpath(dirname(__FILE__) . '/../config/conn.php');
            $this->ipConn = $ipConn;
            $this->userConn = $userConn;
            $this->passConn = $passConn;
            $this->databaseConn = $databaseConn;
            $this->conn = new mysqli($this->ipConn, $this->userConn, $this->passConn, $this->databaseConn);
            if ($this->conn->connect_error) {
                die("Error de conexión: " . $this->conn->connect_error);
            }
        }

        // public function access2($user, $pass) {
        //     $query = "SELECT id, type_user, pass FROM users WHERE user = '" . $user . "' AND status = 1";
        //     $result = $this->conn->query($query)->fetch_all(MYSQLI_ASSOC);

        //     if (count($result) == 0) {
        //         return null;
        //     }
        //     else {
        //         $password = $result[0]['pass'];
        //         if (password_verify($pass, $password)) {
        //             return array('id' => $result[0]['id'], 'type' => $result[0]['type_user']);
        //         }
        //         else {
        //             return null;
        //         }
        //     }
        // }

        function getToken($endpoint, $id, $rbd) {
            // URL del endpoint
            $urlEndPoint = $endpoint . '/getToken';
    
            // Datos a enviar
            $data = array(
                'serverId' => null, // no masterclass
                'role' => 'UTP',
                'core_colegio' => $id,
                'RBD' => $rbd,
                'hostToconect' => ''
            );
    
            // Convertir los datos a JSON
            $jsonData = json_encode($data);
    
            // Inicializar cURL
            $ch = curl_init();
    
            // Establecer opciones para la petición (POST)
            curl_setopt($ch, CURLOPT_URL, $urlEndPoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
            // Realizar la petición
            $response = curl_exec($ch);
    
            // Verificar petición
            if (curl_errno($ch)) {
                // Error
                // die('Error al realizar la petición: ' . curl_error($ch) . ' | N° de error ' .curl_errno($ch));
    
                // Cerrar la sesión cURL
                curl_close($ch);
    
                return 'backend error';
            }
            else {
                // Cerrar la sesión cURL
                curl_close($ch);
    
                // Decodificar la respuesta JSON
                $token = json_decode($response, true)['data']['token'];
    
                // Procesar datos recibidos
                return $token;
            }
        }

        public function access($endpoint, $user, $pass, $token) {
            // URL del endpoint
            $urlEndPoint = $endpoint . '/access/getAccess';

            // Datos a enviar
            $data = array(
                'user' => $user,
                'pass' => $pass
            );

            // Convertir los datos a JSON
            $jsonData = json_encode($data);

            // Inicializar cURL
            $ch = curl_init();

            // Establecer opciones para la petición (POST)
            curl_setopt($ch, CURLOPT_URL, $urlEndPoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["token: $token", "Content-Type: application/json"]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // Realizar la petición
            $response = curl_exec($ch);

            // Verificar petición
            if (curl_errno($ch)) {
                // Error
                // die('Error al realizar la petición: ' . curl_error($ch));

                // Cerrar la sesión cURL
                curl_close($ch);

                return null;
            }
            else {
                // Cerrar la sesión cURL
                curl_close($ch);

                $data = json_decode($response, true);

                return $data;
            }
        }
    }
?>