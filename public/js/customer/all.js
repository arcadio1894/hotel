
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});
function saveCustomer() {
    $("#guardar").prop("disabled", true);
    let url = $('#id').val() ? '/home/clientes/editar/' + $('#id').val() : '/home/clientes/crear';

    $.ajax({
        url: url,
        method: 'POST',
        data: $('#roomTypeForm').serialize(),
        success: function (response) {
            $("#roomTypeModal").modal("hide");
            $("#guardar").prop("disabled", false);
            Toast.fire({
                icon: 'success',
                title: response.success,
            }).then(function () {
                window.location.href = "/home/clientes/listar";
            });
        },
        error: function (xhr) {
            $("#guardar").prop("disabled", false);
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = "Errores de validación:<br>";
        
                for (let field in errors) {
                    errorMessage += `- ${errors[field][0]}<br>`;
                }
        
                Toast.fire({
                    icon: 'error',
                    title: errorMessage
                });
            } else if (xhr.responseJSON && xhr.responseJSON.customError) {
                let customErrorMessage = xhr.responseJSON.error;
                Toast.fire({
                    icon: 'error',
                    title: customErrorMessage
                })
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Hubo un error al procesar la solicitud'
                }).then(function () {
                    window.location.href = "/home/clientes/listar";
                });
            }
        }
    });
}

function cleanCustomer(){
    $('#id').val('');
    $('#document_type').val('');

    $('#document_type option:not(:selected)').prop('disabled', false);
    $('#document_type option:selected').prop('disabled', false);
    $('#document_type option[value="RUC"]').show();

    $('#document').val('');
    $('#name').val('');
    $('#lastname').val('');
    $('#phone').val('');
    $('#email').val('');
    $('#birth').val('');
    $('#address').val('');

    $('#roomTypeModal').modal('show');
}

function updateCustomer(btn){
    $('#id').val($(btn).data('id'));
    $('#document_type').val($(btn).data('document_type'));
    $('#document').val($(btn).data('document'));
    $('#name').val($(btn).data('name'));
    $('#lastname').val($(btn).data('lastname'));
    console.log($(btn).data('id'));
    console.log($(btn).data('document_type'));
    console.log($(btn).data('document'));
    console.log($(btn).data('name'));
    console.log($(btn).data('lastname'));
    console.log($(btn).data('phone'));
    console.log($(btn).data('email'));
    console.log($(btn).data('birth'));
    console.log($(btn).data('address'));

    $('#phone').val($(btn).data('phone'));
    $('#email').val($(btn).data('email'));
    $('#birth').val(moment($(btn).data('birth'), 'DD-MM-YYYY').format('YYYY-MM-DD'));
    $('#address').val($(btn).data('address'));

    if($('#document_type').val()==='RUC'){
        $('#exampleModalLabel').text('Datos del Cliente Empresarial');
        $('#document_type option:not(:selected)').attr('disabled',true);
        $('#document_type option[value="RUC"]').show();
        $('#name-label').text('Razon Social');
        $('#lastname-group').hide();
        $('#birth-label').text('Fecha de Constitución');
        //$('#lastname').val(null);
    } else {
        $('#exampleModalLabel').text('Datos del Cliente');
        $('#document_type option:not(:selected)').prop('disabled', false);
        $('#document_type option:selected').prop('disabled', false);
        $('#document_type option[value="RUC"]').hide();
        $('#name-label').text('Nombre');
        $('#lastname-group').show();
        $('#birth-label').text('Cumpleaños');
    }

    $('#roomTypeModal').modal('show');
}

function deleteCustomer(btn) {
    $(btn).attr("disabled", true);
    idRoomType= $(btn).data('id');

    Swal.fire({
        title: '¿Estas seguro?',
        text: "¿Realmente quieres eliminar el Cliente?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, borrar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/home/clientes/borrar/" + idRoomType,
                type: "DELETE",
                data: {_token: csrfToken},
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: "Eliminado correctamente"
                    }).then(function () {
                        window.location.href = "/home/clientes/listar";
                    });
                },
                error: function (xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: "Error al eliminar"
                    })
                }
            });
        } else {
            $(btn).attr("disabled", false);
        }
    });
}
function restoreCustomer(btn){
    $(btn).attr("disabled", true);
    idRoomType = $(btn).data('id');

    Swal.fire({
        title: '¿Estas seguro?',
        text: "¿Realmente quieres restaurar el Cliente?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, restaurar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/home/clientes/restaurar/" + idRoomType,
                type: "POST",
                data: {_token: csrfToken},
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    }).then(function () {
                        window.location.href = "/home/clientes/listar/eliminados";
                    });
                },
                error: function (xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: "Error al restaurar"
                    })
                }
            });
        } else {
            $(btn).attr("disabled", false);
        }
    });
}

function exportarExcel() {

    $.confirm({
        icon: 'fas fa-file-excel',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        title: 'Descargar reporte de Clientes ',
        content: 'Se descargará la lista de Clientes.',
        buttons: {
            confirm: {
                text: 'DESCARGAR',
                action: function (e) {
                    $.alert('Descargando archivo ...');

                    var url = "/home/clientes/reporte/descargar";

                    window.location = url;

                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Exportación cancelada.");
                },
            },
        },
    });
}