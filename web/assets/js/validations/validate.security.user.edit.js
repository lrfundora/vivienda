$(function () {
//Cancel button
    $('#cancel').on('click', function (e) {
        $(location).attr('href', $('#cancel').attr('data-url'));
    });

    //Select2
    $('#idTrabajador').val($('#idTrabajador').attr('data-selected'));
    $('#idTrabajador').select2({
        placeholder: "Seleccione un trabajador",
        allowClear: true
    });
    $('#idTrabajador').on('change', function (e) {
        if ($('#idTrabajador').val()) {
            $('#idTrabajador-error').text('');
            $('#idTrabajador-error').addClass('hidden');
        } else {
            $('#idTrabajador-error').text('Seleccione un trabajador.');
            $('#idTrabajador-error').removeClass('hidden');
        }
    });

    $('#rolesSelect').val($('#rolesSelect').attr('data-selected').split(','));
    $('#rolesSelect').select2({
        placeholder: "Seleccione uno o varios roles",
        allowClear: true
    });
    $('#rolesSelect').on('change', function (e) {
        $('#roles').val($('#rolesSelect').val());
        if ($('#roles').val()) {
            $('#rolesSelect-error').text('');
            $('#rolesSelect-error').addClass('hidden');
        } else {
            $('#rolesSelect-error').text('Seleccione uno o varios roles para el usuario.');
            $('#rolesSelect-error').removeClass('hidden');
        }
    });

    $('#usuario_info_user').on('keyup', function (e) {
        clearString(this.id);
    });

    //Eliminar simbolo de una cadena
    function clearString(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^a-zA-Z0-9\.]/, '').toLowerCase());
    }

    //Validaciones de los formularios
    $("#formInfo").validate({
        rules: {
            'idTrabajador': {
                required: true
            },
            'usuario_info[user]': {
                required: true,
                minlength: 3,
                maxlength: 30
            },
            'rolesSelect': {
                required: true
            },
            'usuario_info[isActive]': {
                required: false
            }
        },
        messages: {
            'idTrabajador': {
                required: "Seleccione un trabajador."
            },
            'usuario_info[user]': {
                required: "Introduzca el nombre de usuario.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'rolesSelect': {
                required: "Seleccione uno o varios roles para el usuario."
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#formPass").validate({
        rules: {
            'usuario_pass[password][first]': {
                required: true,
                minlength: 5,
                maxlength: 255
            }
        },
        messages: {
            'usuario_pass[password][first]': {
                required: "Introduzca la contraseña.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            }
        },
        submitHandler: function (form) {
            var pass = $('#formPass input[id*="_password_first"]').val();
            $('#formPass input[id*="_password_second"]').val(pass);
            form.submit();
        }
    });
});
