$(document).ready(function() {
    // ...

    // Manejar el clic en el botón "Generar Costo"
    $("#btnGenerarCosto").on("click", function() {
        generarCosto();
    });
});

function generarCosto() {
    event.preventDefault();
    var selectedRooms=[];
    var selectedRoomPrices = [];
    /*$("input[data-room-id]:checked").each(function() {
        selectedRooms.push($(this).data("room-id"));
        selectedRoomPrices.push($(this).data("price-room"));
    });*/
    var room_id = $("#room_id").html();
    var reservationType = $("#reservationType").val();
    var hoursQuantity = $('#hoursQuantity').val();
    var startDate = $('#startDate').val();
    var endDate = $('#endDate').val();
    $.ajax({
        type: "POST",
        url: "/home/reservas/generar/costo/por/habitacion",
        data: {
            room_id:room_id,
            selectedRoomPrices:selectedRoomPrices,
            reservationType: reservationType,
            hoursQuantity:hoursQuantity,
            startDate:startDate,
            endDate:endDate,
        },
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            $("#resumenTablaBody").empty();

            for (var i = 0; i < response.detalleReserva.length; i++) {
                var habitacion = response.detalleReserva[i].habitacion;
                var precioTotal = response.detalleReserva[i].precioTotal;

                $("#resumenTablaBody").append(
                    "<tr>" +
                    "<td>" + habitacion + "</td>" +
                    "<td>" + precioTotal + "</td>" +
                    "</tr>"
                );
            }

            $("#totalFinal").text("S/. " + response.costoTotal);
            $("#totalFinalMonto").val(response.costoTotal);
        },
        error: function(error) {
            console.error("Error al calcular el costo: ", error);
        }
    });

}
