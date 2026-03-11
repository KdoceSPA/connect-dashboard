<?php
    function renderSchoolTabContentFilter($idx, $key, $eventos, $timbres, $recursos, $mensajes_utp, $em_medicas, $em_conductuales, $mensajes_connect, $active = false) {
        $activeClass = $active ? 'active show' : '';
        return "<div class='tab-pane fade {$activeClass}' id='tab-{$key}-{$idx}' role='tabpanel'>
            <table class='table'>
                <thead>
                    <tr><th colspan='5' class='text-center' style='background-color: #fff; color: #195ca6; height: 20px; padding: 5px;'>Administración</th></tr>
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
                        <td class='text-center' style='background-color: #fff;'>{$eventos}</td>
                        <td class='text-center' style='background-color: #fff;'>{$timbres}</td>
                        <td class='text-center' style='background-color: #fff;'>{$recursos}</td>
                        <td class='text-center' style='background-color: #fff;'>{$mensajes_utp}</td>
                    </tr>
                </tbody>
            </table>
            <table class='table'>
                <thead>
                    <tr><th colspan='4' class='text-center' style='background-color: #fff; color: #195ca6; height: 20px; padding: 5px;'>Salas</th></tr>
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
                        <td class='text-center' style='background-color: #fff;'>{$em_medicas}</td>
                        <td class='text-center' style='background-color: #fff;'>{$em_conductuales}</td>
                        <td class='text-center' style='background-color: #fff;'>{$mensajes_connect}</td>
                    </tr>
                </tbody>
            </table>
        </div>";
    }

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
            $schools = $this->model->listExtended($this->endpoint, '1', '1', $token);
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

                // Events and bells (total + per year/month)
                $countEvents = 0;
                $countBells = 0;
                $arrEvents = [
                    'calendario_eventos_2024' => 0, 'calendario_eventos_2025' => 0,
                    'calendario_eventos_3' => 0, 'calendario_eventos_4' => 0,
                    'calendario_eventos_5' => 0, 'calendario_eventos_6' => 0,
                    'calendario_eventos_7' => 0, 'calendario_eventos_8' => 0,
                    'calendario_eventos_9' => 0, 'calendario_eventos_10' => 0,
                    'calendario_eventos_11' => 0, 'calendario_eventos_12' => 0,
                ];
                $arrBells = [
                    'calendario_timbres_2024' => 0, 'calendario_timbres_2025' => 0,
                    'calendario_timbres_3' => 0, 'calendario_timbres_4' => 0,
                    'calendario_timbres_5' => 0, 'calendario_timbres_6' => 0,
                    'calendario_timbres_7' => 0, 'calendario_timbres_8' => 0,
                    'calendario_timbres_9' => 0, 'calendario_timbres_10' => 0,
                    'calendario_timbres_11' => 0, 'calendario_timbres_12' => 0,
                ];
                foreach ($allEvents['jobs'] as $event) {
                    if ($school['rbd'] == $event['data']['data']['rbd']) {
                        $startDate = substr($event['data']['startDate'], 0, 10);
                        $endDate = substr($event['data']['endDate'], 0, 10);
                        $startYear = substr($startDate, 0, 4);
                        $startMonth = intval(substr($startDate, 5, 2));
                        if ($event['data']['data']['event_type'] == 'Evento') {
                            $countEvents++;
                            if ($startYear == '2024') $arrEvents['calendario_eventos_2024']++;
                            if ($startYear == '2025') $arrEvents['calendario_eventos_2025']++;
                            if ($startYear == '2026' && isset($arrEvents['calendario_eventos_' . $startMonth])) $arrEvents['calendario_eventos_' . $startMonth]++;
                        }
                        elseif ($event['data']['data']['event_type'] == 'Timbre') {
                            $countBells++;
                            if ($startYear == '2024') $arrBells['calendario_timbres_2024']++;
                            if ($startYear == '2025') $arrBells['calendario_timbres_2025']++;
                            if ($startYear == '2026' && isset($arrBells['calendario_timbres_' . $startMonth])) $arrBells['calendario_timbres_' . $startMonth]++;
                        }
                    }
                }
                $schools['data']['data'][$a]['eventos'] = $countEvents;
                $schools['data']['data'][$a]['timbres'] = $countBells;
                foreach ($arrEvents as $key => $val) { $schools['data']['data'][$a][$key] = $val; }
                foreach ($arrBells as $key => $val) { $schools['data']['data'][$a][$key] = $val; }
                
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
                            <td id='tblDetails" . ($a + 1) . "' colspan='8' style='background-color: #f2f2f2; display: none; padding: 15px;'>
                                <ul class='nav nav-tabs' id='tabs-school-" . ($a + 1) . "' role='tablist'>
                                    <li class='nav-item'><a class='nav-link active' data-toggle='pill' href='#tab-2024-" . ($a + 1) . "' role='tab'>2024</a></li>
                                    <li class='nav-item'><a class='nav-link' data-toggle='pill' href='#tab-2025-" . ($a + 1) . "' role='tab'>2025</a></li>
                                    <li class='nav-item'><a class='nav-link' data-toggle='pill' href='#tab-3-" . ($a + 1) . "' role='tab'>Mar</a></li>
                                    <li class='nav-item'><a class='nav-link' data-toggle='pill' href='#tab-4-" . ($a + 1) . "' role='tab'>Abr</a></li>
                                    <li class='nav-item'><a class='nav-link' data-toggle='pill' href='#tab-5-" . ($a + 1) . "' role='tab'>May</a></li>
                                    <li class='nav-item'><a class='nav-link' data-toggle='pill' href='#tab-6-" . ($a + 1) . "' role='tab'>Jun</a></li>
                                    <li class='nav-item'><a class='nav-link' data-toggle='pill' href='#tab-7-" . ($a + 1) . "' role='tab'>Jul</a></li>
                                    <li class='nav-item'><a class='nav-link' data-toggle='pill' href='#tab-8-" . ($a + 1) . "' role='tab'>Ago</a></li>
                                    <li class='nav-item'><a class='nav-link' data-toggle='pill' href='#tab-9-" . ($a + 1) . "' role='tab'>Sep</a></li>
                                    <li class='nav-item'><a class='nav-link' data-toggle='pill' href='#tab-10-" . ($a + 1) . "' role='tab'>Oct</a></li>
                                    <li class='nav-item'><a class='nav-link' data-toggle='pill' href='#tab-11-" . ($a + 1) . "' role='tab'>Nov</a></li>
                                    <li class='nav-item'><a class='nav-link' data-toggle='pill' href='#tab-12-" . ($a + 1) . "' role='tab'>Dic</a></li>
                                </ul>
                                <div class='tab-content' style='background-color: #fff; padding: 10px;'>" .
                                    renderSchoolTabContentFilter($a + 1, '2024', $school['calendario_eventos_2024'], $school['calendario_timbres_2024'], $school['recursos_enviados_2024'], $school['mensajes_utp_2024'], $school['emergencias_medicas_2024'], $school['emergencias_conductuales_2024'], $school['mensajes_connect_2024'], true) .
                                    renderSchoolTabContentFilter($a + 1, '2025', $school['calendario_eventos_2025'], $school['calendario_timbres_2025'], $school['recursos_enviados_2025'], $school['mensajes_utp_2025'], $school['emergencias_medicas_2025'], $school['emergencias_conductuales_2025'], $school['mensajes_connect_2025']) .
                                    renderSchoolTabContentFilter($a + 1, '3', $school['calendario_eventos_3'], $school['calendario_timbres_3'], $school['recursos_enviados_3'], $school['mensajes_utp_3'], $school['emergencias_medicas_3'], $school['emergencias_conductuales_3'], $school['mensajes_connect_3']) .
                                    renderSchoolTabContentFilter($a + 1, '4', $school['calendario_eventos_4'], $school['calendario_timbres_4'], $school['recursos_enviados_4'], $school['mensajes_utp_4'], $school['emergencias_medicas_4'], $school['emergencias_conductuales_4'], $school['mensajes_connect_4']) .
                                    renderSchoolTabContentFilter($a + 1, '5', $school['calendario_eventos_5'], $school['calendario_timbres_5'], $school['recursos_enviados_5'], $school['mensajes_utp_5'], $school['emergencias_medicas_5'], $school['emergencias_conductuales_5'], $school['mensajes_connect_5']) .
                                    renderSchoolTabContentFilter($a + 1, '6', $school['calendario_eventos_6'], $school['calendario_timbres_6'], $school['recursos_enviados_6'], $school['mensajes_utp_6'], $school['emergencias_medicas_6'], $school['emergencias_conductuales_6'], $school['mensajes_connect_6']) .
                                    renderSchoolTabContentFilter($a + 1, '7', $school['calendario_eventos_7'], $school['calendario_timbres_7'], $school['recursos_enviados_7'], $school['mensajes_utp_7'], $school['emergencias_medicas_7'], $school['emergencias_conductuales_7'], $school['mensajes_connect_7']) .
                                    renderSchoolTabContentFilter($a + 1, '8', $school['calendario_eventos_8'], $school['calendario_timbres_8'], $school['recursos_enviados_8'], $school['mensajes_utp_8'], $school['emergencias_medicas_8'], $school['emergencias_conductuales_8'], $school['mensajes_connect_8']) .
                                    renderSchoolTabContentFilter($a + 1, '9', $school['calendario_eventos_9'], $school['calendario_timbres_9'], $school['recursos_enviados_9'], $school['mensajes_utp_9'], $school['emergencias_medicas_9'], $school['emergencias_conductuales_9'], $school['mensajes_connect_9']) .
                                    renderSchoolTabContentFilter($a + 1, '10', $school['calendario_eventos_10'], $school['calendario_timbres_10'], $school['recursos_enviados_10'], $school['mensajes_utp_10'], $school['emergencias_medicas_10'], $school['emergencias_conductuales_10'], $school['mensajes_connect_10']) .
                                    renderSchoolTabContentFilter($a + 1, '11', $school['calendario_eventos_11'], $school['calendario_timbres_11'], $school['recursos_enviados_11'], $school['mensajes_utp_11'], $school['emergencias_medicas_11'], $school['emergencias_conductuales_11'], $school['mensajes_connect_11']) .
                                    renderSchoolTabContentFilter($a + 1, '12', $school['calendario_eventos_12'], $school['calendario_timbres_12'], $school['recursos_enviados_12'], $school['mensajes_utp_12'], $school['emergencias_medicas_12'], $school['emergencias_conductuales_12'], $school['mensajes_connect_12']) .
                                "</div>
                            </td>
                        </tr>";
            }
            
            echo $html;
        }
    }
?>