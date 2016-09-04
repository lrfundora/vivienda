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
    var checker = $('input[type=checkbox]');
    var baja = $('input[id*="_baja"]:checked').val();
    var estado = $('#estado');
    var div_estado = $('#div-estado');

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
    criterio.on('focusout', function (e) {
        if(columna.val()=='valor'){
            formatPrice(criterio);
        }
    });
    criterio.on('keyup', function (e) {
        if (e.keyCode == 13) {
            formatPrice(criterio);
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

    estado.select2({
        allowClear: false
    });
    estado.on('change', function (e) {
        criterio.val(estado.val());
    });

    checker.on('click', function (e) {
        baja = this.checked;
        validate();
    });

    $('#send').on('click', function (e) {
        if (validate()) {
            $('#form').submit();
        }
    });

    reset();

    function reset() {
        if (isNaN(baja)) {
            baja = 0;
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
            div_estado.addClass('hidden');
            criterio.removeClass('hidden');
        }
        else if (columna.val() == 'fechaContrato') {
            criterio.val(fecha.val());
            criterio.addClass('hidden');
            div_estado.addClass('hidden');
            fecha.removeClass('hidden');

        } else if (columna.val() == 'anoConstruccion') {
            if (isNaN(criterio.val())) {
                criterio.val('');
            }
            div_estado.addClass('hidden');
            fecha.addClass('hidden');
            criterio.removeClass('hidden');
        }
        else if (columna.val() == 'estado') {
            criterio.val(estado.val());
            criterio.addClass('hidden');
            fecha.addClass('hidden');
            div_estado.removeClass('hidden');
        } else {
            div_estado.addClass('hidden');
            fecha.addClass('hidden');
            criterio.removeClass('hidden');
        }
    }

    function validate() {
        if (criterio.val() == '' && !baja) {
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
        if (columna.val() == 'anoConstruccion') {
            clearNumber(criterio);
            if (criterio.val().length > 4) {
                error.removeClass('hidden');
                error.text('El año de construcción puede tener como máximo 4 dígitos.');
                return false;
            } else {
                error.addClass('hidden');
            }
        }
        if (columna.val() == 'valor') {
            clearPrice(criterio);
            if (isNaN(criterio.val()*1)) {
                error.removeClass('hidden');
                error.text('El precio solo puede contener dígitos. ej: 25236.89');
                return false;
            } else {
                error.addClass('hidden');
            }
        }
        if (columna.val() != 'carnet' && columna.val() != 'fechaContrato') {
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

    function clearPrice(id) {
        id.val(id.val().replace(/[^0-9.]/, ''));
    }

    function formatPrice(id) {
        if (!isNaN(id.val() * 1) && (id.val() * 1) != 0) {
            id.val((id.val() * 1).toFixed(2));
        } else {
            id.val('0.00');
        }
    }

});
