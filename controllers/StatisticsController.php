<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once dirname(__FILE__) . '/../models/StatisticsModel.php';

    $controller = new StatisticsController();
    
    // Redirect functions
    if (!isset($_GET['f'])) {
        $controller->list();
    }
    
    class StatisticsController {
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
            $allEvents = $this->model->getAllEvents($this->endpoint, $token);
            $weightings = $this->model->getWeightings($this->endpoint, $token);
            $month = date('m');

            // Score tables
            foreach ($schools['data']['data'] as $a => $school) {
                $arrEvents = [
                    "calendario_eventos_2024" => 0,
                    "calendario_eventos_3" => 0,
                    "calendario_eventos_4" => 0,
                    "calendario_eventos_5" => 0,
                    "calendario_eventos_6" => 0,
                    "calendario_eventos_7" => 0,
                    "calendario_eventos_8" => 0,
                    "calendario_eventos_9" => 0,
                    "calendario_eventos_10" => 0,
                    "calendario_eventos_11" => 0,
                    "calendario_eventos_12" => 0,
                    "calendario_eventos" => 0
                ];

                $arrBells = [
                    "calendario_timbres_2024" => 0,
                    "calendario_timbres_3" => 0,
                    "calendario_timbres_4" => 0,
                    "calendario_timbres_5" => 0,
                    "calendario_timbres_6" => 0,
                    "calendario_timbres_7" => 0,
                    "calendario_timbres_8" => 0,
                    "calendario_timbres_9" => 0,
                    "calendario_timbres_10" => 0,
                    "calendario_timbres_11" => 0,
                    "calendario_timbres_12" => 0,
                    "calendario_timbres" => 0
                ];

                foreach ($allEvents['jobs'] as $event) {
                    if ($school['rbd'] == $event['data']['data']['rbd']) {
                        $startDate = substr($event['data']['startDate'], 0, 10);
                        $endDate = substr($event['data']['endDate'], 0, 10);
                        
                        if ($event['data']['data']['event_type'] == 'Evento') {
                            // Search events per month
                            foreach ($arrEvents as $key => $value) {
                                $pos = strpos($key, '2024');
                                if ($pos !== false) {
                                    $arrEvents[$key]++;
                                }
                                else {
                                    $strDate = substr($key, 19);
                                    if ($strDate >= $startDate && $strDate <= $endDate) {
                                        $arrEvents[$key]++;
                                    }
                                }
                            }
                        }
                        elseif ($event['data']['data']['event_type'] == 'Timbre') {
                            // Search bells per month
                            foreach ($arrBells as $key => $value) {
                                $pos = strpos($key, '2024');
                                if ($pos !== false) {
                                    $arrBells[$key]++;
                                }
                                else {
                                    $strDate = substr($key, 19);
                                    if ($strDate >= $startDate && $strDate <= $endDate) {
                                        $arrBells[$key]++;
                                    }
                                }
                            }
                        }
                    }
                }
                
                // Weightings
                $arrWeightingsAdministrative = json_decode($school['ponderacion_administrativa_mensual'], true);
                $arrWeightingsHalls = json_decode($school['ponderacion_salas_mensual'], true);
                
                // Add Events and adminAula per month
                foreach ($arrEvents as $keyEvent => $event) {
                    $columnEvent = substr($keyEvent, 19);

                    // Event
                    $schools['data']['data'][$a][$keyEvent] = $event;
                    
                    // Bell
                    $keyBell = str_replace('calendario_eventos', 'calendario_timbres', $keyEvent);
                    $schools['data']['data'][$a][$keyBell] = $arrBells[$keyBell];
                    
                    // Admin-Aula
                    $percentCalendarEvents = ($event >= $arrWeightingsAdministrative[0]) ? 50 : $event / $arrWeightingsAdministrative[0] * 50;
                    $percentCalendarBells = ($arrBells[$keyBell] >= $arrWeightingsAdministrative[1]) ? 15 : $arrBells[$keyBell] / $arrWeightingsAdministrative[1] * 15;
                    
                    if (strlen($keyEvent) > 19) {
                        $percentAlerts = ($school['recursos_enviados_'.$columnEvent] >= $arrWeightingsAdministrative[2]) ? 20 : $school['recursos_enviados_'.$columnEvent] / $arrWeightingsAdministrative[2] * 20;
                        $percentMessagesAdminAula = ($school['mensajes_utp_'.$columnEvent] >= $arrWeightingsAdministrative[3]) ? 15 : $school['mensajes_utp_'.$columnEvent] / $arrWeightingsAdministrative[3] * 15;
                        $adminAula = $percentCalendarEvents + $percentCalendarBells + $percentAlerts + $percentMessagesAdminAula;
                        $schools['data']['data'][$a]['adminAula_'.$columnEvent] = number_format($adminAula, 1, ',', '.') . '%';
                    }
                    else {
                        $percentAlerts = ($school['recursos_enviados'] >= $arrWeightingsAdministrative[2]) ? 20 : $school['recursos_enviados'] / $arrWeightingsAdministrative[2] * 20;
                        $percentMessagesAdminAula = ($school['mensajes_utp'] >= $arrWeightingsAdministrative[3]) ? 15 : $school['mensajes_utp'] / $arrWeightingsAdministrative[3] * 15;
                        $adminAula = $percentCalendarEvents + $percentCalendarBells + $percentAlerts + $percentMessagesAdminAula;
                        $schools['data']['data'][$a]['adminAula'] = number_format($adminAula, 1, ',', '.') . '%';
                    }

                    // Aula-Admin
                    if (strlen($keyEvent) > 19) {
                        $percentMedicalAlerts = ($school['emergencias_medicas_'.$columnEvent] >= $arrWeightingsHalls[0]) ? 15 : $school['emergencias_medicas_'.$columnEvent] / $arrWeightingsHalls[0] * 15;
                        $percentBehavioralAlerts = ($school['emergencias_conductuales_'.$columnEvent] >= $arrWeightingsHalls[1]) ? 15 : $school['emergencias_conductuales_'.$columnEvent] / $arrWeightingsHalls[1] * 15;
                        $percentMessagesAulaAdmin = ($school['mensajes_connect_'.$columnEvent] >= $arrWeightingsHalls[2]) ? 70 : $school['mensajes_connect_'.$columnEvent] / $arrWeightingsHalls[2] * 70;
                        $aulaAdmin = $percentMedicalAlerts + $percentBehavioralAlerts + $percentMessagesAulaAdmin;
                        $schools['data']['data'][$a]['aulaAdmin_'.$columnEvent] = number_format($aulaAdmin, 1, ',', '.') . '%';
                    }
                    else {
                        $percentMedicalAlerts = ($school['emergencias_medicas'] >= $arrWeightingsHalls[0]) ? 15 : $school['emergencias_medicas'] / $arrWeightingsHalls[0] * 15;
                        $percentBehavioralAlerts = ($school['emergencias_conductuales'] >= $arrWeightingsHalls[1]) ? 15 : $school['emergencias_conductuales'] / $arrWeightingsHalls[1] * 15;
                        $percentMessagesAulaAdmin = ($school['mensajes_connect'] >= $arrWeightingsHalls[2]) ? 70 : $school['mensajes_connect'] / $arrWeightingsHalls[2] * 70;
                        $aulaAdmin = $percentMedicalAlerts + $percentBehavioralAlerts + $percentMessagesAulaAdmin;
                        $schools['data']['data'][$a]['aulaAdmin'] = number_format($aulaAdmin, 1, ',', '.') . '%';
                    }

                    // Column Use
                    $use = ($adminAula + $aulaAdmin) / 2;
                    if (strlen($keyEvent) > 19) {
                        $schools['data']['data'][$a]['uso_'.$columnEvent] = number_format($use, 1, ',', '.') . '%';
                    }
                    else {
                        $schools['data']['data'][$a]['uso'] = number_format($use, 1, ',', '.') . '%';
                    }
    
                    // Column Frecuency
                    if ($use > 75) {
                        $frecuency = 'USUARIO FRECUENTE';
                        $color = '#62b678';
                    }
                    elseif ($use > 50) {
                        $frecuency = 'USUARIO ALTO';
                        $color = '#2f77c1';
                    }
                    elseif ($use > 25) {
                        $frecuency = 'USUARIO REGULAR';
                        $color = '#f19732';
                    }
                    elseif ($use >= 0) {
                        $frecuency = 'SIN USO';
                        $color = '#e03634';
                    }
                    
                    if (strlen($keyEvent) > 19) {
                        if ($columnEvent == '2024' || $columnEvent <= $month) {
                            $schools['data']['data'][$a]['frecuencia_'.$columnEvent] = $frecuency;
                            $schools['data']['data'][$a]['color_'.$columnEvent] = $color;
                        }
                        else {
                            $schools['data']['data'][$a]['frecuencia_'.$columnEvent] = '-';
                            $schools['data']['data'][$a]['color_'.$columnEvent] = '#000000';
                        }
                    }
                    else {
                        $schools['data']['data'][$a]['frecuencia'] = $frecuency;
                        $schools['data']['data'][$a]['color'] = $color;
                    }
                }
            }

            require_once 'views/statistics.php';
        }
    }
?>