<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="color: #195ca6;">Estadisticas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Estadisticas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <input id="token" type="hidden" value="<?php echo $token; ?>">

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $totalRbds; ?></h3>
                            <p>Colegios</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <a href="#" class="small-box-footer"></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $percentTotalCountActivated; ?><sup style="font-size: 20px">%</sup></h3>
                            <p>Activos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <a href="#" class="small-box-footer"></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo $totalGroups; ?></h3>
                            <p>Grupos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <a href="#" class="small-box-footer"></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $totalDevices; ?></h3>
                            <p>Dispositivos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tv"></i>
                        </div>
                        <a href="#" class="small-box-footer"></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="card col-md-12">
                    <div class="card-header">
                        <button class="btnButtonModal" style="float: right;" onclick="showWeightings()"><i class="fas fa-calculator"></i> Ponderaciones</button>
                        <h5 style="color: #195ca6;"><i class="fas fa-university"></i> Colegios</h5>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="color: #195ca6; width: 10px">#</th>
                                        <th style="color: #195ca6;">Nombre</th>
                                        <th class="text-center" style="color: #195ca6;">Grupos</th>
                                        <th class="text-center" style="color: #195ca6;">Dispositivos</th>
                                        <th class="text-center" style="color: #195ca6;">Admin-Aula</th>
                                        <th class="text-center" style="color: #195ca6;">Aula-Admin</th>
                                        <th class="text-center" style="color: #195ca6;">Uso</th>
                                        <th class="text-center" style="color: #195ca6;">Frecuencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($schools['data']['data'] as $a => $school) {
                                            echo "<tr onclick='showDetails(" . ($a + 1) . ")' style='cursor: pointer;'>
                                                    <td style='color: #195ca6;'><b>" . ($a + 1) . "</b></td>
                                                    <td>" . $school['nombre'] . "</td>
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

<!--Modal Weightings-->
<div class="modal fade-scale" id="modalWeightings" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: #195ca6;"><i class="fa fa-calculator"></i> Ponderaciones</h5>
            </div>
            <div class="modal-body modalBody" style="background-color: #f9f9f9; height: 470px; padding: 20px;">
                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Administrativa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Salas</a>
                    </li>
                </ul>
                <div class="tab-content" id="custom-content-below-tabContent">
                    <div class="tab-pane fade active show" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                        <div id="tabAdministrative" class="card col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="6" class="rowHead" style="height: 20px; padding: 5px;">Administración - Aulas</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #195ca6; color: #fff; text-align:center;">Mínimo</th>
                                            <th style="background-color: #195ca6; color: #fff; text-align:center;">Máximo</th>
                                            <th style="background-color: #195ca6; color: #fff; text-align:center;">Calendario<br>de Eventos</th>
                                            <th style="background-color: #195ca6; color: #fff; text-align:center;">Calendario<br>de Timbres</th>
                                            <th style="background-color: #195ca6; color: #fff; text-align:center;">Alertas</th>
                                            <th style="background-color: #195ca6; color: #fff; text-align:center;">Mensajeria en Tiempo Real</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($weightings['data']['rowsAdministrative'] as $weighting) {
                                                echo "<tr>
                                                        <td style='height: 20px; padding: 7px'><input name='txtIdAdministrative' type='hidden' value='" . $weighting['id'] . "'><input name='txtMinAdministrative' type='text' class='form-control text-center' value='" . $weighting['min'] . "'></td>
                                                        <td style='height: 20px; padding: 7px'><input name='txtMaxAdministrative' type='text' class='form-control text-center' value='" . $weighting['max'] . "'></td>
                                                        <td style='height: 20px; padding: 7px'><input name='txtCalendarEventsAdministrative' type='text' class='form-control text-center' value='" . $weighting['calendar_events'] . "'></td>
                                                        <td style='height: 20px; padding: 7px'><input name='txtCalendarBellsAdministrative' type='text' class='form-control text-center' value='" . $weighting['calendar_bells'] . "'></td>
                                                        <td style='height: 20px; padding: 7px'><input name='txtAlertsAdministrative' type='text' class='form-control text-center' value='" . $weighting['alerts'] . "'></td>
                                                        <td style='height: 20px; padding: 7px'><input name='txtMessagesAdministrative' type='text' class='form-control text-center' value='" . $weighting['messages'] . "'></td>
                                                    </tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                        <div id="tabHalls" class="card col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="5" class="rowHead" style="height: 20px; padding: 5px;">Aulas - Administración</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #195ca6; color: #fff; text-align:center;">Mínimo</th>
                                            <th style="background-color: #195ca6; color: #fff; text-align:center;">Máximo</th>
                                            <th style="background-color: #195ca6; color: #fff; text-align:center;">Alerta Medica</th>
                                            <th style="background-color: #195ca6; color: #fff; text-align:center;">Alerta Conductual</th>
                                            <th style="background-color: #195ca6; color: #fff; text-align:center;">Mensajeria en Tiempo Real</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($weightings['data']['rowsHalls'] as $weighting) {
                                                echo "<tr>
                                                        <td style='height: 20px; padding: 7px'><input name='txtIdHalls' type='hidden' value='" . $weighting['id'] . "'><input name='txtMinHalls' type='text' class='form-control text-center' value='" . $weighting['min'] . "'></td>
                                                        <td style='height: 20px; padding: 7px'><input name='txtMaxHalls' type='text' class='form-control text-center' value='" . $weighting['max'] . "'></td>
                                                        <td style='height: 20px; padding: 7px'><input name='txtMedicalAlertsHalls' type='text' class='form-control text-center' value='" . $weighting['medical_alert'] . "'></td>
                                                        <td style='height: 20px; padding: 7px'><input name='txtBehavioralAlertsHalls' type='text' class='form-control text-center' value='" . $weighting['behavioral_alert'] . "'></td>
                                                        <td style='height: 20px; padding: 7px'><input name='txtMessagesHalls' type='text' class='form-control text-center' value='" . $weighting['messages'] . "'></td>
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
            <div class="modal-footer">
                <button type="button" class="btnButtonModal" onclick="updateWeightings()"><i class="fas fa-save" aria-hidden="true"></i> Guardar</button>
                <button type="button" class="btnButtonCloseModal" data-dismiss="modal"><i class="fas fa-sign-out-alt" aria-hidden="true"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>