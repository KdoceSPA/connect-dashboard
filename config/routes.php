<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Enrutador
    $error = '';
    $ruta = trim($_GET['v'] ?? 'home'); // Coalescencia nula y trim
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
        include ('controllers/SchoolController.php');
    }
    else {
        $partes = explode('/', $ruta);
        if (!preg_match('/^[a-zA-Z0-9_\/]+$/', $ruta)) {
            $error = "Error: Formato de ruta inválido. Use solo letras, números, guiones bajos y '/'.";
            include ('views/error/error.php');
        }
        else {
            $partes = explode('/', $ruta);

            if (count($partes) > 2) {
                $error = "Error en la ruta. Formato correcto: controlador/accion";
                include ('views/error/error.php');
            }
            else {
                $controladorNombre = ucfirst($partes[0]) . 'Controller';
                $accion = (count($partes) == 1) ? 'list' : $partes[1];
                $archivoControlador = 'controllers/' . $controladorNombre . '.php';
                if (!file_exists($archivoControlador)) {
                    $error = "Error: Controlador '$controladorNombre' no encontrado.";
                    include ('views/error/error.php');
                }
                else {
                    $path = dirname(dirname(__FILE__)).'/controllers/StatisticsController.php';
                    require_once($path);
                }
            }
        }
    }
?>
