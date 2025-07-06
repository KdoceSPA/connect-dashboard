<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once dirname(__FILE__) . '/../models/SchoolModel.php';

    $controller = new SchoolController();
    
    // Redirect functions
    if (!isset($_GET['f'])) {
        $controller->list();
    }
    else {
        if ($_GET['f'] == 'updateWeightings') {
            $controller->updateWeightings($_POST['arrFields']);
        }
        elseif ($_GET['f'] == 'processFilter') {
            $controller->processFilter($_POST);
        }
    }
    
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
                if (gettype($school['ponderacion_administrativa']) == 'array') {
                    $arrWeightingsAdministrative = $school['ponderacion_administrativa'];
                    $arrWeightingsHalls = $school['ponderacion_salas'];
                }
                else {
                    $arrWeightingsAdministrative = json_decode($school['ponderacion_administrativa'], true);
                    $arrWeightingsHalls = json_decode($school['ponderacion_salas'], true);
                }

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

            $percentTotalCountActivated = ($totalDevices == 0) ? '0%' : (($totalcountActivated / $totalDevices * 100 > 0 && $totalcountActivated / $totalDevices * 100 < 100) ? number_format($totalcountActivated / $totalDevices * 100, 1, ',', '.') : number_format($totalcountActivated / $totalDevices * 100, 0, ',', '.'));
            $totalcountDeactivated = $totalDevices - $totalcountActivated;
            $percentTotalCountDeactivated = ($totalDevices == 0) ? '0%' : (($totalcountDeactivated / $totalDevices * 100 > 0 && $totalcountDeactivated / $totalDevices * 100 < 100) ? number_format($totalcountDeactivated / $totalDevices * 100, 1, ',', '.') : number_format($totalcountDeactivated / $totalDevices * 100, 0, ',', '.'));
            require_once 'views/home/home.php';
        }

        public function updateWeightings() {
            $response = json_encode($this->model->updateWeightings($this->endpoint, $_POST['token'], $_POST['arrFields']));
            echo $response;
        }

        public function processFilter($data) {
            $token = $data['token'];
            $type = $data['arrFields']['type'];
            if ($type == 2) {
                $arrMonths = json_decode($data['arrFields']['val']);
            }
            $schools = $this->model->listIterationsFilter($this->endpoint, '1', '1', $token, $data['arrFields']);
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
                            // Filter by day
                            if ($type == 1) {
                                $dateToCheck = new DateTime($data['arrFields']['val']);
                                $startDate = new DateTime(substr($event['data']['startDate'], 0, 10));
                                $endDate = new DateTime(substr($event['data']['endDate'], 0, 10));
                                if ($dateToCheck >= $startDate && $dateToCheck <= $endDate) {
                                    $countEvents++;
                                }
                            }
                            // Filter by months
                            else if ($type == 2) {
                                $startDateMonth = substr($event['data']['startDate'], 5, 2);
                                $startDateYear = substr($event['data']['startDate'], 0, 4);
                                $endDateMonth = substr($event['data']['endDate'], 5, 2);
                                $endDateYear = substr($event['data']['endDate'], 0, 4);
                                $yearNow = date('Y');
                                foreach ($arrMonths as $month) {
                                    if ($month >= $startDateMonth && $month <= $endDateMonth && $startDateYear == $yearNow && $endDateYear == $yearNow) {
                                        $countEvents++;
                                    }
                                }
                            }
                            // Filter by year
                            else if ($type == 3) {

                            }
                        }
                        elseif ($event['data']['data']['event_type'] == 'Timbre') {
                            // Filter by day
                            if ($type == 1) {
                                $dateToCheck = new DateTime($data['arrFields']['val']);
                                $startDate = new DateTime(substr($event['data']['startDate'], 0, 10));
                                $endDate = new DateTime(substr($event['data']['endDate'], 0, 10));
                                if ($dateToCheck >= $startDate && $dateToCheck <= $endDate) {
                                    $countBells++;
                                }
                            }
                            // Filter by months
                            else if ($type == 2) {
                                $startDateMonth = substr($event['data']['startDate'], 5, 2);
                                $startDateYear = substr($event['data']['startDate'], 0, 4);
                                $endDateMonth = substr($event['data']['endDate'], 5, 2);
                                $endDateYear = substr($event['data']['endDate'], 0, 4);
                                $yearNow = date('Y');
                                foreach ($arrMonths as $month) {
                                    if ($month >= $startDateMonth && $month <= $endDateMonth && $startDateYear == $yearNow && $endDateYear == $yearNow) {
                                        $countBells++;
                                    }
                                }
                            }
                            // Filter by year
                            else if ($type == 3) {

                            }
                        }
                    }
                }
                $schools['data']['data'][$a]['eventos'] = $countEvents;
                $schools['data']['data'][$a]['timbres'] = $countBells;
                
                // Weightings
                if (gettype($school['ponderacion_administrativa']) == 'array') {
                    $arrWeightingsAdministrative = $school['ponderacion_administrativa'];
                    $arrWeightingsHalls = $school['ponderacion_salas'];
                }
                else {
                    $arrWeightingsAdministrative = json_decode($school['ponderacion_administrativa'], true);
                    $arrWeightingsHalls = json_decode($school['ponderacion_salas'], true);
                }

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

            $html = '';
            foreach ($schools['data']['data'] as $a => $school) {
                $html .= "<tr onclick='showDetails(" . ($a + 1) . ")' style='cursor: pointer;'>
                            <td style='color: #195ca6;'><b>" . ($a + 1) . "</b></td>
                            <td>" . $school['rbd'] . " - " . $school['nombre'] . "</td>
                            <td class='text-center'>" . $school['grupos'] . "</td>
                            <td class='text-center'>
                                <div class='progress progress-xs'>
                                    <div class='progress-bar bg-success' style='width: " . $school['porcentaje_activados'] . ";'></div>
                                </div> " . $school['activados'] . ' de ' . $school['dispositivos'] . "
                            </td>
                            <td class='text-center'>" . $school['adminAula'] . "</td>
                            <td class='text-center'>" . $school['aulaAdmin'] . "</td>
                            <td class='text-center'>" . $school['uso'] . "</td>
                            <td class='text-center' style='color: " . $school['color'] . ";'><b>" . $school['frecuencia'] . "</b></td>
                        </tr>
                        <tr>
                            <td id='tblDetails" . ($a + 1) . "' colspan='8' style='background-color: #f2f2f2; display: none;'>
                                <table class='table'>
                                    <thead>
                                        <tr>
                                            <th colspan='5' class='text-center' style='background-color: #fff; color: #195ca6; height: 20px; padding: 5px;'>Administración</th>
                                        </tr>
                                        <tr>
                                            <th class='text-center' style='background-color: #195ca6; color: #fff;'>#</th>
                                            <th class='text-center' style='background-color: #195ca6; color: #fff;'>Calendario de Eventos<br>(50%)</th>
                                            <th class='text-center' style='background-color: #195ca6; color: #fff;'>Calendario de Timbres<br>(15%)</th>
                                            <th class='text-center' style='background-color: #195ca6; color: #fff;'>Alertas<br>(20%)</th>
                                            <th class='text-center' style='background-color: #195ca6; color: #fff;'>Mensajeria en Tiempo Real<br>(15%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class='text-center' style='background-color: #fff;'>Interacciones</td>
                                            <td class='text-center' style='background-color: #fff;'>" . $school['eventos'] . "</td>
                                            <td class='text-center' style='background-color: #fff;'>" . $school['timbres'] . "</td>
                                            <td class='text-center' style='background-color: #fff;'>" . $school['recursos_enviados'] . "</td>
                                            <td class='text-center' style='background-color: #fff;'>" . $school['mensajes_utp'] . "</td>
                                        </tr>
                                    <tbody>
                                </table>

                                <table class='table'>
                                    <thead>
                                        <tr>
                                            <th colspan='4' class='text-center' style='background-color: #fff; color: #195ca6; height: 20px; padding: 5px;'>Salas</th>
                                        </tr>
                                        <tr>
                                            <th class='text-center' style='background-color: #195ca6; color: #fff;'>#</th>
                                            <th class='text-center' style='background-color: #195ca6; color: #fff;'>Alerta Medica<br>(15%)</th>
                                            <th class='text-center' style='background-color: #195ca6; color: #fff;'>Alerta Conductual<br>(15%)</th>
                                            <th class='text-center' style='background-color: #195ca6; color: #fff;'>Mensaje Instantaneo<br>(70%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class='text-center' style='background-color: #fff;'>Interacciones</td>
                                            <td class='text-center' style='background-color: #fff;'>" . $school['emergencias_medicas'] . "</td>
                                            <td class='text-center' style='background-color: #fff;'>" . $school['emergencias_conductuales'] . "</td>
                                            <td class='text-center' style='background-color: #fff;'>" . $school['mensajes_connect'] . "</td>
                                        </tr>
                                    <tbody>
                                </table>
                            </td>
                        </tr>";
            }
            
            echo $html;
        }
    }
?>