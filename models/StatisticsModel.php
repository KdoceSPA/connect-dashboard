<?php
    class SchoolModel {
        private $conexion;

        public function __construct() {

        }

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
        
        public function list($endpoint, $id, $rbd, $token) {
            // URL del endpoint
            $urlEndPoint = $endpoint . '/schools/extended';

            // Datos a enviar
            $data = array(
                'token' => $token,
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

            // Establecer opciones para la petición (GET)
            curl_setopt($ch, CURLOPT_URL, $urlEndPoint . '?' . http_build_query(['json' => $jsonData])); // Adjuntamos datos JSON a la URL
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["token: $token"]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // Realizar la petición GET
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

                // Decodificar la respuesta JSON
                // ($option == 1) ? $data = json_decode($response, true) : $data = $response;
                $data = json_decode($response, true);
                // $data = $response;

                // Procesar datos recibidos
                return $data;
            }
        }

        function getAllUsers($endpoint, $token) {
            // URL del endpoint
            $urlEndPoint = $endpoint . '/allUsers';

            // Inicializar cURL
            $ch = curl_init();

            // Establecer opciones para la petición (GET)
            curl_setopt($ch, CURLOPT_URL, $urlEndPoint); // Adjuntamos datos JSON a la URL
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["token: $token"]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // Realizar la petición GET
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

                // Decodificar la respuesta JSON
                $data = json_decode($response, true);

                // Procesar datos recibidos
                return $data;
            }
        }

        function getAllEvents($endpoint, $token) {
            // URL del endpoint
            $urlEndPoint = $endpoint . '/events/all';

            // Inicializar cURL
            $ch = curl_init();

            // Establecer opciones para la petición (GET)
            // curl_setopt($ch, CURLOPT_URL, $urlEndPoint); // Adjuntamos datos JSON a la URL
            curl_setopt($ch, CURLOPT_URL, $urlEndPoint); // Adjuntamos datos JSON a la URL
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["token: $token"]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // Realizar la petición GET
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

                // Decodificar la respuesta JSON
                $data = json_decode($response, true);

                // Procesar datos recibidos
                // return $urlEndPoint;
                return $data;
            }
        }

        function getWeightings($endpoint, $token) {
            // URL del endpoint
            $urlEndPoint = $endpoint . '/weightings';

            // Inicializar cURL
            $ch = curl_init();

            // Establecer opciones para la petición (GET)
            // curl_setopt($ch, CURLOPT_URL, $urlEndPoint); // Adjuntamos datos JSON a la URL
            curl_setopt($ch, CURLOPT_URL, $urlEndPoint); // Adjuntamos datos JSON a la URL
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["token: $token"]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // Realizar la petición GET
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

                // Decodificar la respuesta JSON
                $data = json_decode($response, true);

                // Procesar datos recibidos
                // return $urlEndPoint;
                return $data;
            }
        }

        function updateWeightings($endpoint, $token, $arrFields) {
            // URL del endpoint
            $urlEndPoint = $endpoint . '/weightings';

            // Datos a enviar
            $data = array(
                'arrFields' => $arrFields
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

                // Cerrar la sesión cURL
                curl_close($ch);

                return json_encode(array('backend error'));
            }
            else {
                // Cerrar la sesión cURL
                curl_close($ch);
    
                // Decodificar la respuesta JSON
                $data = json_decode($response, true);
    
                // Procesar datos recibidos
                return $data;
            }
        }
    }
?>