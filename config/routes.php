<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    // --- DEFINIR LA RUTA ABSOLUTA DE LA RAÍZ DEL PROYECTO ---
    // Asumiendo que routes.php está en /var/www/html/singepolep/config/
    // dirname(__FILE__) es /var/www/html/singepolep/config/
    // dirname(dirname(__FILE__)) es /var/www/html/singepolep/
    define('ROOT_PATH', dirname(dirname(__FILE__)));

    // Incluye el archivo de configuración de la base de datos usando ROOT_PATH
    // require_once ROOT_PATH . '/config/database.php'; 

    // Enrutador
    $error = '';
    $ruta = trim($_GET['v'] ?? 'home');
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domain = $_SERVER['HTTP_HOST'];
    $path = $_SERVER['REQUEST_URI'];
    $url = $protocol . $domain . $path;
    $parsed_url = parse_url($url);
    if (isset($_GET['v'])) {
        $query = $parsed_url['query'];
        parse_str($query, $params);
    }
    else {
        $params = null;
    }

    if ($ruta == 'home') {
        // Usar ROOT_PATH para las vistas
        include ('controllers/HomeController.php');
    }
    else {
        $partes = explode('/', $ruta);
        if (!preg_match('/^[a-zA-Z0-9_\/]+$/', $ruta)) {
            $error = "Error: Formato de ruta inválido. Use solo letras, números, guiones bajos y '/'.";
            // Usar ROOT_PATH para las vistas
            include (ROOT_PATH . '/views/error/error.php'); // Esto afecta a la línea 46
        }
        else {
            $partes = explode('/', $ruta);

            if (count($partes) > 2) {
                $error = "Error en la ruta. Formato correcto: controlador/accion";
                // Usar ROOT_PATH para las vistas
                include (ROOT_PATH . '/views/error/error.php');
            }
            else {
                $controladorNombre = ucfirst($partes[0]) . 'Controller';
                $accion = (count($partes) == 1) ? 'list' : $partes[1];
                // Construir la ruta del controlador usando ROOT_PATH
                $archivoControlador = ROOT_PATH . '/controllers/' . $controladorNombre . '.php';

                if (!file_exists($archivoControlador)) {
                    $error = "Error: Controlador '$controladorNombre' no encontrado.";
                    // Usar ROOT_PATH para las vistas
                    include (ROOT_PATH . '/views/error/error.php');
                }
                else {
                    require_once $archivoControlador;
    
                    if (!class_exists($controladorNombre)) {
                        $error = "Error: Clase '$controladorNombre' no encontrada en el archivo.";
                        // Usar ROOT_PATH para las vistas
                        include (ROOT_PATH . '/views/error/error.php');
                    }
                    else {
                        $controller = new $controladorNombre(); 
    
                        if (!method_exists($controller, $accion)) {
                            $error = "Error: Método '$accion' no encontrado en el controlador '$controladorNombre'.";
                            // Usar ROOT_PATH para las vistas
                            include (ROOT_PATH . '/views/error/error.php');
                        }
                        else {
                            // if (in_array($accion, ['save', 'searchAll', 'searchMun', 'searchEditMun', 'searchPar', 'searchConsejo', 'searchUbch'])) {
                                $controller->$accion($_POST);
                            // } else {
                                // $controller->$accion($params);
                            // }
                        }
                    }
                }
            }
        }
    }
?>
