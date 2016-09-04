/**
 * Created by LARRY on 3/16/2016.
 */
$(function (e) {

    //Cancel button
    $('#cancel').on('click', function (e) { $(location).attr('href',$('#cancel').attr('data-url')); });

    $('#cliente_carnet').on('keyup', function (e) {
        clearNumber(this.id);
    });

    $('#cliente_nombre').on('keyup', function (e) {
        clearString(this.id);
    });
    $('#cliente_apellidos').on('keyup', function (e) {
        clearString(this.id);
    });

    //Eliminar simbolo y numeros de una cadena
    function clearString(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^a-zA-ZñÑáÁéÉíÍóÓúÚ \.]/, ''));
    }

    //Eliminar simbolo y letras de un numero
    function clearNumber(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^0-9]/, ''));
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
            }
        },
        messages: {
            'cliente_arrendamiento[carnet]': {
                required: "Introduzca el carnet de identidad del arrendatario.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_arrendamiento[nombre]': {
                required: "Introduzca el nombre del arrendatario.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_arrendamiento[apellidos]': {
                required: "Introduzca los apellidos del arrendatario.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'cliente_arrendamiento[direccion]': {
                required: "Introduzca la dirección del arrendatario.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
});