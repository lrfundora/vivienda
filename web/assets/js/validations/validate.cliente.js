$(function () {

    //Form Info
    $('button[class*="info"]').on('click', function (e) {
        $('#formInfo input[id*="_id"]').val($(this).attr('data-id'));
        $('#formInfo').submit();
    });

    //Form nuevo tramite
    $('button[class*="new"]').on('click', function (e) {
        $('#formNewTramite input[id*="_id"]').val($(this).attr('data-id'));
        $('#formNewTramite').submit();
    });
});
