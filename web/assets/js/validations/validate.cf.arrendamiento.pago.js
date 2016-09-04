/**
 * Created by LARRY on 3/16/2016.
 */
$(function (e) {
    //Cancel button
    $('#cancel').on('click', function (e) {
        $(location).attr('href', $('#cancel').attr('data-url'));
    });
});