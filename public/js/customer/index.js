$(document).ready(function () {

    getDataOperations(1);

    $(document).on('click', '[data-item]', showData);
    $("#btn-search").on('click', showDataSeach);

});

var $formEdit;
var $modalEdit;
var $buttonSubmit;
var $buttonCancel;
var $buttonClose;
var $formValidation;

function showDataSeach() {
    getDataOperations(1)
}

function showData() {
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    getDataOperations(numberPage)
}

function getDataOperations($numberPage) {
    var documentCliente = $('#inputDocumentCliente').val(); // Obtén el valor del input de documento cliente
    var codigoOperacion = $('#inputCodigoOperacion').val(); // Obtén el valor del input de código de operación
    var bancoId = $('#selectBanco').val();

    $.get('/home/clientes/get/data/'+$numberPage, {
        document_cliente: documentCliente,
        codigo_operacion: codigoOperacion,
        banco_id: bancoId
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
    var clone = activateTemplate('#item-table');
    clone.querySelector("[data-id]").innerHTML = data.id;
    clone.querySelector("[data-document_type]").innerHTML = data.document_type;
    clone.querySelector("[data-document]").innerHTML = data.document;
    clone.querySelector("[data-name]").innerHTML = data.name;
    clone.querySelector("[data-lastname]").innerHTML = data.lastname;
    clone.querySelector("[data-phone]").innerHTML = data.phone;
    clone.querySelector("[data-email]").innerHTML = data.email;
    clone.querySelector("[data-birth]").innerHTML = data.birth;
    clone.querySelector("[data-address]").innerHTML = data.address;

   // Configurar los botones en el nuevo td
   var buttonsTd = clone.querySelector("[data-buttons]");
   buttonsTd.innerHTML = ''; // Limpiar contenido existente

   var updateButton = document.createElement('button');
   updateButton.setAttribute('type', 'button');
   updateButton.setAttribute('class', 'btn btn-outline-primary');
   updateButton.setAttribute('onclick', 'updateRoomType(this)');
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
   deleteButton.setAttribute('onclick', 'deleteRoomType(this)');
   deleteButton.setAttribute('data-id', data.id);
   deleteButton.innerHTML = '<i class="nav-icon fas fa-trash"></i>';
   buttonsTd.appendChild(deleteButton);


    $("#body-table").append(clone);

    var clone2 = activateTemplate('#item-card');
    clone2.querySelector("[data-id]").innerHTML = data.id;
    clone2.querySelector("[data-document_type]").innerHTML = data.document_type;
    clone2.querySelector("[data-document]").innerHTML = data.document;
    clone2.querySelector("[data-name]").innerHTML = data.name;
    clone2.querySelector("[data-lastname]").innerHTML = data.lastname;
    clone2.querySelector("[data-phone]").innerHTML = data.phone;
    clone2.querySelector("[data-email]").innerHTML = data.email;
    clone2.querySelector("[data-birth]").innerHTML = data.birth;
    clone2.querySelector("[data-address]").innerHTML = data.address;

    
    $("#body-card").append(clone2);

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
