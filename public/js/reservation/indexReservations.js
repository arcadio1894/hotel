$(document).ready(function () {

    getDataOperations(1);
    $tipo = $('#tipo').val();
    console.log($tipo);

    $(document).on('click', '[data-item]', showData);
    $("#btn-search").on('click', showDataSeach);

    $('#document_type').change(function () {
        // Obtener el valor seleccionado
        var selectedValue = $(this).val();
        // Modificar el label y ocultar o mostrar el input de lastname según la opción seleccionada
        if (selectedValue === 'RUC') {
            //$('#exampleModalLabel').text('Datos del Cliente Empresarial');
            $('#name-label').text('Razon Social');
            $('#lastname-group').hide();
            $('#lastname').val(null);
            $('#birth-label').text('Fecha de Constitución');
        } else {
            //$('#exampleModalLabel').text('Datos del Cliente');
            $('#name-label').text('Nombre');
            $('#lastname-group').show();
            $('#birth-label').text('Fecha de Nacimiento');
        }
    });


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
    var documentCliente = $('#inputDocumentCliente').val(); // Obtén el valor del input de documento cliente
    var name = $('#inputName').val(); // Obtén el valor del input de código de operación
    var type = $('#selectType').val();
    var tipo = $('#tipo').val();
    var status = $('#inputStatus').val();

    $.get('/home/reservas/get/get/data/'+$numberPage, {
        document_cliente: documentCliente,
        name: name,
        type: type,
        tipo: tipo,
        status: status,
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
    clone.querySelector("[data-code]").innerHTML = data.code;
    clone.querySelector("[data-customer_name]").innerHTML = data.customer_name + " " + data.customer_lastname;
    clone.querySelector("[data-employer_name]").innerHTML = data.employer_name + " " + data.employer_lastname;
    //clone.querySelector("[data-status_id]").innerHTML = data.status_id;
    clone.querySelector("[data-paymethod_id]").innerHTML = data.paymethod_id;
    clone.querySelector("[data-start_date]").innerHTML = data.start_date;
    clone.querySelector("[data-end_date]").innerHTML = data.end_date;
    clone.querySelector("[data-initial_pay]").innerHTML = data.initial_pay;
    clone.querySelector("[data-total_guest]").innerHTML = data.total_guest;
    if (data.status_id === 1) {
        clone.querySelector("[data-status]").innerHTML = "En espera";
        //clone.querySelector("[data-color]").classList.add("bg-success");
        //clone.querySelector("[data-color_text]").classList.add("text-light");
    } else if (data.status_id === 2) {
        clone.querySelector("[data-status]").innerHTML = "En Proceso";
        //clone.querySelector("[data-color]").classList.add("bg-danger");
        //clone.querySelector("[data-color_text]").classList.add("text-light");
    }else if (data.status_id === 3) {
        clone.querySelector("[data-status]").innerHTML = "Culminada";
        //clone.querySelector("[data-color]").classList.add("bg-secondary");
        //clone.querySelector("[data-color_text]").classList.add("text-light");
    }else if (data.status_id === 4) {
        clone.querySelector("[data-status]").innerHTML = "Cancelada";
        //clone.querySelector("[data-color]").classList.add("bg-warning");
        //clone.querySelector("[data-color_text]").classList.add("text-light");
    }

    if($('#tipo').val()==="lista"){
    // Configurar los botones en el nuevo td
    var buttonsTd = clone.querySelector("[data-buttons]");
    buttonsTd.innerHTML = ''; // Limpiar contenido existente

    var updateButton = document.createElement('button');
    updateButton.setAttribute('type', 'button');
    updateButton.setAttribute('class', 'btn btn-outline-primary');
    updateButton.setAttribute('onclick', 'updateReservation(this)');
    updateButton.setAttribute('data-id', data.id);
    updateButton.setAttribute('data-code', data.code);
    updateButton.setAttribute('data-customer_id', data.customer_id);
    updateButton.setAttribute('data-employer_id', data.employer_id);
    updateButton.setAttribute('data-status_id', data.status_id);
    updateButton.setAttribute('data-paymethod_id', data.paymethod_id);
    updateButton.setAttribute('data-start_date', data.start_date);
    updateButton.setAttribute('data-end_date', data.end_date);
    updateButton.setAttribute('data-initial_pay', data.initial_pay);
    updateButton.setAttribute('data-total_guest', data.total_guest);

    updateButton.innerHTML = '<i class="nav-icon fas fa-pen"></i>';
    buttonsTd.appendChild(updateButton);

    var checkButton = document.createElement('button');
    checkButton.setAttribute('type', 'button');
    checkButton.setAttribute('class', 'btn btn-outline-secondary');
    checkButton.setAttribute('onclick', 'checkDetail(this)');
    checkButton.setAttribute('data-id', data.id);
    checkButton.setAttribute('data-start_date', data.start_date);
    checkButton.setAttribute('data-end_date', data.end_date);
    checkButton.setAttribute('data-status_id', data.status_id);
    if(data.status_id==3 || data.status_id==4){
        checkButton.disabled=true;
    }

    checkButton.innerHTML = '<i class="nav-icon fas fa-sign-in-alt"></i> / <i class="nav-icon fas fa-sign-out-alt"></i>';
    
    buttonsTd.appendChild(checkButton);

    var deleteButton = document.createElement('button');
    deleteButton.setAttribute('type', 'button');
    deleteButton.setAttribute('class', 'btn btn-outline-danger');
    deleteButton.setAttribute('onclick', 'deleteReservation(this)');
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
