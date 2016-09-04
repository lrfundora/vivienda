$(function () {
    //Cancel button
    $('#cancel').on('click', function (e) { $(location).attr('href',$('#cancel').attr('data-url')); });

    $('#entidad_nombre').on('keyup', function (e) {
        clearString(this.id);
    });
    $('#entidad_telefono').on('keyup', function (e) {
        clearPhone(this.id);
    });
    //Eliminar simbolo y numeros de una cadena
    function clearString(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^a-zA-ZñÑáÁéÉíÍóÓúÚ ]/, ''));
    }
    //Eliminar simbolo y letras de una cadena
    function clearPhone(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^0-9\+ -]/, ''));
    }

    //Validaciones de los formularios
    $("#formEntidad").validate({
        rules: {
            'entidad[nombre]': {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            'entidad[direccion]': {
                required: true,
                minlength: 3,
                maxlength: 255
            },
            'entidad[telefono]': {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            'entidad[externa]': {
                required: false
            },
            'id': {
                required: false
            }
        },
        messages: {
            'entidad[nombre]': {
                required: "Introduzca el nombre de la entidad.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'entidad[direccion]': {
                required: "Introduzca la dirección de la entidad.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'entidad[telefono]': {
                required: "Introduzca el teléfono de la entidad.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

});
