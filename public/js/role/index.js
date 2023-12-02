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
    let url = $('#id').val() ? '/home/roles/edit/' + $('#id').val() : '/home/roles';

    $.ajax({
        url: url,
        method: 'POST',
        data: $('#rolForm').serialize(),
        success: function (response) {
            $("#rolModal").modal("hide");
            $("#guardar").prop("disabled", false);
            Toast.fire({
                icon: 'success',
                title: response.success,
            }).then(function () {
                window.location.href = "/home/roles/listar";
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
                    window.location.href = "/home/roles/listar";
                });
            }
        }
    });
}

function addRol(){
    $('#id').val('');
    $('#name').val('');
    $('#description').val('');
    $('#rolModal').modal('show');
}

function updateRol(btn) {
    $('#id').val($(btn).data('id'));
    $('#name').val($(btn).data('name'));
    $('#description').val($(btn).data('description'));
    console.log($(btn).data('id'));
    console.log($(btn).data('name'));
    console.log($(btn).data('description'));
    $('#rolModal').modal('show');

}

function deleteRol(btn) {
    $(btn).attr("disabled", true);
    idRol= $(btn).data('id');

    Swal.fire({
        title: '¿Estas seguro?',
        text: "¿Realmente quieres eliminar el rol?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, borrar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "delete/" + idRol,
                type: "DELETE",
                data: {_token: csrfToken},
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: "Rol eliminado correctamente"
                    }).then(function () {
                        window.location.href = "/home/roles/listar";
                    });
                },
                error: function (xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: "Error al eliminar el rol"
                    })
                }
            });
        } else {
            $(btn).attr("disabled", false);
        }
    });
}
function redirectRol(btn) {
    $(btn).attr("disabled", true);
    idRol = $(btn).data('id');

    Swal.fire({
        title: "Cargando...",
        html: "Redirigiendo en <b></b> segundos.",
        timer: 3000,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
            const timer = Swal.getPopup().querySelector("b");
            timerInterval = setInterval(() => {
                timer.textContent = `${Math.ceil(Swal.getTimerLeft() / 1000)}`;
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
            window.location.href = "/home/roles/permisos/" + idRol;
        }
    });
}
function restaurarRol(btn){
    $(btn).attr("disabled", true);
    idrol = $(btn).data('id');

    Swal.fire({
        title: '¿Estas seguro?',
        text: "¿Realmente quieres restaurar el rol?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, restaurar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/roles/restore/" + idrol,
                type: "POST",
                data: {_token: csrfToken},
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    }).then(function () {
                        window.location.href = "/roles";
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
                            window.location.href = "/roles";
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

function asignarForm() {
    $("#asignar").prop("disabled", true);
    var form = $('#asignarEdit');

    if ($('input[type="checkbox"]:checked').length === 0) {
        Swal.fire({
            title: 'Ningún permiso asignado',
            text: "¿Realmente no quieres asignar permisos a este rol?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, guardar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    success: function (response) {
                        $("#asignar").prop("disabled", false);
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                    },
                    error: function (xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            let errorDetails = xhr.responseJSON.error;
                            Toast.fire({
                                icon: 'error',
                                title: 'Error al guardar los permisos',
                                html: `Detalles: ${errorDetails}`
                            }).then(function () {
                                window.location.href = "/roles";
                            });
                        }
                    }
                });
            }else {
                $("#asignar").prop("disabled", false);
            }
        });
    }else {
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function (response) {
                $("#asignar").prop("disabled", false);
                Toast.fire({
                    icon: 'success',
                    title: response.message
                });
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    let errorDetails = xhr.responseJSON.error;
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al guardar los permisos',
                        html: `Detalles: ${errorDetails}`
                    }).then(function () {
                        window.location.href = "/roles";
                    });
                }
            }
        });
    }
}

$(document).ready(function() {
    $('.checkbox-select-all').each(function() {
        var groupId = $(this).data('group-id');
        var groupCheckboxSelector = '.group-checkbox-' + groupId;
        var updateSelectAllCheckbox = function() {
            var allChecked = $(groupCheckboxSelector).length === $(groupCheckboxSelector + ':checked').length;
            $(this).prop('checked', allChecked);
        };

        $(groupCheckboxSelector).change(updateSelectAllCheckbox.bind(this));
        updateSelectAllCheckbox.call(this);
    });
    $('.checkbox-select-all').click(function() {
        var isChecked = $(this).prop('checked');
        var groupId = $(this).data('group-id');
        var groupCheckboxSelector = '.group-checkbox-' + groupId;
        $(groupCheckboxSelector).prop('checked', isChecked);
    });
});



