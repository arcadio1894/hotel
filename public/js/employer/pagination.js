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
    var documentEmployer = $('#inputDocumentEmployer').val(); // Obtén el valor del input de documento cliente
    var name = $('#inputName').val(); // Obtén el valor del input de código de operación
    var tipo = $('#tipo').val();

    $.get('/home/employers/get/data/'+$numberPage, {
        document_employer: documentEmployer,
        name: name,
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
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' empleados');
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
    clone.querySelector("[data-name]").innerHTML = data.name;
    clone.querySelector("[data-lastname]").innerHTML = data.lastname;
    clone.querySelector("[data-position_name]").innerHTML = data.position_name;
    clone.querySelector("[data-dni]").innerHTML = data.dni;
    clone.querySelector("[data-address]").innerHTML = data.address;
    clone.querySelector("[data-email]").innerHTML = data.email;
    clone.querySelector("[data-birth]").innerHTML = data.birth;
    clone.querySelector("[data-phone]").innerHTML = data.phone;

    if($('#tipo').val()==="lista"){
    // Configurar los botones en el nuevo td
    var buttonsTd = clone.querySelector("[data-buttons]");
    buttonsTd.innerHTML = ''; // Limpiar contenido existente

    var updateButton = document.createElement('button');
    updateButton.setAttribute('type', 'button');
    updateButton.setAttribute('class', 'btn btn-outline-primary');
    updateButton.setAttribute('onclick', 'updateEmployer(this)');
    updateButton.setAttribute('data-id', data.id);
    updateButton.setAttribute('data-name', data.name);
    updateButton.setAttribute('data-lastname', data.lastname);
    updateButton.setAttribute('data-position_id', data.position_id);
    updateButton.setAttribute('data-dni', data.dni);
    updateButton.setAttribute('data-address', data.address);
    updateButton.setAttribute('data-email', data.email);
    updateButton.setAttribute('data-birth', data.birth);
    updateButton.setAttribute('data-phone', data.phone);

    updateButton.innerHTML = '<i class="nav-icon fas fa-pen"></i>';
    buttonsTd.appendChild(updateButton);

    var deleteButton = document.createElement('button');
    deleteButton.setAttribute('type', 'button');
    deleteButton.setAttribute('class', 'btn btn-outline-danger');
    deleteButton.setAttribute('onclick', 'deleteEmployer(this)');
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
            restoreButton.setAttribute('onclick', 'restoreEmployer(this)');
            restoreButton.setAttribute('data-id', data.id);
            restoreButton.innerHTML = '<i class="nav-icon fas fa-check"></i>';
            buttonsTd.appendChild(restoreButton);
        }
    }

    $("#body-table").append(clone);

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
