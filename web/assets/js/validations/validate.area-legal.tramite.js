/**
 * Created by LARRY on 3/16/2016.
 */
$(function (e) {
    //Actions comentar
    $('button[class*="com"]').on('click', function (e) {
        $('#formCom input[id*="_id"]').val($(this).attr('data-id'));
        $('#formCom').submit();
    });
    //Actions Edit
    $('button[class*="edit"]').on('click', function (e) {
        $('#formEdit input[id*="_id"]').val($(this).attr('data-id'));
        $('#formEdit').submit();
    });
    //Validando Delete Model
    $('button[class*="end"]').on('click', function (e) {
        $('#formEnd input[id*="_id"]').val($(this).attr('data-id'));
    });
});