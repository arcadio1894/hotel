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


            $('#inputName, #inputPhone, #inputDocumentType, #inputLastname, #inputEmail, #inputAddress, #inputBirth').removeClass('d-none');
            $('#idCustomer').val(data.cliente.id).prop('readonly', true);
            $('#documentType').val(data.cliente.document_type).prop('readonly', true);
            $('#name').val(data.cliente.name).prop('readonly', true);
            $('#phone').val(data.cliente.phone).prop('readonly', true);
            $('#lastname').val(data.cliente.lastname).prop('readonly', true);
            $('#email').val(data.cliente.email).prop('readonly', true);
            $('#birth').val(data.cliente.birth).prop('readonly', true);
            $('#address').val(data.cliente.address).prop('readonly', true);
            $('#code').val(data.codigo);
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
            $('#documentType').val('').prop('readonly', false);
            $('#lastname').val('').prop('readonly', false);
            $('#email').val('').prop('readonly', false);
            $('#birth').val('').prop('readonly', false);
            $('#address').val('').prop('readonly', false);
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
    $('#inputName, #inputPhone, #inputDocumentType, #inputLastname, #inputEmail, #inputAddress, #inputBirth').addClass('d-none');
    $('#reservationModal').modal('show');
}


function saveReservations() {
    $("#guardar").prop("disabled", true);

    // Recopila los datos del formulario
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
                window.location.href = "/home/reservas/lista/crear";
            });
        },
        error: function (xhr) {
            // Manejo de errores
            console.log(xhr);
            $("#guardar").prop("disabled", false);
            // ... Resto del manejo de errores
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