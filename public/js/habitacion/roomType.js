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
function saveRoomType() {
    $("#guardar").prop("disabled", true);
    let url = $('#id').val() ? '/home/room/types/edit/' + $('#id').val() : '/home/room/types';

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
                window.location.href = "/home/room/types/listar";
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
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Hubo un error al procesar la solicitud'
                }).then(function () {
                    window.location.href = "/home/room/types/listar";
                });
            }
        }
    });
}

function cleanRoomType(){
    $('#id').val('');
    $('#name').val('');
    $('#description').val('');
    $('#capacity').val('');
    $('#roomTypeModal').modal('show');
}

function updateRoomType(btn){
    $('#id').val($(btn).data('id'));
    $('#name').val($(btn).data('name'));
    $('#description').val($(btn).data('description'));
    $('#capacity').val($(btn).data('capacity'));
    $('#roomTypeModal').modal('show');
}

function deleteRoomType(btn) {
    $(btn).attr("disabled", true);
    idRoomType= $(btn).data('id');

    Swal.fire({
        title: '¿Estas seguro?',
        text: "¿Realmente quieres eliminar el tipo de habitación?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, borrar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/home/room/types/delete/" + idRoomType,
                type: "DELETE",
                data: {_token: csrfToken},
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: "Eliminado correctamente"
                    }).then(function () {
                        window.location.href = "/home/room/types/listar";
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
function restoreRoomType(btn){
    $(btn).attr("disabled", true);
    idRoomType = $(btn).data('id');

    Swal.fire({
        title: '¿Estas seguro?',
        text: "¿Realmente quieres restaurar el tipo de habitacion?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, restaurar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/home/room/types/restore/" + idRoomType,
                type: "POST",
                data: {_token: csrfToken},
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    }).then(function () {
                        window.location.href = "/home/room/types/listar/eliminados";
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