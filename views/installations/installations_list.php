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
                    <h1 class="m-0" style="color: #195ca6;">Instalaciones</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Instalaciones</li>
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
                        <button class="btnButtonModal" style="float: right;" onclick="showInstallations()"><i class="fas fa-sticky-note"></i> Nueva Instalación</button>
                        <h5 style="color: #195ca6;"><i class="fas fa-university"></i> Colegios</h5>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="color: #195ca6;">#</th>
                                        <th class="" style="color: #195ca6;">Nombre</th>
                                        <th class="text-center" style="color: #195ca6;">Fecha de solicitud</th>
                                        <th class="text-center" style="color: #195ca6;">Estatus</th>
                                        <th class="text-center" style="color: #195ca6;">Url</th>
                                        <th class="text-center" style="color: #195ca6;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if ($installations['schools'] != null) {
                                            foreach ($installations['schools']['rows'] as $a => $school) {
                                                $status = ($school['status'] == 0) ? 'Pendiente' : 'Completado';
                                                $color = ($school['status'] == 0) ? 'brown' : 'green';
                                                $url = ($school['url_connect'] == '') ? '-' : '<a href="'. $school['url_connect'] . '" target="_blank"> ' . $school['url_connect'] . '</a>';
                                                
                                                echo '<tr style="cursor: pointer;">
                                                        <td class="text-center" style="color: #195ca6;"><b>' . ($a + 1) . '</b></td>
                                                        <td>' . $school['rbd'] . ' - ' . $school['nombre'] . '</td>
                                                        <td class="text-center">' . substr($school['application_date'], 8, 2) . '-' . substr($school['application_date'], 5, 2) . '-' . substr($school['application_date'], 0, 4) . '</td>
                                                        <td class="text-center" style="color: ' . $color . ';"><b>' . $status . '</b></td>
                                                        <td class="text-center">' . $url . '</td>
                                                        <td class="text-center">
                                                            <span class="fa fa-edit" style="color: #286090; cursor: pointer;" onclick="editInstallation(\'' . $school['id'] . '\', \'' . $school['rbd'] . '\', \'' . $school['nombre'] . '\', \'' . substr($school['application_date'], 0, 10) . '\', \'' . $school['status'] . '\', \'' . $school['url_connect'] . '\')"></span>&nbsp;&nbsp;
                                                            <span class="fa fa-trash" style="color: #286090; cursor: pointer;" onclick="deleteInstallation(\'' . $school['id'] . '\')"></span>
                                                        </td>
                                                    </tr>';
                                            }
                                        }
                                        else {
                                            echo '<tr style="cursor: pointer;">
                                                    <td colspan="6" class="text-center">No hay registros</td>
                                                </tr>';
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

<!--Modal Installations-->
<div class="modal fade-scale" id="modalInstallations" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: #195ca6;"><i class="fa fa-wrench"></i> Instalación</h5>
            </div>
            <input id="txtId" type="hidden">
            <div class="modal-body modalBody" style="background-color: #f9f9f9; padding: 20px;">
                <div class="row">
                    <div class="col-md-3" style="color: #264173; font-size: 14px; margin-bottom: 15px;">
                        <label for="txtRbd">RBD</label>
                        <input id="txtRbd" name="txtRbd" type="text" class="form-control" maxlength="11" placeholder="RBD" onblur="searchSchoolByRbd(this.value)">
                    </div>
                    <div class="col-md-6" style="color: #264173; font-size: 14px; margin-bottom: 15px;">
                        <label for="txtSchool">Colegio</label>
                        <input id="txtSchool" name="txtSchool" type="text" class="form-control" placeholder="Colegio" readonly>
                    </div>
                    <div class="col-md-3" style="color: #264173; font-size: 14px; margin-bottom: 15px;">
                        <label for="txtDateReq">Fecha de Solicitud</label>
                        <input id="txtDateReq" name="txtDateReq" type="date" class="form-control">
                    </div>
                    <div class="col-md-3" style="color: #264173; font-size: 14px; margin-bottom: 15px;">
                        <label for="cboStatus">Estatus</label>
                        <select id="cboStatus" class="form-control">
                            <option value="" selected>Seleccione</option>
                            <option value="0">Pendiente</option>
                            <option value="1">Completado</option>
                        </select>
                    </div>
                    <div class="col-md-9" style="color: #264173; font-size: 14px; margin-bottom: 15px;">
                        <label for="txtUrl">Url</label>
                        <input id="txtUrl" name="txtUrl" type="text" class="form-control" maxlength="255" placeholder="Url">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btnButtonModal" onclick="saveInstallation()"><i class="fas fa-save" aria-hidden="true"></i> Guardar</button>
                <button type="button" class="btnButtonCloseModal" data-dismiss="modal"><i class="fas fa-sign-out-alt" aria-hidden="true"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>