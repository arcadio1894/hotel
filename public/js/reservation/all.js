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
        enableTime: true,
        dateFormat: "d/m/y H:i",
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
function addReservationDetail(){

    cleanCustomer();
    $('#addReservationDetailModal').modal('show');
}

function updateReservationDetail(){

    cleanCustomer();
    $('#addReservationDetailModal').modal('show');
}


function cleanCustomer(){
    $('#document').val('');
    $('#idCustomer').val('').prop('readonly', false);
    $('#name').val('').prop('readonly', false);
    $('#phone').val('').prop('readonly', false);
    $('#code').val('');
    $('#inputName, #inputPhone').addClass('d-none');

}