$(function () {

    var link = $('a[class*="info"]');
    link.on('click', function (e) {
        $('input[id*="_id"]').val(link.attr('data-id'));
        $('#formInfo').submit();
    });

    var columna = $('select[id*="_columna"]');
    var coincide = $('select[id*="_coincidencia"]');
    var criterio = $('input[id*="_criterio"]');
    var fecha = $('.input-group.date');
    var error = $('#error');
    var completado =  $('input[type=checkbox]:checked').val();

    columna.select2({
        allowClear: false
    });
    columna.on('change', function (e) {
        reset();
    });

    coincide.select2({
        allowClear: false
    });

    criterio.on('focusin', function (e) {
        this.select();
    });
    criterio.on('keyup', function (e) {
        if (e.keyCode == 13) {
            if (validate()) {
                $('#form').submit();
            }
        }
        validate();
    });

    fecha.datepicker({
        startView: 1,
        orientation: "top",
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        format: "dd/mm/yyyy"
    });
    fecha.on('change', function (e) {
        criterio.val(fecha.val());
    });

    $('input[type=checkbox]').on('click', function (e) {
        completado = this.checked;
        validate();
    });

    $('#send').on('click', function (e) {
        if (validate()) {
            $('#form').submit();
        }
    });

    reset();

    function reset() {
        if (isNaN(completado)) {
            completado = 0;
        }
        error.addClass('hidden');
        criterioChange();
    }

    function criterioChange() {
        if (columna.val() == 'carnet') {
            if (isNaN(criterio.val())) {
                criterio.val('');
            }
            fecha.addClass('hidden');
            criterio.removeClass('hidden');
        }
        else if (columna.val() == 'fechaEntrada' || columna.val() == 'fechaVencimiento') {
            criterio.val(fecha.val());
            criterio.addClass('hidden');
            fecha.removeClass('hidden');
        } else {
            fecha.addClass('hidden');
            criterio.removeClass('hidden');
        }
    }

    function validate() {
        if (criterio.val() == '' && !completado) {
            error.removeClass('hidden');
            error.text('Este campo no puede quedar vacío.');
            return false;
        } else {
            error.addClass('hidden');
        }
        if (columna.val() == 'carnet') {
            clearNumber(criterio);
            if (criterio.val().length > 11) {
                error.removeClass('hidden');
                error.text('El número de carnet tiene como máximo 11 dígitos.');
                return false;
            } else {
                error.addClass('hidden');
            }
        }
        if (columna.val() != 'carnet' && columna.val() != 'fechaEntrada' && columna.val() != 'fechaVencimiento') {
            if (criterio.val().length > 250) {
                error.removeClass('hidden');
                error.text('Este campo puede tener un máximo de 255 catacteres.');
                return false;
            } else {
                error.addClass('hidden');
            }
        }

        return true;
    }

    function clearNumber(id) {
        id.val(id.val().replace(/[^0-9]/, ''));
    }

});
