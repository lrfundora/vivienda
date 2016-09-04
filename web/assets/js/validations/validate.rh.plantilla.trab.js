$(function () {

    //Form Trabajador Info
    $('a[class*="btn-info"]').on('click', function(e){
        $('input[id*="_id"]').val($(this).attr('data-item'));
        $('#formInfo').submit();
    });

    //Form Trabajador Edit
    $('a[class*="edit"]').on('click', function(e){
        $('input[id*="_id"]').val($(this).attr('data-item'));
        $('#formEdit').submit();
    });

    //Validando Dar Baja Model
    $('a[class*="delete"]').on('click', function (e) {
        hideMsgError();
        msgText = 'Si da baja al trabajador "' + $(this).attr('data-name') + '" implicará que se elimine de la plantilla laboral y si cuenta con un usuario del sistema automáticamente se eliminará, impidiendo que vuelva a iniciar sesión en  el sistema.';
        $('#delete_text').text(msgText);
        $('input[id*="_id"]').val($(this).attr('data-item'));
    });

    $("#formBaja").validate({
        rules: {
            'trabajador_baja[motivoBaja]': {
                required: true,
                minlength: 5,
                maxlength: 1000
            }
        },
        messages: {
            'trabajador_baja[motivoBaja]': {
                required: "Especifique el motivo de  la baja.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    function hideMsgError() {
        $('input[class*="error"]').removeClass('error').addClass('valid').attr('aria-invalid','false');
        $('textarea[class*="error"]').removeClass('error').addClass('valid').attr('aria-invalid','false');
        $('label[id*="-error"]').attr('style','display:none;')
    }
});
