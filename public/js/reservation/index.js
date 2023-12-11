$(document).ready(function () {

    getDataOperations(1);
    $tipo = $('#tipo').val();
    console.log($tipo);

    $(document).on('click', '[data-item]', showData);
    $(".btn-search").on('click', showDataSeach);

});


function showDataSeach() {
    var type = $(this).val();

    getDataOperations(1,type);
}

function showData() {
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataOperations(numberPage)
}

function getDataOperations($numberPage,$type) {
    var datePicker = flatpickr("#dateSearch");

    //var type = $('#selectType').val();

    var type =$type;
    //var idle =$('#estadoSwitch').val();
    var tipo = $('#tipo').val();
    var idle = $('input[name="inlineRadioOptions"]:checked').val();
    var dateSearch = $('#dateSearch').val();

    var reservation_id = $('#reservation_id').val();
    console.log(type,idle,tipo,reservation_id)

        $.get('/home/reservas/get/data/'+$numberPage, {
            type: type,
            idle: idle,
            tipo: tipo,
            reservation_id:reservation_id,
            dateSearch:dateSearch,
        }, function(data) {
            renderDataOperations(data);
            datePicker.clear();

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

    $("#body-table").html('');
    $("#body-card").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' operaciones');
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
    var cardElement = clone.querySelector(".card");

    clone.querySelector("[data-id]").innerHTML = data.id;
    clone.querySelector("[data-room_type_id]").innerHTML = data.room_type_id;
    clone.querySelector("[data-room_type_name]").innerHTML = data.room_type_name;
    clone.querySelector("[data-level]").innerHTML = data.level;
    clone.querySelector("[data-number]").innerHTML = data.number;
    clone.querySelector("[data-status]").innerHTML = data.status;

    // Limpia las clases de contexto de Bootstrap
    cardElement.classList.remove("bg-success", "bg-danger");

    // Agrega la clase de contexto de Bootstrap según el valor de data.status
    if (data.status === 'O') {
        cardElement.classList.add("bg-danger");
    } else if (data.status === 'D') {
        cardElement.classList.add("bg-success");
    } else if (data.status === 'E') {
        cardElement.classList.add("bg-info");
    }


    if(data.status==="E"){
    // Configurar los botones en el nuevo td
    var buttonsTd = clone.querySelector("[data-buttons]");
    buttonsTd.innerHTML = ''; // Limpiar contenido existente

    var updateButton = document.createElement('button');
    updateButton.setAttribute('type', 'button');
    updateButton.setAttribute('class', 'btn btn-outline-primary');
    updateButton.setAttribute('onclick', 'updateReservationDetail(this)');
    updateButton.setAttribute('data-id', data.id);
    /*
    updateButton.setAttribute('data-room_type_id', data.room_type_id);
    updateButton.setAttribute('data-room_type_name', data.room_type_name);
    updateButton.setAttribute('data-level', data.level);
    updateButton.setAttribute('data-number', data.number);
    updateButton.setAttribute('data-status', data.status);
    */
    
    updateButton.innerHTML = '<i class="nav-icon fas fa-pen"></i>';
    buttonsTd.appendChild(updateButton);
    /*
    var deleteButton = document.createElement('button');
    deleteButton.setAttribute('type', 'button');
    deleteButton.setAttribute('class', 'btn btn-outline-danger');
    deleteButton.setAttribute('onclick', 'deleteCustomer(this)');
    deleteButton.setAttribute('data-id', data.id);
    deleteButton.innerHTML = '<i class="nav-icon fas fa-check"></i>';
    buttonsTd.appendChild(deleteButton);
    */
    }
    else if(data.status==="D")
        {
            var buttonsTd = clone.querySelector("[data-buttons]");
            buttonsTd.innerHTML = '';
            var addButton = document.createElement('button');
            addButton.setAttribute('type', 'button');
            addButton.setAttribute('class', 'btn btn-outline-primary');
            addButton.setAttribute('onclick', 'addReservationDetail(this)');
            addButton.setAttribute('data-id', data.id);
            addButton.innerHTML = '<i class="nav-icon fa fa-plus"></i>';
            buttonsTd.appendChild(addButton);
        }
        else{
            if($('#tipo').val()==="reporte")
            {

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
