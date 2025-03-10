<?php
    function customUcwords($text) {
        $exceptions = ['y', 'de', 'a'];
        $text = mb_strtolower($text, 'UTF-8');
        $words = preg_split('/\s+/', $text);
        foreach ($words as &$word) {
            if (!in_array($word, $exceptions)) {
                $word = ucfirst($word);
            }
        }
        return implode(' ', $words);
    }
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="color: #195ca6;">Estadísticas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Estadísticas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <input id="token" type="hidden" value="<?php echo $token; ?>">

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="card col-md-12">
                    <div class="card-header">
                        <h5 style="color: #195ca6;"><i class="fas fa-university"></i> Colegios</h5>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="color: #195ca6;">#</th>
                                        <th class="text-center" style="color: #195ca6;">Nombre</th>
                                        <th class="text-center" style="color: #195ca6;">2024</th>
                                        <th class="text-center" style="color: #195ca6;">Mar</th>
                                        <th class="text-center" style="color: #195ca6;">Abr</th>
                                        <th class="text-center" style="color: #195ca6;">May</th>
                                        <th class="text-center" style="color: #195ca6;">Jun</th>
                                        <th class="text-center" style="color: #195ca6;">Jul</th>
                                        <th class="text-center" style="color: #195ca6;">Ago</th>
                                        <th class="text-center" style="color: #195ca6;">Sep</th>
                                        <th class="text-center" style="color: #195ca6;">Oct</th>
                                        <th class="text-center" style="color: #195ca6;">Nov</th>
                                        <th class="text-center" style="color: #195ca6;">Dic</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($schools['data']['data'] as $a => $school) {
                                            echo "<tr style=\"cursor: pointer;\">
                                                    <td style=\"color: #195ca6;\"><b>" . ($a + 1) . "</b></td>
                                                    <td>" . $school['nombre'] . "</td>
                                                    <td class=\"text-center\" style=\"color: " . $school['color_2024'] . ";\" onclick=\"showDetailsMonth('" . customUcwords($school['nombre']) . " - 2024', '" . $school['calendario_eventos_2024'] . "', '" . $school['calendario_timbres_2024'] . "', '" . $school['recursos_enviados_2024'] . "', '" . $school['mensajes_utp_2024'] . "', '" . $school['emergencias_medicas_2024'] . "', '" . $school['emergencias_conductuales_2024'] . "', '" . $school['mensajes_connect_2024'] . "')\"><b>" . $school['frecuencia_2024'] . "</b></td>
                                                    <td class=\"text-center\" style=\"color: " . $school['color_3'] . ";\" onclick=\"showDetailsMonth('" . customUcwords($school['nombre']) . " - Marzo', '" . $school['calendario_eventos_3'] . "', '" . $school['calendario_timbres_3'] . "', '" . $school['recursos_enviados_3'] . "', '" . $school['mensajes_utp_3'] . "', '" . $school['emergencias_medicas_3'] . "', '" . $school['emergencias_conductuales_3'] . "', '" . $school['mensajes_connect_3'] . "')\"><b>" . $school['frecuencia_3'] . "</b></td>
                                                    <td class=\"text-center\" style=\"color: " . $school['color_4'] . ";\" onclick=\"showDetailsMonth('" . customUcwords($school['nombre']) . " - Abril', '" . $school['calendario_eventos_4'] . "', '" . $school['calendario_timbres_4'] . "', '" . $school['recursos_enviados_4'] . "', '" . $school['mensajes_utp_4'] . "', '" . $school['emergencias_medicas_4'] . "', '" . $school['emergencias_conductuales_4'] . "', '" . $school['mensajes_connect_4'] . "')\"><b>" . $school['frecuencia_4'] . "</b></td>
                                                    <td class=\"text-center\" style=\"color: " . $school['color_5'] . ";\" onclick=\"showDetailsMonth('" . customUcwords($school['nombre']) . " - Mayo', '" . $school['calendario_eventos_5'] . "', '" . $school['calendario_timbres_5'] . "', '" . $school['recursos_enviados_5'] . "', '" . $school['mensajes_utp_5'] . "', '" . $school['emergencias_medicas_5'] . "', '" . $school['emergencias_conductuales_5'] . "', '" . $school['mensajes_connect_5'] . "')\"><b>" . $school['frecuencia_5'] . "</b></td>
                                                    <td class=\"text-center\" style=\"color: " . $school['color_6'] . ";\" onclick=\"showDetailsMonth('" . customUcwords($school['nombre']) . " - Junio', '" . $school['calendario_eventos_6'] . "', '" . $school['calendario_timbres_6'] . "', '" . $school['recursos_enviados_6'] . "', '" . $school['mensajes_utp_6'] . "', '" . $school['emergencias_medicas_6'] . "', '" . $school['emergencias_conductuales_6'] . "', '" . $school['mensajes_connect_6'] . "')\"><b>" . $school['frecuencia_6'] . "</b></td>
                                                    <td class=\"text-center\" style=\"color: " . $school['color_7'] . ";\" onclick=\"showDetailsMonth('" . customUcwords($school['nombre']) . " - Julio', '" . $school['calendario_eventos_7'] . "', '" . $school['calendario_timbres_7'] . "', '" . $school['recursos_enviados_7'] . "', '" . $school['mensajes_utp_7'] . "', '" . $school['emergencias_medicas_7'] . "', '" . $school['emergencias_conductuales_7'] . "', '" . $school['mensajes_connect_7'] . "')\"><b>" . $school['frecuencia_7'] . "</b></td>
                                                    <td class=\"text-center\" style=\"color: " . $school['color_8'] . ";\" onclick=\"showDetailsMonth('" . customUcwords($school['nombre']) . " - Agosto', '" . $school['calendario_eventos_8'] . "', '" . $school['calendario_timbres_8'] . "', '" . $school['recursos_enviados_8'] . "', '" . $school['mensajes_utp_8'] . "', '" . $school['emergencias_medicas_8'] . "', '" . $school['emergencias_conductuales_8'] . "', '" . $school['mensajes_connect_8'] . "')\"><b>" . $school['frecuencia_8'] . "</b></td>
                                                    <td class=\"text-center\" style=\"color: " . $school['color_9'] . ";\" onclick=\"showDetailsMonth('" . customUcwords($school['nombre']) . " - Septiembre', '" . $school['calendario_eventos_9'] . "', '" . $school['calendario_timbres_9'] . "', '" . $school['recursos_enviados_9'] . "', '" . $school['mensajes_utp_9'] . "', '" . $school['emergencias_medicas_9'] . "', '" . $school['emergencias_conductuales_9'] . "', '" . $school['mensajes_connect_9'] . "')\"><b>" . $school['frecuencia_9'] . "</b></td>
                                                    <td class=\"text-center\" style=\"color: " . $school['color_10'] . ";\" onclick=\"showDetailsMonth('" . customUcwords($school['nombre']) . " - Octubre', '" . $school['calendario_eventos_10'] . "', '" . $school['calendario_timbres_10'] . "', '" . $school['recursos_enviados_10'] . "', '" . $school['mensajes_utp_10'] . "', '" . $school['emergencias_medicas_10'] . "', '" . $school['emergencias_conductuales_10'] . "', '" . $school['mensajes_connect_10'] . "')\"><b>" . $school['frecuencia_10'] . "</b></td>
                                                    <td class=\"text-center\" style=\"color: " . $school['color_11'] . ";\" onclick=\"showDetailsMonth('" . customUcwords($school['nombre']) . " - Noviembre', '" . $school['calendario_eventos_11'] . "', '" . $school['calendario_timbres_11'] . "', '" . $school['recursos_enviados_11'] . "', '" . $school['mensajes_utp_11'] . "', '" . $school['emergencias_medicas_11'] . "', '" . $school['emergencias_conductuales_11'] . "', '" . $school['mensajes_connect_11'] . "')\"><b>" . $school['frecuencia_11'] . "</b></td>
                                                    <td class=\"text-center\" style=\"color: " . $school['color_12'] . ";\" onclick=\"showDetailsMonth('" . customUcwords($school['nombre']) . " - Diciembre', '" . $school['calendario_eventos_12'] . "', '" . $school['calendario_timbres_12'] . "', '" . $school['recursos_enviados_12'] . "', '" . $school['mensajes_utp_12'] . "', '" . $school['emergencias_medicas_12'] . "', '" . $school['emergencias_conductuales_12'] . "', '" . $school['mensajes_connect_12'] . "')\"><b>" . $school['frecuencia_12'] . "</b></td>
                                                </tr>";

                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!--Modal Details-->
<div class="modal fade-scale" id="modalDetails" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: #195ca6;"><i class="fa fa-edit"></i> <span id="spanTitle"></span></h5>
            </div>
            <div class="modal-body modalBody" style="background-color: #f9f9f9; padding: 20px;">
                <div class="table-responsive">
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
                                <td id="valCalendarEvents" class='text-center' style='background-color: #fff;'></td>
                                <td id="valCalendarBells" class='text-center' style='background-color: #fff;'></td>
                                <td id="valAlerts" class='text-center' style='background-color: #fff;'></td>
                                <td id="valMessagesAdministrative" class='text-center' style='background-color: #fff;'></td>
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
                                <td id="valMedicalAlerts" class='text-center' style='background-color: #fff;'></td>
                                <td id="valBehavioralAlerts" class='text-center' style='background-color: #fff;'></td>
                                <td id="valMessagesHalls" class='text-center' style='background-color: #fff;'></td>
                            </tr>
                        <tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btnButtonCloseModal" data-dismiss="modal"><i class="fas fa-sign-out-alt" aria-hidden="true"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>