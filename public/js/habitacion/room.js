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

$(document).ready(function () {

    getDataOperations(1);

    $(document).on('click', '[data-item]', showData);
    $("#btn-search").on('click', showDataSeach);


});

function showDataSeach() {
    getDataOperations(1)
}

function showData() {
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataOperations(numberPage)
}

function getDataOperations($numberPage) {
    var inputType = $('#inputType').val();
    var inputLevel = $('#inputLevel').val();
    var inputNumber = $('#inputNumber').val();
    var inputStatus = $('#inputStatus').val();
    var tipo = $('#tipo').val();

    $.get('/home/rooms/get/data/'+$numberPage, {
        inputType: inputType,
        inputLevel: inputLevel,
        inputNumber: inputNumber,
        inputStatus: inputStatus,
        tipo: tipo
    }, function(data) {
        renderDataOperations(data);

    }).fail(function(jqXHR, textStatus, errorThrown) {
        // Función de error, se ejecuta cuando la solicitud GET falla
        console.error(textStatus, errorThrown);
        if (jqXHR.responseJSON.message && !jqXHR.responseJSON.errors) {
            toastr.error(jqXHR.responseJSON.message, 'Error', {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });
        }
        for (var property in jqXHR.responseJSON.errors) {
            toastr.error(jqXHR.responseJSON.errors[property], 'Error', {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });
        }
    }, 'json')
        .done(function() {
            // Configuración de encabezados
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            };

            $.ajaxSetup({
                headers: headers
            });
        });
}

function renderDataOperations(data) {
    var dataAccounting = data.data;
    var pagination = data.pagination;
    console.log(dataAccounting);
    console.log(pagination);

    $("#body-card").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' Habitaciones');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    for (let j = 0; j < dataAccounting.length ; j++) {
        renderDataTableCard(dataAccounting[j]);
    }

    if (pagination.currentPage > 1)
    {
        renderPreviousPage(pagination.currentPage-1);
    }

    if (pagination.totalPages > 1)
    {
        if (pagination.currentPage > 3)
        {
            renderItemPage(1);

            if (pagination.currentPage > 4) {
                renderDisabledPage();
            }
        }

        for (var i = Math.max(1, pagination.currentPage - 2); i <= Math.min(pagination.totalPages, pagination.currentPage + 2); i++)
        {
            renderItemPage(i, pagination.currentPage);
        }

        if (pagination.currentPage < pagination.totalPages - 2)
        {
            if (pagination.currentPage < pagination.totalPages - 3)
            {
                renderDisabledPage();
            }
            renderItemPage(i, pagination.currentPage);
        }

    }

    if (pagination.currentPage < pagination.totalPages)
    {
        renderNextPage(pagination.currentPage+1);
    }
}

function renderDataTableCard(data) {
    var clone = activateTemplate('#item-card');
    clone.querySelector("[data-id]").innerHTML = data.id;
    if (data.type_room == null) {
        clone.querySelector("[data-type_room]").innerHTML = "Sin descripción";
    } else {
        clone.querySelector("[data-type_room]").innerHTML = data.type_room;
    }
    clone.querySelector("[data-level]").innerHTML = data.level + "-";
    clone.querySelector("[data-number]").innerHTML = data.number;
    if (data.status === 'D') {
        clone.querySelector("[data-status]").innerHTML = "Disponible";
        clone.querySelector("[data-color]").classList.add("bg-success");
        clone.querySelector("[data-color_text]").classList.add("text-light");
    } else if (data.status === 'O') {
        clone.querySelector("[data-status]").innerHTML = "Ocupado";
        clone.querySelector("[data-color]").classList.add("bg-danger");
        clone.querySelector("[data-color_text]").classList.add("text-light");
    }else if (data.status === 'F') {
        clone.querySelector("[data-status]").innerHTML = "Fuera de servicio";
        clone.querySelector("[data-color]").classList.add("bg-secondary");
        clone.querySelector("[data-color_text]").classList.add("text-light");
    }else if (data.status === 'R') {
        clone.querySelector("[data-status]").innerHTML = "Reservada";
        clone.querySelector("[data-color]").classList.add("bg-warning");
        clone.querySelector("[data-color_text]").classList.add("text-light");
    }
    else if (data.status === 'L') {
        clone.querySelector("[data-status]").innerHTML = "Limpieza";
        clone.querySelector("[data-color]").classList.add("bg-primary");
        clone.querySelector("[data-color_text]").classList.add("text-light");
    }
    else if (data.status === 'E') {
        clone.querySelector("[data-status]").innerHTML = "En Espera";
        clone.querySelector("[data-color]").classList.add("bg-info");
        clone.querySelector("[data-color_text]").classList.add("text-light");
    }
    if (data.description == null) {
        clone.querySelector("[data-description]").innerHTML = "Sin descripción";
    } else {
        clone.querySelector("[data-description]").innerHTML = data.description;
    }

    var imageElement = clone.querySelector("[data-image]");
    imageElement.setAttribute('src', document.location.origin + '/images/rooms/' + data.image);
    imageElement.style.width = '100px';
    imageElement.style.height = 'auto';

    if($('#tipo').val()==="Lista"){
        // Configurar los botones en el nuevo td
        var buttonsTd = clone.querySelector("[data-buttons]");
        buttonsTd.innerHTML = ''; // Limpiar contenido existente

        var updateButton = document.createElement('button');
        updateButton.setAttribute('type', 'button');
        updateButton.setAttribute('class', 'btn btn-outline-light');
        updateButton.setAttribute('onclick', 'updateRoom(this)');
        updateButton.setAttribute('data-id', data.id);
        updateButton.setAttribute('data-type_room_id', data.type_room_id);
        updateButton.setAttribute('data-level', data.level);
        updateButton.setAttribute('data-number', data.number);
        updateButton.setAttribute('data-description', data.description);
        updateButton.setAttribute('data-status', data.status);
        updateButton.setAttribute('data-image', data.image);

        updateButton.innerHTML = '<i class="nav-icon fas fa-pen"></i>';
        buttonsTd.appendChild(updateButton);

        var deleteButton = document.createElement('button');
        deleteButton.setAttribute('type', 'button');
        deleteButton.setAttribute('class', 'btn btn-outline-dark');
        deleteButton.setAttribute('onclick', 'deleteRoom(this)');
        deleteButton.setAttribute('data-id', data.id);
        deleteButton.innerHTML = '<i class="nav-icon fas fa-trash"></i>';
        buttonsTd.appendChild(deleteButton);
    }
    else{
        if($('#tipo').val()==="Eliminados")
        {
            var buttonsTd = clone.querySelector("[data-buttons]");
            buttonsTd.innerHTML = '';
            var restoreButton = document.createElement('button');
            restoreButton.setAttribute('type', 'button');
            restoreButton.setAttribute('class', 'btn btn-outline-light');
            restoreButton.setAttribute('onclick', 'restoreRoom(this)');
            restoreButton.setAttribute('data-id', data.id);
            restoreButton.innerHTML = '<i class="nav-icon fas fa-check"></i>';
            buttonsTd.appendChild(restoreButton);
        }
    }

    $("#body-card").append(clone);

    $('[data-toggle="tooltip"]').tooltip();
}

function renderPreviousPage($numberPage) {
    var clone = activateTemplate('#previous-page');
    clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
    $("#pagination").append(clone);
}

function renderDisabledPage() {
    var clone = activateTemplate('#disabled-page');
    $("#pagination").append(clone);
}

function renderItemPage($numberPage, $currentPage) {
    var clone = activateTemplate('#item-page');
    if ( $numberPage == $currentPage )
    {
        clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
        clone.querySelector("[data-active]").setAttribute('class', 'page-item active');
        clone.querySelector("[data-item]").innerHTML = $numberPage;
    } else {
        clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
        clone.querySelector("[data-item]").innerHTML = $numberPage;
    }

    $("#pagination").append(clone);
}

function renderNextPage($numberPage) {
    var clone = activateTemplate('#next-page');
    clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
    $("#pagination").append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function saveRoom() {
    $("#guardar").prop("disabled", true);
    let url = $('#id').val() ? '/home/rooms/edit/' + $('#id').val() : '/home/rooms';

    $.ajax({
        url: url,
        method: 'POST',
        data: $('#roomForm').serialize(),
        success: function (response) {
            $("#roomModal").modal("hide");
            $("#guardar").prop("disabled", false);
            Toast.fire({
                icon: 'success',
                title: response.success,
            }).then(function () {
                window.location.href = "/home/rooms/listar";
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
                    window.location.href = "/home/rooms/listar";
                });
            }
        }
    });
}

function cleanRoom(){
    $('#id').val('');
    $('#room_type').val('');
    $('#level').val('');
    $('#number').val('');
    $('#description').val('');
    $('#image').val('');
    $('#status').val('');
    $('#roomModal').modal('show');
}

function updateRoom(btn) {
    $('#id').val($(btn).data('id'));
    $('#room_type').val($(btn).data('type_room_id'));
    $('#level').val($(btn).data('level'));
    $('#number').val($(btn).data('number'));
    $('#status').val($(btn).data('status'));
    $('#description').val($(btn).data('description'));

    // Previsualización de la imagen
    var imagePreview = $('#preview');
    var imageInput = $('#image');
    if ($(btn).data('image')) {
        imagePreview.attr('src',imageUrl + $(btn).data('image')).show();
        imageInput.val('');
    } else {
        imagePreview.hide();
        imageInput.val('');
    }

    $('#roomModal').modal('show');
}

function deleteRoom(btn) {
    $(btn).attr("disabled", true);
    idRoom= $(btn).data('id');

    Swal.fire({
        title: '¿Estas seguro?',
        text: "¿Realmente quieres eliminar la habitación?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, borrar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/home/rooms/delete/" + idRoom,
                type: "DELETE",
                data: {_token: csrfToken},
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: "Eliminado correctamente"
                    }).then(function () {
                        window.location.href = "/home/rooms/listar";
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
function restoreRoom(btn){
    $(btn).attr("disabled", true);
    idRoom = $(btn).data('id');

    Swal.fire({
        title: '¿Estas seguro?',
        text: "¿Realmente quieres restaurar la habitacion?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, restaurar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/home/rooms/restore/" + idRoom,
                type: "POST",
                data: {_token: csrfToken},
                success: function (response) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    }).then(function () {
                        window.location.href = "/home/rooms/listar/eliminados";
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

function previewImage(input) {
    var preview = document.getElementById('preview');
    var file = input.files[0];
    var reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
        preview.style.display = 'block';

        // Restablecer el valor del campo de entrada de archivo
        input.value = '';
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
}
