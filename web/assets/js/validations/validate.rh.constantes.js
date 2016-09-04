$(function () {

    //Validando Add Model
    $('a[class*="add"]').on('click', function (e) {
        switch ($(this).attr('data-tab')) {
            case '1':
                idForm = 'formCargo';
                idField = 'cargo_nombre';
                mTitle = 'AGREGAR NUEVO CARGO';
                mText = 'Esta ventana le permite agregar un nuevo cargo de forma simple, solo tiene que introducir el nombre del cargo y hacer clic en guardar.';
                break;
            case '2':
                idForm = 'formCategoria';
                idField = 'categoria_nombre';
                mTitle = 'AGREGAR NUEVA CATEGORÍA OCUPACIONAL';
                mText = 'Esta ventana le permite agregar una nueva categoría ocupacional de forma simple, solo tiene que introducir el nombre de la categoría y hacer clic en guardar.';
                break;
            case '3':
                idForm = 'formContrato';
                idField = 'contrato_tipo';
                mTitle = 'AGREGAR NUEVO TIPO DE CONTRATO';
                mText = 'Esta ventana le permite agregar un nuevo tipo de contrato de forma simple, solo tiene que introducir el nombre del tipo de contrato y hacer clic en guardar.';
                break;
            case '4':
                idForm = 'formEscalaSalarial';
                idField = 'escala_salarial_escala';
                idField2 = 'escala_salarial_salario';
                mTitle = 'AGREGAR NUEVA ESCALA SALARIAL';
                mText = 'Esta ventana le permite agregar una nueva escala salarial de forma simple, solo tiene que introducir el nombre de la escala, el salario de la misma y hacer clic en guardar.';
                break;
            case '5':
                idForm = 'formEscolaridad';
                idField = 'escolaridad_nivel';
                mTitle = 'AGREGAR NUEVO NIVEL ESCOLAR';
                mText = 'Esta ventana le permite agregar un nuevo nivel escolar de forma simple, solo tiene que introducir el nombre del nivel escolar y hacer clic en guardar.';
                break;
            case '6':
                idForm = 'formIntegracion';
                idField = 'integracion_nombre';
                mTitle = 'AGREGAR NUEVA INTEGRACIÓN REVOLUCIONARIA';
                mText = 'Esta ventana le permite agregar una nueva integración revolucionaria de forma simple, solo tiene que introducir el nombre de la integración y hacer clic en guardar.';
                break;
            case '7':
                idForm = 'formEntidad';
                idField = 'entidad_nombre';
                idField2 = 'entidad_direccion';
                idField3 = 'entidad_telefono';
                mTitle = 'EDITAR ENTIDAD';
                mText = 'Esta ventana le permite agregar una entidad de forma simple, solo tiene que introducir los datos solicitados y hacer clic en guardar.';
                break;

        }
        mButton = 'Guardar';
        $('#mTitle' + $(this).attr('data-tab')).text(mTitle);
        if ($('#mTitle' + $(this).attr('data-tab')).hasClass('text-success')) {
            $('#mTitle' + $(this).attr('data-tab')).removeClass('text-success').addClass('text-green');
        }
        $('#mText' + $(this).attr('data-tab')).text(mText);
        $('#mButton' + $(this).attr('data-tab')).text('').append($('<i class="fa fa-save"></i>')).append(' ' + mButton);
        if ($('#mButton' + $(this).attr('data-tab')).hasClass('btn-success')) {
            $('#mButton' + $(this).attr('data-tab')).removeClass('btn-success').addClass('btn-primary');
        }
        $('#' + idForm).attr('action', $(this).attr('data-action'));
        $('#' + idField).val('');
        if ($(this).attr('data-tab') == '4') {
            $('#' + idField2).val('');
        }
        if ($(this).attr('data-tab') == '7') {
            $('#' + idField2).val('');
            $('#' + idField3).val('');
        }
        hideMsgError();
    });

    //Validando Edit Model
    $('a[class*="edit"]').on('click', function (e) {
        switch ($(this).attr('data-tab')) {
            case '1':
                idForm = 'formCargo';
                idField = 'cargo_nombre';
                mTitle = 'EDITAR CARGO';
                mText = 'Esta ventana le permite editar un cargo de forma simple, solo tiene que reemplazar el nombre del cargo actual por el nuevo cargo y hacer clic en actualizar.';
                break;
            case '2':
                idForm = 'formCategoria';
                idField = 'categoria_nombre';
                mTitle = 'EDITAR CATEGORÍA OCUPACIONAL';
                mText = 'Esta ventana le permite editar una categoría ocupacional de forma simple, solo tiene que reemplazar el nombre de la categoría actual por la nueva categoría y hacer clic en actualizar.';
                break;
            case '3':
                idForm = 'formContrato';
                idField = 'contrato_tipo';
                mTitle = 'EDITAR TIPO DE CONTRATO';
                mText = 'Esta ventana le permite editar un tipo de contrato de forma simple, solo tiene que reemplazar el nombre del tipo de contrato actual por el nuevo tipo de contrato y hacer clic en actualizar.';
                break;
            case '4':
                idForm = 'formEscalaSalarial';
                idField = 'escala_salarial_escala';
                idField2 = 'escala_salarial_salario';
                mTitle = 'EDITAR ESCALA SALARIAL';
                mText = 'Esta ventana le permite editar una escala salarial de forma simple, solo tiene que reemplazar el nombre de la escala actual por la nueva escala y hacer clic en actualizar.';
                break;
            case '5':
                idForm = 'formEscolaridad';
                idField = 'escolaridad_nivel';
                mTitle = 'EDITAR NIVEL ESCOLAR';
                mText = 'Esta ventana le permite editar un nivel escolar de forma simple, solo tiene que reemplazar el nombre del nivel escolar por el nuevo nivel escolar y hacer clic en actualizar.';
                break;
            case '6':
                idForm = 'formIntegracion';
                idField = 'integracion_nombre';
                mTitle = 'EDITAR INTEGRACIÓN REVOLUCIONARIA';
                mText = 'Esta ventana le permite editar una integración revolucionaria de forma simple, solo tiene que reemplazar el nombre de la integración actual por la nueva integración y hacer clic en actualizar.';
                break;
            case '7':
                idForm = 'formEntidad';
                idField = 'entidad_nombre';
                idField2 = 'entidad_direccion';
                idField3 = 'entidad_telefono';
                mTitle = 'EDITAR ENTIDAD';
                mText = 'Esta ventana le permite editar una entidad de forma simple, solo tiene que reemplazar los datos actuales por los nuevos y hacer clic en actualizar.';
                break;
        }
        mButton = 'Actualizar';
        $('#mTitle' + $(this).attr('data-tab')).text(mTitle);
        if ($('#mTitle' + $(this).attr('data-tab')).hasClass('text-green')) {
            $('#mTitle' + $(this).attr('data-tab')).removeClass('text-green').addClass('text-success');
        }
        $('#mText' + $(this).attr('data-tab')).text(mText);
        $('#mButton' + $(this).attr('data-tab')).text('').append($('<i class="fa fa-refresh"></i>')).append(' ' + mButton);
        if ($('#mButton' + $(this).attr('data-tab')).hasClass('btn-primary')) {
            $('#mButton' + $(this).attr('data-tab')).removeClass('btn-primary').addClass('btn-success');
        }
        $('#' + idForm).attr('action', $(this).attr('data-action'));
        $('#' + idField).val($(this).attr('data-input'));
        if ($(this).attr('data-tab') == '4') {
            $('#' + idField2).val($(this).attr('data-input2'));
        }
        if ($(this).attr('data-tab') == '7') {
            $('#' + idField2).val($(this).attr('data-input2'));
            $('#' + idField3).val($(this).attr('data-input3'));
        }
        hideMsgError();
    });

    function hideMsgError() {
        $('input[class*="error"]').removeClass('error').addClass('valid').attr('aria-invalid','false');
        $('textarea[class*="error"]').removeClass('error').addClass('valid').attr('aria-invalid','false');
        $('label[id*="-error"]').attr('style','display:none;')
    }

    //Validando Delete Model
    $('a[class*="delete"]').on('click', function (e) {

        msgText = '';
        switch ($(this).attr('data-tab')) {
            case '1':
                msgText = 'Si elimina el cargo "' + $(this).attr('data-name') + '" implicará que se elimine este dato de los trabajadores de la Plantilla Laboral relacionados con el mismo.';
                break;
            case '2':
                msgText = 'Si elimina la categoría ocupacional "' + $(this).attr('data-name') + '" implicará que se elimine este dato de los trabajadores de la Plantilla Laboral relacionados con el mismo.';
                break;
            case '3':
                msgText = 'Si elimina el tipo de contrato "' + $(this).attr('data-name') + '" implicará que se elimine este dato de los trabajadores de la Plantilla Laboral relacionados con el mismo.';
                break;
            case '4':
                msgText = 'Si elimina la escala salarial "' + $(this).attr('data-name') + '" implicará que se elimine este dato de los trabajadores de la Plantilla Laboral relacionados con el mismo.';
                break;
            case '5':
                msgText = 'Si elimina el nivel escolar "' + $(this).attr('data-name') + '" implicará que se elimine este dato de los trabajadores de la Plantilla Laboral relacionados con el mismo.';
                break;
            case '6':
                msgText = 'Si elimina la integración revolucionaria "' + $(this).attr('data-name') + '" implicará que se elimine este dato de los trabajadores de la Plantilla Laboral relacionados con el mismo.';
                break;
            case '7':
                msgText = 'Si elimina la entidad "' + $(this).attr('data-name') + '" implicará que se elimine este dato de los trabajadores de la Plantilla Laboral relacionados con el mismo.';
                break;
            default:
                break;
        }

        $('#delete_text').text(msgText + ' Se recomienda usar la opción de Editar y no Eliminar.');
        $('#formDelete').attr('action', $(this).attr('data-action'));
        $('input[id*="_id"]').val($(this).attr('data-item'));
    });

    //Formatendo el campo salario
    $('input[id*="_salario"]').on('focusin', function (e) {
        if (!isNaN($('#' + this.id).val() * 1) && ($('#' + this.id).val() * 1) != 0) {
            $('#' + this.id).select();
        }
    });
    $('input[id*="_salario"]').on('focusout', function (e) {
        if (!isNaN($('#' + this.id).val() * 1) && ($('#' + this.id).val() * 1) != 0) {
            $('#' + this.id).val(($('#' + this.id).val() * 1).toFixed(2));
        } else {
            $('#' + this.id).val('');
        }
    });

    //Validaciones de los formularios
    $("#formCargo").validate({
        rules: {
            'cargo[nombre]': {
                required: true,
                minlength: 3,
                maxlength: 50
            }
        },
        messages: {
            'cargo[nombre]': {
                required: "Introduzca el nombre del cargo.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#formCategoria").validate({
        rules: {
            'categoria[nombre]': {
                required: true,
                minlength: 3,
                maxlength: 50
            }
        },
        messages: {
            'categoria[nombre]': {
                required: "Introduzca el nombre de la categoría ocupacinal.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#formContrato").validate({
        rules: {
            'contrato[tipo]': {
                required: true,
                minlength: 3,
                maxlength: 50
            }
        },
        messages: {
            'contrato[tipo]': {
                required: "Introduzca el nombre del tipo de contrato.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#formEscalaSalarial").validate({
        rules: {
            'escala_salarial[escala]': {
                required: true,
                minlength: 1,
                maxlength: 50
            },
            'escala_salarial[salario]': {
                required: true,
                number: true,
                minlength: 1,
                maxlength: 10
            }
        },
        messages: {
            'escala_salarial[escala]': {
                required: "Introduzca el nombre de la escala salarial.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'escala_salarial[salario]': {
                required: "Introduzca el salario para la escala.",
                number: "Este campo solo se admite números.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} dígitos."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} dígitos inclyendo 2 decimales.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#formEscolaridad").validate({
        rules: {
            'escolaridad[nivel]': {
                required: true,
                minlength: 3,
                maxlength: 50
            }
        },
        messages: {
            'escolaridad[nivel]': {
                required: "Introduzca el nombre del nivel escolar.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#formIntegracion").validate({
        rules: {
            'integracion[nombre]': {
                required: true,
                minlength: 1,
                maxlength: 50
            }
        },
        messages: {
            'integracion[nombre]': {
                required: "Introduzca el nombre de la integración revolucionaria.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#formEntidad").validate({
        rules: {
            'entidad[nombre]': {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            'entidad[direccion]': {
                required: true,
                minlength: 3,
                maxlength: 255
            },
            'entidad[telefono]': {
                required: true,
                minlength: 6,
                maxlength: 20
            }
        },
        messages: {
            'entidad[nombre]': {
                required: "Introduzca el nombre de la entidad.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'entidad[direccion]': {
                required: "Introduzca la dirección de la entidad.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            },
            'entidad[telefono]': {
                required: "Introduzca el teléfono de la entidad.",
                minlength: jQuery.validator.format("El campo debe tener al menos {0} caracteres."),
                maxlength: jQuery.validator.format("El campo puede tener como máximo {0} caracteres.")
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $('#cargo_nombre').on('keyup', function (e) {
        clearString(this.id);
    });
    $('#categoria_nombre').on('keyup', function (e) {
        clearString(this.id);
    });
    $('#contrato_tipo').on('keyup', function (e) {
        clearString(this.id);
    });
    $('#integracion_nombre').on('keyup', function (e) {
        clearString(this.id);
    });
    $('#entidad_nombre').on('keyup', function (e) {
        clearString(this.id);
    });
    $('#entidad_telefono').on('keyup', function (e) {
        clearPhone(this.id);
    });
    //Eliminar simbolo y numeros de una cadena
    function clearString(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^a-zA-ZñÑáÁéÉíÍóÓúÚ ]/, ''));
    }
    //Eliminar simbolo y letras de una cadena
    function clearPhone(id) {
        id = '#' + id;
        $(id).val($(id).val().replace(/[^0-9\+ -]/, ''));
    }
});
