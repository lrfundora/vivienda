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
    var cargo = $('#cargo');
    var div_cargo = $('#div-cargo');
    var categoria = $('#categoria');
    var div_categoria = $('#div-categoria');
    var contrato = $('#contrato');
    var div_contrato = $('#div-contrato');
    var entidad = $('#entidad');
    var div_entidad = $('#div-entidad');
    var escala = $('#escala');
    var div_escala = $('#div-escala');
    var escolaridad = $('#escolaridad');
    var div_escolaridad = $('#div-escolaridad');
    var integracion = $('#integracion');
    var div_integracion = $('#div-integracion');
    var error = $('#error');
    var baja = $('input[id*="_baja"]:checked').val();

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
    cargo.select2({
        allowClear: false
    });
    cargo.on('change', function (e) {
        criterio.val(cargo.val());
    });

    categoria.select2({
        allowClear: false
    });
    categoria.on('change', function (e) {
        criterio.val(categoria.val());
    });

    contrato.select2({
        allowClear: false
    });
    contrato.on('change', function (e) {
        criterio.val(contrato.val());
    });

    entidad.select2({
        allowClear: false
    });
    entidad.on('change', function (e) {
        criterio.val(entidad.val());
    });

    escala.select2({
        allowClear: false
    });
    escala.on('change', function (e) {
        criterio.val(escala.val());
    });

    escolaridad.select2({
        allowClear: false
    });
    escolaridad.on('change', function (e) {
        criterio.val(escolaridad.val());
    });

    integracion.select2({
        allowClear: false
    });
    integracion.on('change', function (e) {
        criterio.val(integracion.val());
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

    $('input[id*="_baja"]').on('click', function (e) {
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
            div_cargo.addClass('hidden');
            div_categoria.addClass('hidden');
            div_contrato.addClass('hidden');
            div_entidad.addClass('hidden');
            div_escala.addClass('hidden');
            div_escolaridad.addClass('hidden');
            div_integracion.addClass('hidden');
            fecha.addClass('hidden');
            criterio.removeClass('hidden');
        }
        else if (columna.val() == 'cargo') {
            criterio.val(cargo.val());
            div_categoria.addClass('hidden');
            div_contrato.addClass('hidden');
            div_entidad.addClass('hidden');
            div_escala.addClass('hidden');
            div_escolaridad.addClass('hidden');
            div_integracion.addClass('hidden');
            fecha.addClass('hidden');
            fecha.addClass('hidden');
            criterio.addClass('hidden');
            div_cargo.removeClass('hidden');
        }
        else if (columna.val() == 'categoria') {
            criterio.val(categoria.val());
            div_cargo.addClass('hidden');
            div_contrato.addClass('hidden');
            div_entidad.addClass('hidden');
            div_escala.addClass('hidden');
            div_escolaridad.addClass('hidden');
            div_integracion.addClass('hidden');
            fecha.addClass('hidden');
            fecha.addClass('hidden');
            criterio.addClass('hidden');
            div_categoria.removeClass('hidden');
        }
        else if (columna.val() == 'contrato') {
            criterio.val(contrato.val());
            div_cargo.addClass('hidden');
            div_categoria.addClass('hidden');
            div_entidad.addClass('hidden');
            div_escala.addClass('hidden');
            div_escolaridad.addClass('hidden');
            div_integracion.addClass('hidden');
            fecha.addClass('hidden');
            fecha.addClass('hidden');
            criterio.addClass('hidden');
            div_contrato.removeClass('hidden');
        }
        else if (columna.val() == 'entidad') {
            criterio.val(entidad.val());
            div_cargo.addClass('hidden');
            div_categoria.addClass('hidden');
            div_contrato.addClass('hidden');
            div_escala.addClass('hidden');
            div_escolaridad.addClass('hidden');
            div_integracion.addClass('hidden');
            fecha.addClass('hidden');
            fecha.addClass('hidden');
            criterio.addClass('hidden');
            div_entidad.removeClass('hidden');
        }
        else if (columna.val() == 'escalaSalarial') {
            criterio.val(escala.val());
            div_cargo.addClass('hidden');
            div_categoria.addClass('hidden');
            div_contrato.addClass('hidden');
            div_entidad.addClass('hidden');
            div_escolaridad.addClass('hidden');
            div_integracion.addClass('hidden');
            fecha.addClass('hidden');
            fecha.addClass('hidden');
            criterio.addClass('hidden');
            div_escala.removeClass('hidden');
        }
        else if (columna.val() == 'escolaridad') {
            criterio.val(escolaridad.val());
            div_cargo.addClass('hidden');
            div_categoria.addClass('hidden');
            div_contrato.addClass('hidden');
            div_entidad.addClass('hidden');
            div_escala.addClass('hidden');
            div_integracion.addClass('hidden');
            fecha.addClass('hidden');
            fecha.addClass('hidden');
            criterio.addClass('hidden');
            div_escolaridad.removeClass('hidden');
        }
        else if (columna.val() == 'integracion') {
            div_cargo.addClass('hidden');
            div_categoria.addClass('hidden');
            div_contrato.addClass('hidden');
            div_entidad.addClass('hidden');
            div_escala.addClass('hidden');
            div_escolaridad.addClass('hidden');
            fecha.addClass('hidden');
            fecha.addClass('hidden');
            criterio.addClass('hidden');
            div_integracion.removeClass('hidden');
        }
        else if (columna.val() == 'fechaAlta') {
            criterio.val(fecha.val());
            div_cargo.addClass('hidden');
            div_categoria.addClass('hidden');
            div_contrato.addClass('hidden');
            div_entidad.addClass('hidden');
            div_escala.addClass('hidden');
            div_escolaridad.addClass('hidden');
            div_integracion.addClass('hidden');
            fecha.addClass('hidden');
            criterio.addClass('hidden');
            fecha.removeClass('hidden');
        } else {
            div_cargo.addClass('hidden');
            div_categoria.addClass('hidden');
            div_contrato.addClass('hidden');
            div_entidad.addClass('hidden');
            div_escala.addClass('hidden');
            div_escolaridad.addClass('hidden');
            div_integracion.addClass('hidden');
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
        if (columna.val() != 'carnet' && columna.val() != 'fechaAlta') {
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
