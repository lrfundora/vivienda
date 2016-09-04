/**
 * Created by LARRY on 3/16/2016.
 */
$(function (e) {

    //Cancel button
    $('#cancel').on('click', function (e) { $(location).attr('href',$('#cancel').attr('data-url')); });

    //Select2
    $('#cliente_arrendamiento_arrendamientos_0_anoConstruccion').select2({
        placeholder: "Seleccione un año",
        allowClear: true
    });

    $('#cliente_arrendamiento_arrendamientos_0_estado').select2({
        placeholder: "Seleccione el estado",
        allowClear: true
    });

    $('#cliente_arrendamiento_arrendamientos_0_anoConstruccion').on('change', function (e) { checkSelect2(this.id, 'Seleccione el año de construcción.'); });
    $('#cliente_arrendamiento_arrendamientos_0_estado').on('change', function (e) { checkSelect2(this.id, 'Seleccione el estado.'); });

    $('#send').on('click', function (e) { checkSend(); });

    function checkSend(){
        checkSelect2('cliente_arrendamiento_arrendamientos_0_anoConstruccion', 'Seleccione el año de construcción.');
        checkSelect2('cliente_arrendamiento_arrendamientos_0_estado', 'Seleccione el estado.');
        checkSelect2('cliente_arrendamiento_arrendamientos_0_fechaContrato', 'Seleccione la fecha del contrato.');
    }

    //Calendar
    $('#data .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        format: 'dd/mm/yyyy'
    });

    $('#cliente_arrendamiento_arrendamientos_0_fechaContrato').on('change', function (e) { checkSelect2(this.id, 'Seleccione la fecha del contrato.'); });

    $('#cliente_arrendamiento_carnet').on('keyup', function (e) {
        clearNumber(this.id);
    });

    $('#cliente_arrendamiento_nombre').on('keyup', function (e) {
        clearString(this.id);
    });
    $('#cliente_arrendamiento_apellidos').on('keyup', function (e) {
        clearString(this.id);
    });

    $('#cliente_arrendamiento_arrendamientos_0_numeroRegistro').on('keyup', function (e) {
        clearNumber(this.id);
    });
    $('#cliente_arrendamiento_arrendamientos_0_expediente').on('keyup', function (e) {
        clearExp(this.id);
    });
    $('#cliente_arrendamiento_arrendamientos_0_numeroContrato').on('keyup', function (e) {
        clearNumber(this.id);
    });

    $('#cliente_arrendamiento_arrendamientos_0_valor').on('keyup', function (e) {
        clearPrice(this.id);
    });

    //Formatendo el campo valor
    $('input[id*="_valor"]').on('focusin', function (e) {
        if (!isNaN($('#' + this.id).val() * 1) && ($('#' + this.id).val() * 1) != 0) {
            $('#' + this.id).select();
        }
    });
    $('input[id*="_valor"]').on('focusout', function (e) {
        if (!isNaN($('#' + this.id).val() * 1) && ($('#' + this.id).val() * 1) != 0) {
            $('#' + this.id).val(($('#' + this.id).val() * 1).toFixed(2));
        } else {
            $('#' + this.id).val('');
        }
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
            'cliente_arrendamiento[carnet]': {
                required: true,
                number: true,
                minlength: 11,
                maxlength: 11
            },
            'cliente_arrendamiento[nombre]': {
                required: true,
                minlength: 3,
                maxlength: 25
            },
            'cliente_arrendamiento[apellidos]': {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            'cliente_arrendamiento[direccion]': {
                required: true,
                minlength: 3,
                maxlength: 255
            },
            'cliente_arrendamiento[arrendamientos][0][numeroRegistro]': {
                required: true,
                number: true,
                maxlength: 10
            },
            'cliente_arrendamiento[arrendamientos][0][resolucion]': {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            'cliente_arrendamiento[arrendamientos][0][expediente]': {
                required: true,
                minlength: 1,
                maxlength: 20
            },
            'cliente_arrendamiento[arrendamientos][0][numeroContrato]': {
                required: true,
                number: true,
                maxlength: 10
            },
            'cliente_arrendamiento[arrendamientos][0][fechaContrato]': {
                required: true
            },
            'cliente_arrendamiento[arrendamientos][0][anoConstruccion]': {
                required: true
            },
            'cliente_arrendamiento[arrendamientos][0][estado]': {
                required: true
            },
            'cliente_arrendamiento[arrendamientos][0][valor]': {
                required: true,
                number: true,
                maxlength: 12
            },
            'cliente_arrendamiento[arrendamientos][0][motivo]': {
                required: true,
                minlength: 3,
                maxlength: 500
            }
        },
        messages: {
            'cliente_arrendamiento[carnet]': {
                required: "Introduzca el carnet de identidad del cliente.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_arrendamiento[nombre]': {
                required: "Introduzca el nombre del cliente.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_arrendamiento[apellidos]': {
                required: "Introduzca los apellidos del cliente.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_arrendamiento[direccion]': {
                required: "Introduzca la dirección del cliente.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_arrendamiento[arrendamientos][0][numeroRegistro]':{
                required: "Introduzca el número de registro.",
                number: "Este campo solo admite número.",
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_arrendamiento[arrendamientos][0][resolucion]': {
                required: "Introduzca la resolución.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_arrendamiento[arrendamientos][0][expediente]': {
                required: "Introduzca la expediente.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_arrendamiento[arrendamientos][0][numeroContrato]':{
                required: "Introduzca el número de contrato.",
                number: "Este campo solo admite número.",
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_arrendamiento[arrendamientos][0][fechaContrato]': {
                required: "Seleccione la fecha del contrato."
            },
            'cliente_arrendamiento[arrendamientos][0][anoConstruccion]': {
                required: "Seleccione el año de construcción."
            },
            'cliente_arrendamiento[arrendamientos][0][estado]': {
                required: "Seleccione el estado."
            },
            'cliente_arrendamiento[arrendamientos][0][valor]':{
                required: "Introduzca el valor del arrendamiento.",
                number: "Este campo solo admite número.",
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_arrendamiento[arrendamientos][0][motivo]': {
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
        if ($('#' + id).val()) {
            $('#' + id).removeClass('error').addClass('valid').attr('aria-invalid', 'false');
            $('#' + id + '-error').text('').addClass('hidden');
        } else {
            $('#' + id).removeClass('valid').addClass('error').attr('aria-invalid', 'true');
            $('#' + id + '-error').text(text).removeClass('hidden');
        }
    }
});