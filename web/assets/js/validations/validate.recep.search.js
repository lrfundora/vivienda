$(function () {

    //Cancel button
    $('#cancel').on('click', function (e) {
        $(location).attr('href', $('#cancel').attr('data-url'));
    });

    field = $('#simple_form_id');

    //Reseteando el campo carnet
    field.val('');

    //Eliminar simbolo del carnet
    $(field).on('keyup', function (e) {
        clearNumber(this.id);
    });

    //Eliminar simbolo y letras de un numero
    function clearNumber(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^0-9]/, ''));
    }

    //Validaciones de los formularios
    $("#formBuscarTramite").validate({
        rules: {
            'simple_form[id]': {
                required: true,
                number: true,
                minlength: 11,
                maxlength: 11
            }
        },
        messages: {
            'simple_form[id]': {
                required: "Introduzca el número de carnet.",
                number: "Este campo solo se admite números.",
                minlength: jQuery.validator.format("El campo debe tener {0} dígitos."),
                maxlength: jQuery.validator.format("El campo debe tener {0} dígitos.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

});
