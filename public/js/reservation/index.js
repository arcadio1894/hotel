$(document).ready(function () {

    getDataOperations(1);
    $tipo = $('#tipo').val();
    console.log($tipo);

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
    /*
    var documentCliente = $('#inputDocumentCliente').val(); // Obtén el valor del input de documento cliente
    var name = $('#inputName').val(); // Obtén el valor del input de código de operación
    */
    var type = $('#selectType').val();
    
    var tipo = $('#tipo').val();


    $.get('/home/reservas/get/data/'+$numberPage, {
        type: type,
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
    clone.querySelector("[data-id]").innerHTML = data.id;
    clone.querySelector("[data-room_type_id]").innerHTML = data.room_type_id;
    clone.querySelector("[data-level]").innerHTML = data.level;
    clone.querySelector("[data-number]").innerHTML = data.number;
    clone.querySelector("[data-status]").innerHTML = data.status;

/*
    if($('#tipo').val()==="lista"){
    // Configurar los botones en el nuevo td
    var buttonsTd = clone.querySelector("[data-buttons]");
    buttonsTd.innerHTML = ''; // Limpiar contenido existente

    var updateButton = document.createElement('button');
    updateButton.setAttribute('type', 'button');
    updateButton.setAttribute('class', 'btn btn-outline-primary');
    updateButton.setAttribute('onclick', 'updateCustomer(this)');
    updateButton.setAttribute('data-id', data.id);
    updateButton.setAttribute('data-document_type', data.document_type);
    updateButton.setAttribute('data-document', data.document);
    updateButton.setAttribute('data-name', data.name);
    updateButton.setAttribute('data-lastname', data.lastname);
    updateButton.setAttribute('data-phone', data.phone);
    updateButton.setAttribute('data-email', data.email);
    updateButton.setAttribute('data-birth', data.birth);
    updateButton.setAttribute('data-address', data.address);

    updateButton.innerHTML = '<i class="nav-icon fas fa-pen"></i>';
    buttonsTd.appendChild(updateButton);

    var deleteButton = document.createElement('button');
    deleteButton.setAttribute('type', 'button');
    deleteButton.setAttribute('class', 'btn btn-outline-danger');
    deleteButton.setAttribute('onclick', 'deleteCustomer(this)');
    deleteButton.setAttribute('data-id', data.id);
    deleteButton.innerHTML = '<i class="nav-icon fas fa-trash"></i>';
    buttonsTd.appendChild(deleteButton);
    }
    else{
        if($('#tipo').val()==="eliminados")
        {
            var buttonsTd = clone.querySelector("[data-buttons]");
            buttonsTd.innerHTML = '';
            var restoreButton = document.createElement('button');
            restoreButton.setAttribute('type', 'button');
            restoreButton.setAttribute('class', 'btn btn-outline-warning');
            restoreButton.setAttribute('onclick', 'restoreCustomer(this)');
            restoreButton.setAttribute('data-id', data.id);
            restoreButton.innerHTML = '<i class="nav-icon fas fa-check"></i>';
            buttonsTd.appendChild(restoreButton);
        }
        else{
            if($('#tipo').val()==="reporte")
            {

            }
        }
    }
    */

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
