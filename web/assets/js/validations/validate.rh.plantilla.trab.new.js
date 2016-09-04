$(function () {
    var opt = $('#fmc').attr('value');

    //Cancel button
    $('#cancel').on('click', function (e) {
        $(location).attr('href', $('#cancel').attr('data-url'));
    });

    //Select2
    clearSex();
    function clearSex() {
        $('#trabajador_sexo option:nth-child(1)').removeAttr('value');
    }

    $('#trabajador_sexo').select2({
        placeholder: "Seleccione un sexo",
        allowClear: true
    });

    $('#trabajador_entidad').select2({
        placeholder: "Seleccione una entidad",
        allowClear: true
    });

    $('#trabajador_contrato').select2({
        placeholder: "Seleccione un tipo de contrato",
        allowClear: true
    });

    $('#trabajador_cargo').select2({
        placeholder: "Seleccione un cargo",
        allowClear: true
    });

    $('#trabajador_categoria').select2({
        placeholder: "Seleccione una categoría",
        allowClear: true
    });

    $('#trabajador_escolaridad').select2({
        placeholder: "Seleccione un nivel escolar",
        allowClear: true
    });

    $('#trabajador_escalaSalarial').select2({
        placeholder: "Seleccione una escala",
        allowClear: true
    });

    $('#intSelect').val($('#intSelect').attr('data-selected').split(','));
    $('#intSelect').select2({
        placeholder: "Seleccione una o varias integraciones.",
        allowClear: true
    });
    $('#intSelect').on('change', function (e) {
        $('#integraciones').val($('#intSelect').val());
        if ($('#integraciones').val()) {
            $('#intSelect-error').text('');
            $('#intSelect-error').addClass('hidden');
        } else {
            $('#intSelect-error').text('Seleccione una o varias integraciones.');
            $('#intSelect-error').removeClass('hidden');
        }
    });

    checkIntegracion();
    $('#trabajador_sexo').on('change', function (e) {
        checkIntegracion();
    });
    function checkIntegracion() {
        intSelect = $('#intSelect');
        sexo = $('#trabajador_sexo');
        if (sexo.val() == '') {
            return;
        }
        if (sexo.val() == 1) {
            intSelect.find('option').each(function (e) {
                if ($(this).text().toLowerCase() == 'fmc') {
                    $(this).remove()
                }
            });
        } else {
            if (isNaN(opt)) {
                return;
            }
            list = [];
            fmc = ['FMC', $('<option id="fmc" value="' + opt + '">FMC</option>')];
            list.push(fmc);
            intSelect.find('option').each(function (e) {
                tmp = [$(this).text(), $(this)];
                list.push(tmp);
                $(this).remove()
            });
            list.sort();
            list.forEach(function (e) {
                intSelect.append(e[1]);
            });
        }
        intSelect.select2({
            placeholder: "Seleccione una o varias integraciones.",
            allowClear: true
        });
    }

    $('#trabajador_carnet').on('keyup', function (e) {
        clearNumber(this.id);
    });

    $('#trabajador_nombre').on('keyup', function (e) {
        clearString(this.id);
    });

    $('#trabajador_apellidos').on('keyup', function (e) {
        clearString(this.id);
    });

    //Eliminar simbolo y numeros de una cadena
    function clearString(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^a-zA-ZñÑáÁéÉíÍóÓúÚ ]/, ''));
    }

    //Eliminar simbolo y letras de un numero
    function clearNumber(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^0-9]/, ''));
    }

    //Validacion del formulario
    $("#form").validate({
        rules: {
            'intSelect': {
                required: true
            },
            'trabajador[carnet]': {
                required: true,
                number: true,
                minlength: 11,
                maxlength: 11
            },
            'trabajador[nombre]': {
                required: true,
                minlength: 3,
                maxlength: 25
            },
            'trabajador[apellidos]': {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            'trabajador[direccion]': {
                required: true,
                minlength: 5,
                maxlength: 255
            },
            'trabajador[sexo]': {
                required: true
            },
            'trabajador[entidad]': {
                required: true
            },
            'trabajador[cargo]': {
                required: true
            },
            'trabajador[categoria]': {
                required: true
            },
            'trabajador[escalaSalarial]': {
                required: true
            },
            'trabajador[contrato]': {
                required: true
            },
            'trabajador[escolaridad]': {
                required: true
            }
        },
        messages: {
            'intSelect': {
                required: "Seleccione una o varias integraciones"
            },
            'trabajador[carnet]': {
                required: "Introduzca el carnet de identidad.",
                number: "Este campo solo admmite números.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'trabajador[nombre]': {
                required: "Introduzca el nombre.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'trabajador[apellidos]': {
                required: "Introduzca los apellidos.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'trabajador[direccion]': {
                required: "Introduzca la dirección particular.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'trabajador[sexo]': {
                required: "Seleccione un sexo."
            },
            'trabajador[entidad]': {
                required: "Seleccione una entidad."
            },
            'trabajador[cargo]': {
                required: "Seleccione un cargo."
            },
            'trabajador[categoria]': {
                required: "Seleccione una categoría."
            },
            'trabajador[escalaSalarial]': {
                required: "Seleccione una escala salarial."
            },
            'trabajador[contrato]': {
                required: "Seleccione un tipo de contrato."
            },
            'trabajador[escolaridad]': {
                required: "Seleccione un nivel escolar."
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
});
