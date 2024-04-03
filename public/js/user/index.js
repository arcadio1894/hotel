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
function save() {
    $("#guardar").prop("disabled", true);
    let url = $('#id').val() ? '/home/users/edit/' + $('#id').val() : '/home/users';

    $.ajax({
        url: url,
        method: 'POST',
        data: $('#userForm').serialize(),
        success: function (response) {
            $("#userModal").modal("hide");
            $("#guardar").prop("disabled", false);
            Toast.fire({
                icon: 'success',
                title: response.success,
            }).then(function () {
                //window.location.href = "/home/users/listar";
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
                    title: 'Hubo un error al guardar'
                }).then(function () {
                    //window.location.href = "/home/users/listar";
                });
            }
        }
    });
}

function addUser(){
    $('#id').val('');
    $('#document_type').val('');

    $('#document_type option:not(:selected)').prop('disabled', false);
    $('#document_type option:selected').prop('disabled', false);
    $('#document_type option[value="RUC"]').show();

    $('#document').val('');
    $('#name').val('');
    $('#lastname').val('');
    $('#email').val('');
    $('#role_id').val('');
    $('#userModal').modal('show');
}
function updateDocumentType() {
    var documentTypeValue = $('#document_type').val();
    if ($('#role_id').val() === "5" && documentTypeValue === 'RUC') {
        $('#name-label').text('Razon Social');
        $('#lastname-group').hide();
    } else {
        $('#name-label').text('Nombre');
        $('#lastname-group').show();
    }
}

function updateUser(btn) {
    $('#id').val($(btn).data('id'));
    $('#name').val($(btn).data('name'));
    $('#lastname').val($(btn).data('lastname'));
    $('#email').val($(btn).data('email'));
    $('#document_type').val($(btn).data('document_type'));
    $('#role_id').val($(btn).data('role_id'));
    console.log($(btn).data('id'));
    console.log($(btn).data('name'));
    console.log($(btn).data('lastname'));
    console.log($(btn).data('email'));
    console.log($(btn).data('role_id'));
    console.log($(btn).data('document_type'));

    updateDocumentType();


    $('#document_type').on('change', function () {
        updateDocumentType();
        $('#lastname-group').show();
    });
    if ($('#role_id').val() === "5") {
        $('#exampleModalLabel').text('Datos del Usuario Cliente');
        $('#role_id option:not([value="5"])').attr('disabled', true);
        $('#role_id option[value="5"]').show();
        $('#document_type').on('change', function () {
            var documentTypeValue = $(this).val();
            if (documentTypeValue === 'RUC') {
                $('#name').text('Razon Social');
                $('#lastname-group').hide();
            } else {
                $('#name').text('Nombre');
                $('#lastname-group').show();
            }
        });
        var initialDocumentTypeValue = $('#document_type').val();
        if (initialDocumentTypeValue === 'RUC') {
            $('#name-label').text('Razon Social');
            $('#lastname-group').hide();
        }
    } else {
        $('#exampleModalLabel').text('Datos del Usuario Empleado');
        $('#role_id option:not(:selected)').prop('disabled', false);
        $('#role_id option:selected').prop('disabled', false);
        $('#role_id option[value="5"]').hide();
        $('#name').text('Nombre');
        $('#lastname-group').show();
        $('#document_type').hide();
    }

    $('#userModal').modal('show');

}

function deleteUser(btn) {
    $(btn).attr("disabled", true);
    idUser= $(btn).data('id');

    Swal.fire({
        title: '¿Estas seguro?',
        text: "¿Realmente quieres eliminar el usuario?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, borrar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url:"delete/" + idUser,
                type: "DELETE",
                data: {_token: csrfToken},
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: "Usuario eliminado correctamente"
                    }).then(function () {
                        window.location.href = "/home/users/listar";
                    });
                },
                error: function (xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: "Error al eliminar al usuario"
                    })
                }
            });
        } else {
            $(btn).attr("disabled", false);
        }
    });
}
function restaurarUsuario(btn){
    $(btn).attr("disabled", true);
    iduser = $(btn).data('id');

    Swal.fire({
        title: '¿Estas seguro?',
        text: "¿Realmente quieres restaurar el usuario?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, restaurar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/users/restore/" + iduser,
                type: "POST",
                data: {_token: csrfToken},
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    }).then(function () {
                        window.location.href = "/users";
                    });
                },
                error: function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        let errorDetails = xhr.responseJSON.error;
                        Toast.fire({
                            icon: 'error',
                            title: 'Error al restaurar',
                            html: `Detalles: ${errorDetails}`
                        }).then(function () {
                            window.location.href = "/users";
                        });
                    }
                }
            });
        }
        else {
            $(btn).attr("disabled", false);
        }
    });
}




