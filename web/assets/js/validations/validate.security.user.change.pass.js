$(function () {

    //Validaciones de los formularios
    $("#form").validate({
        rules: {
            'oldPass': {
                required: true,
                maxlength: 255
            },
            'usuario_pass[password][first]': {
                required: true,
                minlength: 5,
                maxlength: 255
            },
            'usuario_pass[password][second]': {
                required: true,
                equalTo: '#usuario_pass_password_first'
            }
        },
        messages: {
            'oldPass': {
                required: "Introduzca la contraseña actual.",
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'usuario_pass[password][first]': {
                required: "Introduzca la nueva contraseña.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'usuario_pass[password][second]': {
                required: "Repita la contraseña.",
                equalTo: "Las contraseñas no coinciden."
            }
        },
        submitHandler: function (form) {
            var oldPass = $('#oldPass').val();
            var newPass = $('#usuario_pass_password_first').val();
            if (oldPass == newPass) {
                alert('La contraseña actual es igual a la nueva contraseña.\nPor favor, introduzca una nueva contraseña diferente a la actual.');
            } else {
                form.submit();
            }
        }
    });
});
