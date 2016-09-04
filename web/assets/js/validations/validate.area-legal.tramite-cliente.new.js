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
        if ($('#' + id).val()) {
            $('#' + id).removeClass('error').addClass('valid').attr('aria-invalid', 'false');
            $('#' + id + '-error').text('').addClass('hidden');
        } else {
            $('#' + id).removeClass('valid').addClass('error').attr('aria-invalid', 'true');
            $('#' + id + '-error').text('Seleccione un abogado.').removeClass('hidden');
        }
    }

    //Calendar
    $('#date_range .input-daterange').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });


    //Validar fecha
    $('#cliente_tramite_tramites_0_fechaEntrada').on('change', function (e) {
        checkDate();
    });
    $('#cliente_tramite_tramites_0_fechaVencimiento').on('change', function (e) {
        checkDate();
    });

    function checkDate() {
        arr1 = $('#cliente_tramite_tramites_0_fechaEntrada').val().split('/');
        arr2 = $('#cliente_tramite_tramites_0_fechaVencimiento').val().split('/');
        date1 = new Date(arr1[2], arr1[1] - 1, arr1[0]);
        date2 = new Date(arr2[2], arr2[1] - 1, arr2[0]);
        if (date1 < date2) {
            $('#checkdate').addClass('hidden');
        } else {
            $('#checkdate').removeClass('hidden');
        }
    }

    $('#cliente_tramite_carnet').on('keyup', function (e) {
        clearNumber(this.id);
    });

    $('#cliente_tramite_nombre').on('keyup', function (e) {
        clearString(this.id);
    });

    $('#cliente_tramite_apellidos').on('keyup', function (e) {
        clearString(this.id);
    });

    //Eliminar simbolo y numeros de una cadena
    function clearString(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^a-zA-ZñÑáÁéÉíÍóÓúÚ ]/, ''));
    }

    //Eliminar simbolo y letras de un numero
    function clearNumber(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^0-9]/, ''));
    }

    //Validaciones de los formularios
    $("#form").validate({
        rules: {
            'cliente_tramite[carnet]': {
                required: true,
                number: true,
                minlength: 11,
                maxlength: 11
            },
            'cliente_tramite[nombre]': {
                required: true,
                minlength: 3,
                maxlength: 25
            },
            'cliente_tramite[apellidos]': {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            'cliente_tramite[direccion]': {
                required: true,
                minlength: 3,
                maxlength: 255
            },
            'idAbogado': {
                required: true
            },
            'cliente_tramite[tramites][0][fechaEntrada]': {
                required: true
            },
            'cliente_tramite[tramites][0][fechaVencimiento]': {
                required: true
            },
            'cliente_tramite[tramites][0][descripcion]': {
                required: true,
                minlength: 5,
                maxlength: 1000
            },
            'cliente_tramite[tramites][0][comentario]': {
                required: false
            },
            'cliente_tramite[tramites][0][isCompletado]': {
                required: false
            }
        },
        messages: {
            'cliente_tramite[carnet]': {
                required: "Introduzca el carnet de identidad del cliente.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_tramite[nombre]': {
                required: "Introduzca el nombre del cliente.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_tramite[apellidos]': {
                required: "Introduzca los apellidos del cliente.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_tramite[direccion]': {
                required: "Introduzca la dirección del cliente.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'idAbogado': {
                required: "Seleccione un abogado."
            },
            'cliente_tramite[tramites][0][fechaEntrada]': {
                required: "Seleccione la fecha de entrada del trámite."
            },
            'cliente_tramite[tramites][0][fechaVencimiento]': {
                required: "Seleccione la fecha de vencimiento del trámite."
            },
            'cliente_tramite[tramites][0][descripcion]': {
                required: "Escriba la descripción del trámite."
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $('input[id*="_fechaEntrada"]').on('change', function (e) {
        if ($('input[id*="_fechaEntrada"]').val()) {
            $('input[id*="_fechaEntrada"]').removeClass('error').addClass('valid');
            $('input[id*="_fechaEntrada"]').attr('aria-invalid', 'false');
            $('label[id*="_fechaEntrada-error"]').text('');
            $('label[id*="_fechaEntrada-error"]').addClass('hidden');
        } else {
            $('input[id*="_fechaEntrada"]').removeClass('valid').addClass('error');
            $('input[id*="_fechaEntrada"]').attr('aria-invalid', 'true');
            $('label[id*="_fechaEntrada-error"]').text('Seleccione la fecha de entrada del trámite.');
            $('label[id*="_fechaEntrada-error"]').removeClass('hidden');
        }
    });

    $('input[id*="_fechaVencimiento"]').on('change', function (e) {
        if ($('input[id*="_fechaVencimiento"]').val()) {
            $('input[id*="_fechaVencimiento"]').removeClass('error').addClass('valid');
            $('input[id*="_fechaVencimiento"]').attr('aria-invalid', 'false');
            $('input[id*="_fechaVencimiento-error"]').text('');
            $('input[id*="_fechaVencimiento-error"]').addClass('hidden');
        } else {
            $('input[id*="_fechaVencimiento"]').removeClass('valid').addClass('error');
            $('input[id*="_fechaVencimiento"]').attr('aria-invalid', 'true');
            $('input[id*="_fechaVencimiento-error"]').text('Seleccione la fecha de vencimiento del trámite.');
            $('input[id*="_fechaVencimiento-error"]').removeClass('hidden');
        }
    });


    ////Validando Delete Model
    //$('button[class*="user-delete"]').on('click', function (e) {
    //    msgText = 'Si elimina el usuario "' + $(this).attr('data-name') + '" implicará que dicho usuario no podrá iniciar sesión en el sistema aunque no se eliminará al trabajador de la plantilla labora.';
    //    $('#delete_text').text(msgText);
    //    $('input[id*="_item"]').val($(this).attr('data-item'));
    //});
    //
    ////Actions Info
    //$('button[class*="user-info"]').on('click', function (e) {
    //    $('input[id*="_id"]').val($(this).attr('data-item'));
    //    $('#formInfo').submit();
    //});

});