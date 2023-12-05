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

    $(".datetimepicker").flatpickr({
        enableTime: false,
        dateFormat: "d/m/y",
        disableMobile: true
    });

    // Agrega el evento de cambio a los datepickers
    $("#startdate, #enddate").change(function () {
        // Obtiene los valores de las fechas
        var startDate = $("#startdate").val();
        var endDate = $("#enddate").val();

        // Compara las fechas
        if (new Date(endDate) <= new Date(startDate)) {
            Toast.fire({
                icon: 'error',
                title: 'La fecha de fin debe ser posterior a la fecha de inicio'
            })
            $("#error-message").text("La fecha de fin debe ser posterior a la fecha de inicio");
            // Puedes agregar más lógica aquí según tus necesidades
            // Por ejemplo, deshabilitar el botón de envío del formulario
        }
        else{
            $("#error-message").text("");
        }
    });


    $("#buscarBtn").on('click', showCustomerSearch);
});

function showCustomerSearch() {
    var documento = $('#document').val();
    


    $.get('/home/reservas/buscar-cliente', {
        dni: documento
    }, function(data) {
        console.log(data)
        if(data.cliente){
            Toast.fire({
                icon: 'success',
                title: 'Cliente Encontrado.',
            })


            $('#inputName, #inputPhone').removeClass('d-none');
            $('#idCustomer').val(data.cliente.id).prop('readonly', true);
            $('#name').val(data.cliente.name).prop('readonly', true);
            $('#phone').val(data.cliente.phone).prop('readonly', true);
            $('#code').val(data.codigo);
        }
        else{
            Toast.fire({
                icon: 'warning',
                title: 'Cliente no Encontrado.'
            })

            $('#inputName, #inputPhone').removeClass('d-none');
            $('#idCustomer').val('').prop('readonly', false);
            $('#name').val('').prop('readonly', false);
            $('#phone').val('').prop('readonly', false);
            $('#code').val(data.codigo);
        }

    })
}

function cleanReservations(){
    $('#document').val('');
    $('#idCustomer').val('').prop('readonly', false);
    $('#name').val('').prop('readonly', false);
    $('#phone').val('').prop('readonly', false);
    $('#code').val('');
    $('#inputName, #inputPhone').addClass('d-none');
    $('#reservationModal').modal('show');
}


function saveReservations() {
    $("#guardar").prop("disabled", true);

    // Recopila los datos del formulario
    let formData = {
        code: $("#code").val(),
        idCustomer: $("#idCustomer").val(),
        employeerid: $("#employeerid").val(),
        startdate: $("#startdate").val(),
        enddate: $("#enddate").val(),
        totalguest: $("#totalguest").val(),
    };

    // Realiza la solicitud AJAX
    $.ajax({
        url: '/home/reservas/crear',
        method: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (response) {
            // Manejo de éxito
            $("#reservationModal").modal("hide");
            $("#guardar").prop("disabled", false);
            Toast.fire({
                icon: 'success',
                title: response.success,
            }).then(function () {
                window.location.href = "/home/reservas/lista";
            });
        },
        error: function (xhr) {
            // Manejo de errores
            $("#guardar").prop("disabled", false);
            // ... Resto del manejo de errores
        }
    });
}

function updateReservation(btn) {
    reservation_id = $(btn).data('id');

    $.ajax({
        url: '/home/reservas/lista/' + reservation_id,
        method: 'GET',
        data: {_token: $('meta[name="csrf-token"]').attr('content')},  // Añadir el token CSRF
        success: function (response) {
            // Redirigir a la nueva página después de que la solicitud tenga éxito
            window.location.href = '/home/reservas/lista/' + reservation_id;
        },
        error: function (xhr, status, error) {
            // Manejar errores
            console.error(xhr, status, error);
        }
    });
}
function formatearFecha(fecha) {
    const fechaFormateada = new Date(fecha).toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
    return fechaFormateada;
}