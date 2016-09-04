/**
 * Created by LARRY on 3/16/2016.
 */
$(function (e) {

    //Cancel button
    $('#cancel').on('click', function (e) {
        $(location).attr('href', $('#cancel').attr('data-url'));
    });

    //Validando Quitar arrendatario Model
    $('button[class*="del"]').on('click', function (e) {
        msgText = 'Si quita al arrendatario "' + $(this).attr('data-name') + '" del arrendamiento con número de registro "' + $(this).attr('data-reg') + '" implicará que automáticamente dejará de ser parte del arrendamiento.';
        $('#delete_text').text(msgText);
        $('#formDelete input[id="id"]').val($(this).attr('data-idc'));
        $('#formDelete input[id*="_id"]').val($(this).attr('data-ida'));
        //$('#formDelete').submit();
    });

    //Select2
    $('#arrendamiento_anoConstruccion').select2({
        placeholder: "Seleccione un año",
        allowClear: true
    });

    $('#arrendamiento_estado').select2({
        placeholder: "Seleccione el estado",
        allowClear: true
    });

    $('#arrendamiento_anoConstruccion').on('change', function (e) {
        checkSelect2(this.id, 'Seleccione el año de construcción.');
    });
    $('#arrendamiento_estado').on('change', function (e) {
        checkSelect2(this.id, 'Seleccione el estado.');
    });

    $('#send').on('click', function (e) {
        checkSend();
    });

    function checkSend() {
        checkSelect2('arrendamiento_anoConstruccion', 'Seleccione el año de construcción.');
        checkSelect2('arrendamiento_estado', 'Seleccione el estado.');
        checkSelect2('arrendamiento_fechaContrato', 'Seleccione la fecha del contrato.');
    }

    //Calendar
    $('#data .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        format: 'dd/mm/yyyy'
    });

    $('#arrendamiento_fechaContrato').on('change', function (e) {
        checkSelect2('arrendamiento_fechaContrato', 'Seleccione la fecha del contrato.');
    });

    $('#arrendamiento_carnet').on('keyup', function (e) {
        clearNumber(this.id);
    });

    $('#arrendamiento_nombre').on('keyup', function (e) {
        clearString(this.id);
    });
    $('#arrendamiento_apellidos').on('keyup', function (e) {
        clearString(this.id);
    });

    $('#arrendamiento_numeroRegistro').on('keyup', function (e) {
        clearNumber(this.id);
    });
    $('#arrendamiento_expediente').on('keyup', function (e) {
        clearExp(this.id);
    });
    $('#arrendamiento_numeroContrato').on('keyup', function (e) {
        clearNumber(this.id);
    });

    $('#arrendamiento_valor').on('keyup', function (e) {
        clearPrice(this.id);
    });

    formatPrice('arrendamiento_valor');

    //Formatendo el campo valor
    $('input[id*="_valor"]').on('focusin', function (e) {
        if (!isNaN($('#' + this.id).val() * 1) && ($('#' + this.id).val() * 1) != 0) {
            $('#' + this.id).select();
        }
    });

    $('input[id*="_valor"]').on('focusout', function (e) {
        formatPrice(this.id);
    });

    function formatPrice(id) {
        id = '#' + id;
        if (!isNaN($(id).val() * 1) && ($(id).val() * 1) != 0) {
            $(id).val(($(id).val() * 1).toFixed(2));
        } else {
            $(id).val('');
        }
    }

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

    //Eliminar simbolo y letras de un numero
    function clearExp(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^0-9\/]/, ''));
    }

    //Eliminar simbolo y letras de un precio
    function clearPrice(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^0-9.,]/, ''));
    }

    //Validaciones del formulario
    $("#form").validate({
        rules: {
            'arrendamiento[numeroRegistro]': {
                required: true,
                number: true,
                maxlength: 10
            },
            'arrendamiento[resolucion]': {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            'arrendamiento[expediente]': {
                required: true,
                minlength: 1,
                maxlength: 20
            },
            'arrendamiento[numeroContrato]': {
                required: true,
                number: true,
                maxlength: 10
            },
            'arrendamiento[fechaContrato]': {
                required: true
            },
            'arrendamiento[anoConstruccion]': {
                required: true
            },
            'arrendamiento[estado]': {
                required: true
            },
            'arrendamiento[valor]': {
                required: true,
                number: true,
                maxlength: 12
            },
            'arrendamiento[motivo]': {
                required: true,
                minlength: 3,
                maxlength: 500
            }
        },
        messages: {
            'arrendamiento[numeroRegistro]': {
                required: "Introduzca el número de registro.",
                number: "Este campo solo admite número.",
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'arrendamiento[resolucion]': {
                required: "Introduzca la resolución.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'arrendamiento[expediente]': {
                required: "Introduzca la expediente.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'arrendamiento[numeroContrato]': {
                required: "Introduzca el número de contrato.",
                number: "Este campo solo admite número.",
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'arrendamiento[fechaContrato]': {
                required: "Seleccione la fecha del contrato."
            },
            'arrendamiento[anoConstruccion]': {
                required: "Seleccione el año de construcción."
            },
            'arrendamiento[estado]': {
                required: "Seleccione el estado."
            },
            'arrendamiento[valor]': {
                required: "Introduzca el valor del arrendamiento.",
                number: "Este campo solo admite número.",
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'arrendamiento[motivo]': {
                required: "Introduzca el motivo del arrendamiento.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    function checkSelect2(id, text) {
        id = '#' + id;
        if ($(id).val()) {
            $(id).removeClass('error').addClass('valid').attr('aria-invalid', 'false');
            $(id + '-error').text('').addClass('hidden');
        } else {
            $(id).removeClass('valid').addClass('error').attr('aria-invalid', 'true');
            $(id + '-error').text(text).removeClass('hidden');
        }
    }
});