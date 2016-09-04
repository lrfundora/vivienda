/**
 * Created by LARRY on 3/16/2016.
 */
$(function (e) {

    //Validando Delete Model
    $('button[class*="user-delete"]').on('click', function (e) {
        msgText = 'Si elimina el usuario "' + $(this).attr('data-name') + '" implicará que dicho usuario no podrá iniciar sesión en el sistema aunque no se eliminará al trabajador de la plantilla labora.';
        $('#delete_text').text(msgText);
        $('input[id*="_id"]').val($(this).attr('data-item'));
    });

    //Actions Info
    $('button[class*="user-info"]').on('click', function (e) {
        $('input[id*="_id"]').val($(this).attr('data-item'));
        $('#formInfo').submit();
    });

    //Actions Edit
    $('button[class*="user-edit"]').on('click', function (e) {
        $('input[id*="_id"]').val($(this).attr('data-item'));
        $('#formEdit').submit();
    });

});