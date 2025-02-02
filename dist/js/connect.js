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