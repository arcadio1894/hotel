$(document).ready(function () {

    //getDataOperations(1);
    $tipo = $('#tipo').val();
    console.log($tipo);

    //$(document).on('click', '[data-item]', showData);
    $("#btn-search-rooms").on('click', showDataSeach);
    $('#rooms').hide();


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
    var reservationType = $('#reservationType').val();
    var selectedDate = $('#selectedDate').val();
    var selectedStartTime = $('#selectedStartTime').val();
    var hoursQuantity = $('#hoursQuantity').val();
    var startDate = $('#startDate').val();
    var endDate = $('#endDate').val();
    var total_guest = $('#total_guest').val();
    var selectRoomType = $('#select_room_type').val();
    var startTime = $('#startTime').val();

    $.ajax({
        url: '/home/reservas/get/rooms/' + $numberPage,
        method: 'GET',
        data: {
            reservationType: reservationType,
            selectedDate: selectedDate,
            selectedStartTime: selectedStartTime,
            hoursQuantity: hoursQuantity,
            startDate: startDate,
            endDate: endDate,
            total_guest: total_guest,
            selectRoomType: selectRoomType,
            startTime: startTime,
        },
        dataType: 'json',
        success: function (data) {
            renderDataOperations(data);
            $('#rooms').show();
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
            }
        }
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
    var total_guest = $('#total_guest').val();
    for (let j = 0; j < dataAccounting.length ; j++) {
        renderDataTableCard(dataAccounting[j],total_guest);
        total_guest-=dataAccounting[j].capacity;
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

function renderDataTableCard(data, total_guest) {
    var clone = activateTemplate('#item-card');
    clone.querySelector("[data-id]").innerHTML = data.id;
    clone.querySelector("[data-type_room]").innerHTML = data.type_room;
    clone.querySelector("[data-level]").innerHTML = data.level;
    clone.querySelector("[data-nivel]").innerHTML = data.level;
    clone.querySelector("[data-number]").innerHTML = data.number;
    clone.querySelector("[data-capacity]").innerHTML = data.capacity;
    if (data.price == null) {
        clone.querySelector("[data-price]").innerHTML = "No Registrado";
    } else {
        clone.querySelector("[data-price]").innerHTML = data.price;
    }


    var checkboxHtml = '<input type="checkbox" class="form-check-input" data-room-id="' + data.id + '" data-price-room="' + data.price + '"';


    if (total_guest>0) {
        checkboxHtml += ' checked';
    }
    checkboxHtml += '>';

    clone.querySelector("[data-buttons]").innerHTML = checkboxHtml;

    if (data.description == null) {
        clone.querySelector("[data-description]").innerHTML = "Sin descripción";
    } else {
        clone.querySelector("[data-description]").innerHTML = data.description_short;
    }

    var imageElement = clone.querySelector("[data-image]");
    imageElement.setAttribute('src', document.location.origin + '/images/rooms/' + data.image);
    imageElement.style.width = '100px';
    imageElement.style.height = 'auto';


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