$(function () {
    //Form Cliente agregar
    $('button[class*="add"]').on('click', function (e) {
        $('#formArr input[id*="_id"]').val($(this).attr('data-id'));
        $('#formArr').submit();
    });

    //Form Cliente Info
    $('button[class*="info"]').on('click', function(e){
        $('input[id*="_id"]').val($(this).attr('data-id'));
        $('#formInfo').submit();
    });

    //Form Cliente Edit
    $('button[class*="edit"]').on('click', function(e){
        $('input[id*="_id"]').val($(this).attr('data-id'));
        $('#formEdit').submit();
    });

    //Validando Dar Baja Model
    $('button[class*="end"]').on('click', function (e) {
        hideMsgError();
        msgText = 'Si da baja al arrendamiento con número de registro "' + $(this).attr('data-name') + '" implicará que dicho arrendamiento deja de pagar impuesto.';
        $('#delete_text').text(msgText);
        $('input[id*="_id"]').val($(this).attr('data-item'));
    });

    $("#formBaja").validate({
        rules: {
            'arrendamiento_baja[comentario]': {
                required: true,
                minlength: 5,
                maxlength: 1000
            }
        },
        messages: {
            'arrendamiento_baja[comentario]': {
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
