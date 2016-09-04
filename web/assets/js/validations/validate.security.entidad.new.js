$(function () {
    ////Validando Model
    $('button[class*="entidad-new"]').on('click', function (e) {
        $('input[id*="_nombre"]').val('');
        $('textarea[id*="_direccion"]').val('');
        $('input[id*="_telefono"]').val('');
        $('input[class*="error"]').removeClass('error').addClass('valid').attr('aria-invalid', 'false');
        $('textarea[class*="error"]').removeClass('error').addClass('valid').attr('aria-invalid', 'false');
        $('label[id*="-error"]').attr('style', 'display:none;')
    });

    $('button[class*="entidad-edit"]').on('click', function (e) {
        $('#formEdit input[id*="_id"]').val($(this).attr('data-id'));
        $('#formEdit').submit();
    });

    //Validando Delete Model
    $('button[class*="entidad-delete"]').on('click', function (e) {
        if (!$(this).attr('data-ext')) {
            msgText = 'Si elimina la entidad "' + $(this).attr('data-name') + '", que es una dependencia interna, implicará que se elimine este dato de los trabajadores de la Plantilla Laboral relacionados con el mismo.';
        } else {
            msgText = 'Si elimina la entidad "' + $(this).attr('data-name') + '", que es una dependencia externa, será eliminada de la guía telefónica';
        }
        $('#delete_text').text(msgText);
        $('input[id*="_id"]').val($(this).attr('data-item'));
    });

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
