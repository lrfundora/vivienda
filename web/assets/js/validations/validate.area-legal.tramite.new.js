/**
 * Created by LARRY on 3/16/2016.
 */
$(function (e) {

    //Cancel button
    $('#cancel').on('click', function (e) {
        $(location).attr('href', $('#cancel').attr('data-url'));
    });

    //Select2
    $('#idAbogado').select2({
        placeholder: "Seleccione un abogado",
        allowClear: true
    });

    $('#idAbogado').on('change', function (e) {
        checkAbogado(this.id);
    });
    $('#send').on('click', function (e) {
        checkAbogado('idAbogado');
    });
    function checkAbogado(id) {
        id = '#' + id;
        if ($(id).val()) {
            $(id).removeClass('error').addClass('valid').attr('aria-invalid', 'false');
            $(id + '-error').text('').addClass('hidden');
        } else {
            $(id).removeClass('valid').addClass('error').attr('aria-invalid', 'true');
            $(id + '-error').text('Seleccione un abogado.').removeClass('hidden');
        }
    }

    //Calendar
    $('#date_range .input-daterange').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });

    //Validar fecha
    $('#tramite_fechaEntrada').on('change', function (e) {
        checkDate();
    });
    $('#tramite_fechaVencimiento').on('change', function (e) {
        checkDate();
    });

    function checkDate() {
        arr1 = $('#tramite_fechaEntrada').val().split('/');
        arr2 = $('#tramite_fechaVencimiento').val().split('/');
        date1 = new Date(arr1[2], arr1[1] - 1, arr1[0]);
        date2 = new Date(arr2[2], arr2[1] - 1, arr2[0]);
        if (date1 < date2) {
            $('#checkdate').addClass('hidden');
        } else {
            $('#checkdate').removeClass('hidden');
        }
    }

    //Validaciones de los formularios
    $("#form").validate({
        rules: {
            'idAbogado': {
                required: true
            },
            'tramite[fechaEntrada]': {
                required: true
            },
            'tramite[fechaVencimiento]': {
                required: true
            },
            'tramite[descripcion]': {
                required: true,
                minlength: 5,
                maxlength: 1000
            },
            'tramite[comenterio]': {
                required: false
            },
            'tramite[isCompletado]': {
                required: false
            }
        },
        messages: {
            'idAbogado': {
                required: "Seleccione un abogado."
            },
            'tramite[fechaEntrada]': {
                required: "Introduzca la fecha de entrada del trámite."
            },
            'tramite[fechaVencimiento]': {
                required: "Introduzca la fecha de vencimiento del trámite"
            },
            'tramite[descripcion]': {
                required: "Introduzca la descripción del trámite.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $('#tramite_fechaEntrada').on('change', function (e) {
        if ($('#tramite_fechaEntrada').val()) {
            $('#tramite_fechaEntrada').removeClass('error').addClass('valid');
            $('#tramite_fechaEntrada').attr('aria-invalid', 'false');
            $('#tramite_fechaEntrada-error').text('');
            $('#tramite_fechaEntrada-error').addClass('hidden');
        } else {
            $('#tramite_fechaEntrada').removeClass('valid').addClass('error');
            $('#tramite_fechaEntrada').attr('aria-invalid', 'true');
            $('#tramite_fechaEntrada-error').text('Introduzca la fecha de entrada del trámite.');
            $('#tramite_fechaEntrada-error').removeClass('hidden');
        }
    });

    $('#tramite_fechaVencimiento').on('change', function (e) {
        if ($('#tramite_fechaVencimiento').val()) {
            $('#tramite_fechaVencimiento').removeClass('error').addClass('valid');
            $('#tramite_fechaVencimiento').attr('aria-invalid', 'false');
            $('#tramite_fechaVencimiento-error').text('');
            $('#tramite_fechaVencimiento-error').addClass('hidden');
        } else {
            $('#tramite_fechaVencimiento').removeClass('valid').addClass('error');
            $('#tramite_fechaVencimiento').attr('aria-invalid', 'true');
            $('#tramite_fechaVencimiento-error').text('Introduzca la fecha de entrada del trámite.');
            $('#tramite_fechaVencimiento-error').removeClass('hidden');
        }
    });
});