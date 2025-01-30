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
            $allUsers = $this->model->getAllUsers($this->endpoint, $token);
            $allEvents = $this->model->getAllEvents($this->endpoint, $token);
            $weightings = $this->model->getWeightings($this->endpoint, $token);

            // Score tables
            $totalcountActivated = 0;
            foreach ($schools['data']['data'] as $a => $school) {
                // Devices activated
                $countActivated = 0;
                foreach ($allUsers['usersRoom'] as $user) {
                    if ($school['rbd'] == $user['room'] && $user['role'] == 'touchclass') {
                        $totalcountActivated++;
                        $countActivated++;
                    }
                }
                $schools['data']['data'][$a]['activados'] = $countActivated;
                $schools['data']['data'][$a]['porcentaje_activados'] = number_format($countActivated / $school['dispositivos'] * 100, 0, ',', '.') . '%';

                // Events and bells
                $countEvents = 0;
                $countBells = 0;
                foreach ($allEvents['jobs'] as $event) {
                    if ($school['rbd'] == $event['data']['data']['rbd']) {
                        if ($event['data']['data']['event_type'] == 'Evento') {
                            $countEvents++;
                        }
                        elseif ($event['data']['data']['event_type'] == 'Timbre') {
                            $countBells++;
                        }
                    }
                }
                $schools['data']['data'][$a]['eventos'] = $countEvents;
                $schools['data']['data'][$a]['timbres'] = $countBells;
                
                // Weightings
                $arrWeightingsAdministrative = json_decode($school['ponderacion_administrativa'], true);
                $arrWeightingsHalls = json_decode($school['ponderacion_salas'], true);

                // % Table administrative and Admin-Aula
                $percentCalendarEvents = ($countEvents >= $arrWeightingsAdministrative[0]) ? 50 : $countEvents / $arrWeightingsAdministrative[0] * 50;
                $percentCalendarBells = ($countBells >= $arrWeightingsAdministrative[1]) ? 15 : $countBells / $arrWeightingsAdministrative[1] * 15;
                $percentAlerts = ($school['recursos_enviados'] >= $arrWeightingsAdministrative[2]) ? 20 : $school['recursos_enviados'] / $arrWeightingsAdministrative[2] * 20;
                $percentMessagesAdminAula = ($school['mensajes_utp'] >= $arrWeightingsAdministrative[3]) ? 15 : $school['mensajes_utp'] / $arrWeightingsAdministrative[3] * 15;
                $adminAula = $percentCalendarEvents + $percentCalendarBells + $percentAlerts + $percentMessagesAdminAula;
                $schools['data']['data'][$a]['adminAula'] = number_format($adminAula, 1, ',', '.') . '%';
                
                // % Table halls and Aula-Admin
                $percentMedicalAlerts = ($school['emergencias_medicas'] >= $arrWeightingsHalls[0]) ? 15 : $school['emergencias_medicas'] / $arrWeightingsHalls[0] * 15;
                $percentBehavioralAlerts = ($school['emergencias_conductuales'] >= $arrWeightingsHalls[1]) ? 15 : $school['emergencias_conductuales'] / $arrWeightingsHalls[1] * 15;
                $percentMessagesAulaAdmin = ($school['mensajes_connect'] >= $arrWeightingsHalls[2]) ? 70 : $school['mensajes_connect'] / $arrWeightingsHalls[2] * 70;
                $aulaAdmin = $percentMedicalAlerts + $percentBehavioralAlerts + $percentMessagesAulaAdmin;
                $schools['data']['data'][$a]['aulaAdmin'] = number_format($aulaAdmin, 1, ',', '.') . '%';

                // Column Use
                $use = ($adminAula + $aulaAdmin) / 2;
                $schools['data']['data'][$a]['uso'] = number_format($use, 1, ',', '.') . '%';

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
                $schools['data']['data'][$a]['frecuencia'] = $frecuency;
                $schools['data']['data'][$a]['color'] = $color;
            }

            $totalRbds = 0;
            $totalGroups = 0;
            $totalDevices = 0;
            foreach ($schools['data']['data'] as $school) {
                $totalRbds++;
                $totalGroups += $school['grupos'];
                $totalDevices += $school['dispositivos'];
            }

            $percentTotalCountActivated = ($totalDevices == 0) ? '0%' : number_format($totalcountActivated / $totalDevices * 100, 1, ',', '.');
            require_once 'views/home/inicio.php';
        }
    }
?>