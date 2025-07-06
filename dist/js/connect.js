function enter(e, obj) {
	tecla = (document.all) ? event.keyCode : e.which;
	if (tecla == 13) {
      event.preventDefault();
      $('#'+obj).click();
    }
}

function access() {
    if ($('#txtUser').val() == '') {
        Swal.fire({
            title: "<div style=\"font-size: 14pt;\">Atención</div>",
            icon: "warning",
            width: "400px",
            html: "<div style=\"font-size: 14pt;\">Debe introducir el usuario</div>",
            customClass: {
                confirmButton: "btnButtonModal"
            },
            buttonsStyling: false
        });
    }
    else if ($('#txtPass').val() == '') {
        Swal.fire({
            title: "<div style=\"font-size: 14pt;\">Atención</div>",
            icon: "warning",
            width: "400px",
            html: "<div style=\"font-size: 14pt;\">Debe introducir la clave</div>",
            customClass: {
                confirmButton: "btnButtonModal"
            },
            buttonsStyling: false
        });
    }
    else {
        $.ajax({
            url: 'controllers/AccessController.php?f=access',
            type: 'POST',
            data: {
                user: $('#txtUser').val(),
                password: $('#txtPass').val()
            },
            success: function (response) {
                try {
                    if (response == 0) {
                        Swal.fire({
                            title: "<div style=\"font-size: 14pt;\">Atención</div>",
                            icon: "warning",
                            width: "400px",
                            html: "<div style=\"font-size: 14pt;\">Credenciales de acceso inválidas</div>",
                            customClass: {
                                confirmButton: "btnButtonModal"
                            },
                            buttonsStyling: false
                        });
                    }
                    else if (response == 1) {
                        Swal.fire({
                            title: "<div style=\"font-size: 14pt;\"><b>Bienvenido</b></div>",
                            icon: "success",
                            width: "400px",
                            html: "<div style=\"font-size: 12pt;\">Ingresando al sistema...</div>",
                            showConfirmButton: false,
                            buttonsStyling: false
                        });
                        window.location.href = 'menu.php';
                    }
                } catch (error) {
                    console.error('Error al parsear la respuesta JSON: ', error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en la solicitud AJAX: ', textStatus, errorThrown);
            }
        });
    }
}

function showDetails(n) {
    $('#tblDetails'+n).toggle();
}

function showWeightings() {
    $('#modalWeightings').modal('show');
}

function updateWeightings() {
    let idsAdministrative = document.getElementsByName("txtIdAdministrative");
    let minAdministrative = document.getElementsByName("txtMinAdministrative");
    let maxAdministrative = document.getElementsByName("txtMaxAdministrative");
    let calendarEventsAdministrative = document.getElementsByName("txtCalendarEventsAdministrative");
    let calendarBellsAdministrative = document.getElementsByName("txtCalendarBellsAdministrative");
    let alertsAdministrative = document.getElementsByName("txtAlertsAdministrative");
    let messagesAdministrative = document.getElementsByName("txtMessagesAdministrative");
    let sizeAdministrative = idsAdministrative.length;
    let arrAdministrative = [];
    
    for (let a = 0; a < sizeAdministrative; a++) {
        if (!$.isNumeric(minAdministrative[a].value) || !$.isNumeric(maxAdministrative[a].value) || !$.isNumeric(calendarEventsAdministrative[a].value) || !$.isNumeric(calendarBellsAdministrative[a].value) || !$.isNumeric(alertsAdministrative[a].value) || !$.isNumeric(messagesAdministrative[a].value)) {
            Swal.fire({
                title: "<div style=\"font-size: 14pt;\">Atención</div>",
                icon: "warning",
                width: "400px",
                html: "<div style=\"font-size: 12pt;\">Todos los valores de la tabla Administración-Aulas deben ser númericos</div>",
                customClass: {
                    confirmButton: "btnButtonModal"
                },
                buttonsStyling: false
            });
            return false;
        }
        else {
            arrAdministrative.push({
                                    'id': idsAdministrative[a].value,
                                    'min': minAdministrative[a].value,
                                    'max': maxAdministrative[a].value,
                                    'calendar_events': calendarEventsAdministrative[a].value,
                                    'calendar_bells': calendarBellsAdministrative[a].value,
                                    'alerts': alertsAdministrative[a].value,
                                    'messages': messagesAdministrative[a].value
                                });
        }
    }
    
    let idsHalls = document.getElementsByName("txtIdHalls");
    let minHalls = document.getElementsByName("txtMinHalls");
    let maxHalls = document.getElementsByName("txtMaxHalls");
    let medicalAlertsHalls = document.getElementsByName("txtMedicalAlertsHalls");
    let behavioralAlertsHalls = document.getElementsByName("txtBehavioralAlertsHalls");
    let messagesHalls = document.getElementsByName("txtMessagesHalls");
    let sizeHalls = idsHalls.length;
    let arrHalls = [];

    for (let a = 0; a < sizeHalls; a++) {
        if (!$.isNumeric(minHalls[a].value) || !$.isNumeric(maxHalls[a].value) || !$.isNumeric(medicalAlertsHalls[a].value) || !$.isNumeric(behavioralAlertsHalls[a].value) || !$.isNumeric(messagesHalls[a].value)) {
            Swal.fire({
                title: "<div style=\"font-size: 14pt;\">Atención</div>",
                icon: "warning",
                width: "400px",
                html: "<div style=\"font-size: 12pt;\">Todos los valores de la tabla Aulas-Administración deben ser númericos</div>",
                customClass: {
                    confirmButton: "btnButtonModal"
                },
                buttonsStyling: false
            });
            return false;
        }
        else {
            arrHalls.push({
                            'id': idsAdministrative[a].value,
                            'min': minHalls[a].value,
                            'max': maxHalls[a].value,
                            'medical_alert': medicalAlertsHalls[a].value,
                            'behavioral_alert': behavioralAlertsHalls[a].value,
                            'messages': messagesHalls[a].value
                        });
        }
    }
    
    let arrFields = {'arrAdministrative': arrAdministrative, 'arrHalls': arrHalls};

    $.ajax({
        url: 'controllers/SchoolController.php?f=updateWeightings',
        type: 'POST',
        data: {
            token: $('#token').val(),
            arrFields
        },
        success: function (response) {
            try {
                let data = JSON.parse(response);
                if (data.response == 'ok') {
                    Swal.fire({
                        title: "<div style=\"font-size: 14pt;\">Hecho</div>",
                        icon: "success",
                        width: "400px",
                        html: "<div style=\"font-size: 12pt;\">Se actualizaron los registros con éxito</div>",
                        timer: 2000,
                        customClass: {
                            confirmButton: "btnButtonModal"
                        },
                        buttonsStyling: false
                    });
                }
                else {
                    Swal.fire({
                        title: "<div style=\"font-size: 14pt;\">Atención</div>",
                        icon: "warning",
                        width: "400px",
                        html: "<div style=\"font-size: 12pt;\">Hubo un error al actualizar el registro</div>",
                        customClass: {
                            confirmButton: "btnButtonModal"
                        },
                        buttonsStyling: false
                    });
                }
            } catch (error) {
                Swal.fire({
                    title: "<div style=\"font-size: 14pt;\">Atención</div>",
                    icon: "warning",
                    width: "400px",
                    html: "<div style=\"font-size: 12pt;\">Hubo un error al actualizar el registro: " + error + "</div>",
                    customClass: {
                        confirmButton: "btnButtonModal"
                    },
                    buttonsStyling: false
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Error en la solicitud AJAX: ', textStatus, errorThrown);
        }
    });
}

function showDetailsMonth(title, calendarEvents, calendarBells, resourcesSubmitted, messagesAdministrative, medicalAlerts, behavioralAlerts, messagesHalls) {
    $('#spanTitle').html(title);
    $('#valCalendarEvents').html(calendarEvents);
    $('#valCalendarBells').html(calendarBells);
    $('#valAlerts').html(resourcesSubmitted);
    $('#valMessagesAdministrative').html(messagesAdministrative);
    $('#valMedicalAlerts').html(medicalAlerts);
    $('#valBehavioralAlerts').html(behavioralAlerts);
    $('#valMessagesHalls').html(messagesHalls);
    $('#modalDetails').modal('show');
}

function showFilter() {
    $('#optDate1').prop('checked', false);
    $('#optDate2').prop('checked', false);
    $('#optDate3').prop('checked', false);
    $('#txtDate').val('');
    $('#selDate').val('');
    $('#selDateYear').val('');
    $('.filter-option-inner-inner').html('Seleccione');
    $('#modalFilter').modal('show');
}

function processFilter() {
    let arrFields = [];
    if ($('#optDate1').is(':checked') == false && $('#optDate2').is(':checked') == false && $('#optDate3').is(':checked') == false) {
        Swal.fire({
            title: "<div style=\"font-size: 14pt;\">Atención</div>",
            icon: "warning",
            width: "400px",
            html: "<div style=\"font-size: 12pt;\">Debe seleccionar una opcion de filtrado</div>",
            customClass: {
                confirmButton: "btnButtonModal"
            },
            buttonsStyling: false
        });
        return false;
    }
    else if ($('#optDate1').is(':checked') && ($('#txtDate').val() == '')) {
        Swal.fire({
            title: "<div style=\"font-size: 14pt;\">Atención</div>",
            icon: "warning",
            width: "400px",
            html: "<div style=\"font-size: 12pt;\">Debe seleccionar el dia</div>",
            customClass: {
                confirmButton: "btnButtonModal"
            },
            buttonsStyling: false
        });
        return false;
    }
    else if ($('#optDate1').is(':checked') && ($('#txtDate').val() != '')) {
        arrFields = {
            'type': 1,
            'val': $('#txtDate').val()
        };
    }
    else if ($('#optDate2').is(':checked') && ($('#selDate').val() == '')) {
        Swal.fire({
            title: "<div style=\"font-size: 14pt;\">Atención</div>",
            icon: "warning",
            width: "400px",
            html: "<div style=\"font-size: 12pt;\">Debe seleccionar el mes o meses</div>",
            customClass: {
                confirmButton: "btnButtonModal"
            },
            buttonsStyling: false
        });
        return false;
    }
    else if ($('#optDate2').is(':checked') && ($('#selDate').val() != '')) {
        arrFields = {
            'type': 2,
            'val': JSON.stringify($('#selDate').val())
        };
    }
    else if ($('#optDate3').is(':checked') && ($('#selDateYear').val() == '')) {
        Swal.fire({
            title: "<div style=\"font-size: 14pt;\">Atención</div>",
            icon: "warning",
            width: "400px",
            html: "<div style=\"font-size: 12pt;\">Debe seleccionar el año</div>",
            customClass: {
                confirmButton: "btnButtonModal"
            },
            buttonsStyling: false
        });
        return false;
    }
    else if ($('#optDate3').is(':checked') && ($('#selDateYear').val() != '')) {
        arrFields = {
            'type': 3,
            'val': $('#selDateYear').val()
        };
    }

    $('#loading').show();
    $.ajax({
        url: 'controllers/SchoolController.php?f=processFilter',
        type: 'POST',
        data: {
            token: $('#token').val(),
            arrFields
        },
        success: function (response) {
            $('#loading').hide();
            try {
                if (response != '') {
                    $('#tblSchools').html(response);
                    $('#modalFilter').modal('hide');
                    Swal.fire({
                        title: "<div style=\"font-size: 14pt;\">Hecho</div>",
                        icon: "success",
                        width: "400px",
                        html: "<div style=\"font-size: 12pt;\">Se actualizaron los datos</div>",
                        timer: 2000,
                        customClass: {
                            confirmButton: "btnButtonModal"
                        },
                        buttonsStyling: false
                    });
                }
                else {
                    Swal.fire({
                        title: "<div style=\"font-size: 14pt;\">Atención</div>",
                        icon: "warning",
                        width: "400px",
                        html: "<div style=\"font-size: 12pt;\">Hubo un error al actualizar</div>",
                        customClass: {
                            confirmButton: "btnButtonModal"
                        },
                        buttonsStyling: false
                    });
                }
            } catch (error) {
                Swal.fire({
                    title: "<div style=\"font-size: 14pt;\">Atención</div>",
                    icon: "warning",
                    width: "400px",
                    html: "<div style=\"font-size: 12pt;\">Hubo un error al actualizar: " + error + "</div>",
                    customClass: {
                        confirmButton: "btnButtonModal"
                    },
                    buttonsStyling: false
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#loading').hide();
            console.log('Error en la solicitud AJAX: ', textStatus, errorThrown);
        }
    });
}

function showInstallations() {
    $('#txtRbd').val('');
    $('#txtSchool').val('');
    $('#txtDateReq').val('');
    $('#cboStatus').val('');
    $('#txtUrl').val('');
    $('#modalInstallations').modal('show');
}

function searchSchoolByRbd() {
    if ($.trim($("#txtRbd").val()) != "") {
        $('#loading').show();
        $.ajax({
            url: 'config/routes.php?v=Installations/searchSchoolByRbd',
            type: 'POST',
            data: {
                token: $('#token').val(),
                rbd: $('#txtRbd').val()
            },
            success: function (response) {
                $('#loading').hide();
                const data = JSON.parse(response);
                try {
                    if (data != '' && data.schools != null) {
                        $('#txtSchool').val(data.schools.rows[0].nombre);
                    }
                    else {
                        $('#txtSchool').val('');
                        Swal.fire({
                            title: "<div style=\"font-size: 14pt;\">Atención</div>",
                            icon: "warning",
                            width: "400px",
                            html: "<div style=\"font-size: 12pt;\">No hay un colegio con el RBD escrito</div>",
                            customClass: {
                                confirmButton: "btnButtonModal"
                            },
                            buttonsStyling: false
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        title: "<div style=\"font-size: 14pt;\">Atención</div>",
                        icon: "warning",
                        width: "400px",
                        html: "<div style=\"font-size: 12pt;\">Hubo un error al procesar los datos: " + error + "</div>",
                        customClass: {
                            confirmButton: "btnButtonModal"
                        },
                        buttonsStyling: false
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#loading').hide();
                console.log('Error en la solicitud: ', textStatus, errorThrown);
            }
        });
    }
}

function saveInstallation() {
    if ($.trim($('#txtRbd').val()) == '') {
        Swal.fire({
            title: "<div style=\"font-size: 14pt;\">Atención</div>",
            icon: "warning",
            width: "400px",
            html: "<div style=\"font-size: 12pt;\">Debe introducir el RBD</div>",
            customClass: {
                confirmButton: "btnButtonModal"
            },
            buttonsStyling: false
        });
        return false;
    }
    else if ($.trim($('#txtSchool').val()) == '') {
        Swal.fire({
            title: "<div style=\"font-size: 14pt;\">Atención</div>",
            icon: "warning",
            width: "400px",
            html: "<div style=\"font-size: 12pt;\">Debe haber un nombre de colegio</div>",
            customClass: {
                confirmButton: "btnButtonModal"
            },
            buttonsStyling: false
        });
        return false;
    }
    else if ($('#txtDateReq').val() == '') {
        Swal.fire({
            title: "<div style=\"font-size: 14pt;\">Atención</div>",
            icon: "warning",
            width: "400px",
            html: "<div style=\"font-size: 12pt;\">Debe introducir la fecha de solicitud</div>",
            customClass: {
                confirmButton: "btnButtonModal"
            },
            buttonsStyling: false
        });
        return false;
    }
    else if ($('#cboStatus').val() == '') {
        Swal.fire({
            title: "<div style=\"font-size: 14pt;\">Atención</div>",
            icon: "warning",
            width: "400px",
            html: "<div style=\"font-size: 12pt;\">Debe seleccionar el estatus</div>",
            customClass: {
                confirmButton: "btnButtonModal"
            },
            buttonsStyling: false
        });
        return false;
    }

    let arrFields = {
            'id': $('#txtId').val(),
            'rbd': $('#txtRbd').val(),
            'dateReq': $('#txtDateReq').val(),
            'status': $('#cboStatus').val(),
            'url': $('#txtUrl').val()
        };

    $.ajax({
        url: 'config/routes.php?v=Installations/saveInstallation',
        type: 'POST',
        data: {
            token: $('#token').val(),
            arrFields
        },
        success: function (response) {
            try {
                let data = JSON.parse(response);
                if (data.schools == 'ok') {
                    $('#modalInstallations').modal('hide');
                    Swal.fire({
                        title: "<div style=\"font-size: 14pt;\">Hecho</div>",
                        icon: "success",
                        width: "400px",
                        html: "<div style=\"font-size: 12pt;\">Se actualizaron los registros con éxito</div>",
                        timer: 2000,
                        customClass: {
                            confirmButton: "btnButtonModal"
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        window.location.reload();
                    });
                }
                else {
                    Swal.fire({
                        title: "<div style=\"font-size: 14pt;\">Atención</div>",
                        icon: "warning",
                        width: "400px",
                        html: "<div style=\"font-size: 12pt;\">Hubo un error al actualizar el registro</div>",
                        customClass: {
                            confirmButton: "btnButtonModal"
                        },
                        buttonsStyling: false
                    });
                }
            } catch (error) {
                Swal.fire({
                    title: "<div style=\"font-size: 14pt;\">Atención</div>",
                    icon: "warning",
                    width: "400px",
                    html: "<div style=\"font-size: 12pt;\">Hubo un error al actualizar el registro: " + error + "</div>",
                    customClass: {
                        confirmButton: "btnButtonModal"
                    },
                    buttonsStyling: false
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Error en la solicitud AJAX: ', textStatus, errorThrown);
        }
    });
}

function editInstallation(id, rbd, school, dateReq, status, url) {
    $('#txtId').val(id);
    $('#txtRbd').val(rbd);
    $('#txtSchool').val(school);
    $('#txtDateReq').val(dateReq);
    $('#cboStatus').val(status);
    $('#txtUrl').val(url);
    $('#modalInstallations').modal('show');
}

function deleteInstallation(id) {
    Swal.fire({
        title: "Connect",
        icon: "question",
        width: "400px",
        html: '<span style="font-size: 16px;">¿Confirma eliminar el recurso?</span>',
        showCancelButton: true,
        confirmButtonText: '<span style="font-size: 16px">Si</span>',
        confirmButtonColor: "#3085d6",
        cancelButtonText: '<span style="font-size: 16px">No</span>',
        onOpen: () => {
            Swal.getCancelButton().focus();
        },
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: 'config/routes.php?v=Installations/deleteInstallation',
                type: 'POST',
                data: {
                    token: $('#token').val(),
                    id
                },
                success: function (response) {
                    try {
                        let data = JSON.parse(response);
                        if (data.schools == 'ok') {
                            $('#modalInstallations').modal('hide');
                            Swal.fire({
                                title: "<div style=\"font-size: 14pt;\">Hecho</div>",
                                icon: "success",
                                width: "400px",
                                html: "<div style=\"font-size: 12pt;\">Se elimino el registro con éxito</div>",
                                timer: 2000,
                                customClass: {
                                    confirmButton: "btnButtonModal"
                                },
                                buttonsStyling: false
                            }).then((result) => {
                                window.location.reload();
                            });
                        }
                        else {
                            Swal.fire({
                                title: "<div style=\"font-size: 14pt;\">Atención</div>",
                                icon: "warning",
                                width: "400px",
                                html: "<div style=\"font-size: 12pt;\">Hubo un error al actualizar el registro</div>",
                                customClass: {
                                    confirmButton: "btnButtonModal"
                                },
                                buttonsStyling: false
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            title: "<div style=\"font-size: 14pt;\">Atención</div>",
                            icon: "warning",
                            width: "400px",
                            html: "<div style=\"font-size: 12pt;\">Hubo un error al actualizar el registro: " + error + "</div>",
                            customClass: {
                                confirmButton: "btnButtonModal"
                            },
                            buttonsStyling: false
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error en la solicitud AJAX: ', textStatus, errorThrown);
                }
            });
        }
    });
}