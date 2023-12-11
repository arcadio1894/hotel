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
var csrfToken = $('meta[name="csrf-token"]').attr('content');
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
        }
        else{
            $("#error-message").text("");
        }
    });


    $("#buscarBtn").on('click', showCustomerSearch);

    $('#documentType').change(function () {
        // Obtener el valor seleccionado
        var selectedValue = $(this).val();
        if (selectedValue === 'RUC') {
            $('#name-label').text('Razon Social');
            $('#inputLastname').hide();
            $('#lastname').val(null);
            $('#birth-label').text('Fecha de Constitución');
        } else {
            $('#name-label').text('Nombre');
            $('#inputLastname').show();
            $('#birth-label').text ('Fecha de Nacimiento');
        }
    });

    $('#reservationType').change(function () {
        var selectedValue = $(this).val();
        if (selectedValue === '1') {
            $("#hourFields").show();
            $("#dayFields").hide();
        } else {
            $("#hourFields").hide();
            $("#dayFields").show();
        }
    });
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


            $('#inputName, #inputPhone, #inputDocumentType, #inputLastname, #inputEmail, #inputAddress, #inputBirth').removeClass('d-none');
            $('#idCustomer').val(data.cliente.id).prop('readonly', true);
            $('#documentType').val(data.cliente.document_type).prop('disabled', true);
            $('#name').val(data.cliente.name).prop('readonly', true);
            $('#phone').val(data.cliente.phone).prop('readonly', true);
            $('#lastname').val(data.cliente.lastname).prop('readonly', true);
            $('#email').val(data.cliente.email).prop('readonly', true);
            $('#birth').val(data.cliente.birth).prop('disabled', true);
            $('#address').val(data.cliente.address).prop('readonly', true);
            $('#code').val(data.codigo);
            if (data.cliente.document_type === 'RUC') {
                // Mostrar campos específicos para RUC
                $('#name-label').text('Razon Social');
                $('#inputLastname').hide();
                $('#lastname').val(null);
                $('#birth-label').text('Fecha de Constitución');
            } else {
                $('#name-label').text('Nombre');
                $('#inputLastname').show();
                $('#birth-label').text ('Fecha de Nacimiento');
            }
        }
        else{
            Toast.fire({
                icon: 'warning',
                title: 'Cliente no Encontrado.'
            })

            $('#inputName, #inputPhone, #inputDocumentType, #inputLastname, #inputEmail, #inputAddress, #inputBirth').removeClass('d-none');
            $('#idCustomer').val('').prop('readonly', false);
            $('#name').val('').prop('readonly', false);
            $('#phone').val('').prop('readonly', false);
            $('#documentType').val('').prop('disabled', false);
            $('#lastname').val('').prop('readonly', false);
            $('#email').val('').prop('readonly', false);
            $('#birth').val('').prop('disabled', false);
            $('#address').val('').prop('readonly', false);
            $('#code').val(data.codigo);
        }

    })
}

function cleanReservations(){
    /*$('#document').val('');
    $('#idCustomer').val('').prop('readonly', false);
    $('#name').val('').prop('readonly', false);
    $('#phone').val('').prop('readonly', false);
    $('#code').val('');*/
    //$('#inputName, #inputPhone, #inputDocumentType, #inputLastname, #inputEmail, #inputAddress, #inputBirth').addClass('d-none');
    $('#reservationModal').modal('show');
}

function makeReservations() {
    window.location.href = "/home/reservas/crear/nueva/reserva";
}


function saveReservations() {
    $("#guardar").prop("disabled", true);
    var selectedRooms=[];
    $("input[data-room-id]:checked").each(function() {
        selectedRooms.push($(this).data("room-id"));
    });

    let formData = {
        code: $("#code").val(),
        idCustomer: $("#idCustomer").val(),
        name: $('#name').val(),
        document: $('#document').val(),
        phone:$('#phone').val(),
        documentType:$('#documentType').val(),
        lastname: $('#lastname').val(),
        email:$('#email').val(),
        birth:$('#birth').val(),
        address: $('#address').val(),
        employeerid: $("#employeerid").val(),
        reservationType:$('#reservationType').val(),
        selectedDate:$("#selectedDate").val(),
        selectedStartTime:  $("#selectedStartTime").val(),
        hoursQuantity:$('#hoursQuantity').val(),
        startDate: $("#startDate").val(),
        endDate: $("#endDate").val(),
        total_guest: $("#total_guest").val(),
        startTime: $("#startTime").val(),
        paymethod: $("#paymethod").val(),
        initialpay: $("#initialpay").val(),
        selectedRooms:selectedRooms,

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
                    window.location.href = "/home/reservas/lista";
                });
            }
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