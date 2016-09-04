/**
 * Created by LARRY on 3/16/2016.
 */
$(function (e) {

    //Cancel button
    $('#cancel').on('click', function (e) { $(location).attr('href',$('#cancel').attr('data-url')); });

    //Validaciones de los formularios
    $("#form").validate({
        rules: {
            'tramite[comentario]': {
                required: true,
                minlength: 5,
                maxlength: 1000
            },
            'tramite[isCompletado]': {
                required: false
            }
        },
        messages: {
            'tramite[comentario]': {
                required: "Introduzca el comentario del trámite.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });


});