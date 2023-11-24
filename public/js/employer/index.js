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
    let url = $('#id').val() ? '/home/employers/edit/' + $('#id').val() : '/home/employers';

    $.ajax({
        url: url,
        method: 'POST',
        data: $('#employerForm').serialize(),
        success: function (response) {
            $("#employerModal").modal("hide");
            $("#guardar").prop("disabled", false);
            Toast.fire({
                icon: 'success',
                title: response.success,
            }).then(function () {
                window.location.href = "/home/employers/listar";
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
                    window.location.href = "/home/employers/listar";
                });
            }
        }
    });
}

function addEmployer(){
    $('#id').val('');
    $('#name').val('');
    $('#lastname').val('');
    $('#position_id').val('');
    $('#dni').val('');
    $('#address').val('');
    $('#email').val('');
    $('#birth').val('');
    $('#phone').val('');
    $('#employerModal').modal('show');
}

function updateEmployer(btn) {
    $('#id').val($(btn).data('id'));
    $('#name').val($(btn).data('name')).prop('readonly', true);
    $('#lastname').val($(btn).data('lastname')).prop('readonly', true);
    $('#position').val($(btn).data('position'));
    $('#dni').val($(btn).data('dni')).prop('readonly', true);
    $('#address').val($(btn).data('address'));
    $('#email').val($(btn).data('email')).prop('readonly', true);
    $('#birth').val($(btn).data('birth'));
    $('#phone').val($(btn).data('phone'));
    $('#employerModal').modal('show');
}



function deleteEmployer(btn) {
    $(btn).attr("disabled", true);
    idEmployer= $(btn).data('id');

    Swal.fire({
        title: '¿Estas seguro?',
        text: "¿Realmente quieres eliminar el empleado?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, borrar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "delete/" + idEmployer,
                type: "DELETE",
                data: {_token: csrfToken},
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: "Empleado eliminado correctamente"
                    }).then(function () {
                        window.location.href = "/home/employers/listar";
                    });
                },
                error: function (xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: "Error al eliminar el empleado"
                    })
                }
            });
        } else {
            $(btn).attr("disabled", false);
        }
    });
}
function restoreEmployer(btn){
    $(btn).attr("disabled", true);
    idEmployer = $(btn).data('id');

    Swal.fire({
        title: '¿Estas seguro?',
        text: "¿Realmente quieres restaurar el empleado?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, restaurar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "restore/" + idEmployer,
                type: "POST",
                data: {_token: csrfToken},
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    }).then(function () {
                        window.location.href = "/home/employers/eliminados";
                    });
                },
                error: function (xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: "Error al restaurar el empleado"
                    })
                }
            });
        } else {
            $(btn).attr("disabled", false);
        }
    });
}