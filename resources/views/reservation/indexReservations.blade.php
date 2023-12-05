@extends('layouts.appadmin')

@section('styles-plugins')
<link href="{{asset('admin/vendors/flatpickr/flatpickr.min.css')}}" rel="stylesheet" />
@endsection

@section('styles-dist')
@endsection

@section('styles-own')

@endsection

@section('openReservation')
    menu-open
@endsection

@section('activeReservation')
    active
@endsection

@section('openUlReservation')
    show
@endsection
@if($tipo=='lista')
    @section('activeListReservations')
        active
    @endsection
@else
    @if($tipo=='eliminados')
        @section('activeDeletedReservations')
        active
        @endsection
    @else
        @section('activeReportReservations')
        active
        @endsection
    @endif

@endif

@section('title')
    Reservas
@endsection

@section('page-header')
    <h3 class="m-0 text-dark">Reservas</h3>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
        <li class="breadcrumb-item active">{{$tipo}} de Reservas</li>
    </ol>
@endsection

@section('page-title')
    <div class="row">
        <div class="col-10">
            
                <h5 class="card-title col-7">Administrar {{$tipo}} de las Reservas</h5>
        </div>
        <div class="d-flex justify-content-end col-2">
            @if($tipo == 'lista')
            <button type="button" class="btn btn-outline-success" onclick="cleanReservations()">
                <i class="fa fa-plus"></i> Nuevo
            </button>
            @endif
            @if($tipo == 'reporte')
                <button type="button" class="btn btn-outline-info" onclick="exportarExcel()">
                        <i class="far fa-file-excel"></i> Descargar Excel
                </button>
            @endif
        </div>
    </div>
@endsection

@section('content')

    <input type="hidden" id="tipo" value="{{ $tipo }}">
    <!--begin::Card-->
    <!--begin::Form-->
    <form action="#">
        <!--begin::Card-->
        <div class="card mb-3">
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Compact form-->
                <div class="d-flex align-items-center">
                    <!--begin::Input group-->
                    <div class="position-relative w-md-400px me-md-2">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <input type="text" class="form-control form-control-solid ps-10" id="inputName" name="search" value="" placeholder="Nombre" />
                    </div>
                    <!--end::Input group-->
                    <!--begin:Action-->
                    <div class="d-flex align-items-center">
                        <button type="button" id="btn-search" class="btn btn-primary me-5">Buscar</button>
                        <a id="kt_horizontal_search_advanced_link" class="btn btn-link" data-bs-toggle="collapse" href="#kt_advanced_search_form">Búsqueda avanzada</a>
                    </div>
                    <!--end:Action-->
                </div>
                <!--end::Compact form-->
                <!--begin::Advance form-->
                <div class="collapse" id="kt_advanced_search_form">
                    <!--begin::Separator-->
                    <div class="separator separator-dashed mt-2 mb-1"></div>
                    <!--end::Separator-->
                    <!--begin::Row-->
                    <div class="row ">
                        <!--begin::Col-->
                        <div class="col">
                            <!--begin::Row-->
                            <div class="row ">
                                <!--begin::Col-->
                                <div class="col">
                                    <label class="form-label fw-bolder text-dark">Tipo de Documento</label>
                                    <!--begin::Select-->
                                    <select id="selectType" class="form-select form-select-solid" data-control="select2" data-placeholder="Seleccione un Tipo de Documento" data-hide-search="true">
                                        <option value=""></option>
                                        @foreach( $documentTypes as $documentType )
                                            <option value="{{ $documentType ->name }}">{{ $documentType ->name }}</option>
                                        @endforeach
                                    </select>
                                    <!--end::Select-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col">
                                    <label class="form-label fw-bolder text-dark">Documento</label>
                                    <input type="text" class="form-control form-control form-control-solid" name="inputDocumentCliente" id="inputDocumentCliente" />
                                </div>
                                <div class="col">
                                    <label class="form-label fw-bolder text-dark">Método de pago</label>
                                    <!--begin::Select-->
                                    <select id="selectMethod" class="form-select form-select-solid" data-control="select2" data-placeholder="Seleccione un método de pago" data-hide-search="true">
                                        <option value=""></option>
                                        @foreach( $paymethods as $paymethod )
                                            <option value="{{ $paymethod->id }}">{{ $paymethod->name }}</option>
                                        @endforeach
                                    </select>
                                    <!--end::Select-->
                                </div>
                                <div class="col">
                                    <label class="form-label fw-bolder text-dark">Estado de Reserva</label>
                                    <!--begin::Select-->
                                    <select id="inputStatus" class="form-select form-select-solid" data-control="select2" data-placeholder="Seleccione un Estado de reserva" data-hide-search="true">
                                        <option value=""></option>
                                        @foreach( $states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    <!--end::Select-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Advance form-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </form>
    <!--end::Form-->


    <!--begin::Toolbar-->
    <div class="d-flex flex-wrap flex-stack pb-3">
        <!--begin::Title-->
        <div class="d-flex flex-wrap align-items-center my-1">
            <h5 class=" me-5 my-1"><span id="numberItems"></span> Clientes encontrados
                <span class="text-gray-400 fs-2">por fecha de creación ↓</span>
            </h5>
        </div>
        <!--end::Title-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Tab Content-->
    <div class="tab-content">
        <!--begin::Tab pane-->
        <div id="kt_project_users_table_pane" >
            <div class="table-responsive scrollbar">
                <table id="kt_project_users_table" class="table table-bordered table-striped fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="sort">ID</th>
                            <th class="sort">Código</th>
                            <th class="sort">Cliente</th>
                            <th class="sort">Empleado</th>
                            <th class="sort">Estado</th>
                            <th class="sort">paymethod_id</th>
                            <th class="sort">Pago inicial</th>
                            <th class="sort">Fecha de inicio</th>
                            <th class="sort">Fecha de fin</th>
                            <th class="sort">N° Huespedes</th>
                            @if($tipo=='lista' or $tipo =='eliminados')
                            <th class="sort">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="body-table" class="list">
    
                    </tbody>
                </table>
            </div>
    
        </div>
        <!--end::Tab pane-->
        <!--begin::Pagination-->
        <div class="d-flex flex-stack flex-wrap pt-1">
            <div class="fw-bold text-gray-700" id="textPagination"></div>
            <!--begin::Pages-->
            <ul class="pagination" style="margin-left: auto" id="pagination">

            </ul>
            <!--end::Pages-->
        </div>
        <!--end::Pagination-->
    </div>
    <!--end::Tab Content-->

    <template id="previous-page">
        <li class="page-item previous">
            <a href="#" class="page-link" data-item>
                <i class="previous"></i>
            </a>
        </li>
    </template>

    <template id="item-page">
        <li class="page-item" data-active>
            <a href="#" class="page-link" data-item="">5</a>
        </li>
    </template>

    <template id="next-page">
        <li class="page-item next">
            <a href="#" class="page-link" data-item>
                <i class="next"></i>
            </a>
        </li>
    </template>

    <template id="disabled-page">
        <li class="page-item disabled">
            <span class="page-link">...</span>
        </li>
    </template>



    <template id="item-table">
        <!--begin::Col-->
        <tr>
            <td data-id></td>
            <td data-code></td>
            <td data-customer_name></td>
            <td data-employer_name></td>
            <td data-status></td>
            <td data-paymethod_id></td>
            <td data-initial_pay></td>
            <td data-start_date></td>
            <td data-end_date></td>
            <td data-total_guest></td>
            @if($tipo=='lista' or $tipo =='eliminados')
            <td class="text-end" data-buttons>

            </td>
            @endif
        </tr>
        <!--end::Col-->
    </template>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Creación de Reservación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <form class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label" for="document">Documento</label>
                            <input class="form-control" id="document" type="text" />
                        </div>
                        <div class="col-md-6">
                            <label>&nbsp;</label><br>
                            <button type="button" id="buscarBtn" class="btn btn-primary">Buscar</button>
                        </div>
                    <form>
                    
                        
                    <form class="row g-3" id="reservationForm">
                        @csrf
                        <div class="col-md-12" hidden>
                            <label class="form-label" for="idCustomer">ID</label>
                            <input class="form-control" id="idCustomer" type="text"/>
                        </div>

                        <div class="col-md-8 d-none" id="inputName">
                            <label class="form-label" for="name">Nombres</label>
                            <input class="form-control" id="name" type="text" placeholder="Pablito"/>
                        </div>
                        <div class="col-md-4 d-none" id="inputPhone" >
                            <label class="form-label" for="phone">Teléfono</label>
                            <input class="form-control" id="phone" type="text" placeholder="987654321"/>
                        </div>


                        <div class="col-md-4">
                            <label class="form-label" for="code">Codigo de Reserva</label>
                            <input class="form-control" id="code" type="text" placeholder="RS-00000" readonly/>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="startdate">Fecha de Inicio</label>
                            <input class="form-control datetimepicker" id="startdate" type="text" placeholder="dd/mm/yy" data-options='{"disableMobile":true}' />
                            
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="enddate">Fecha de Fin</label>
                            <input class="form-control datetimepicker" id="enddate" type="text" placeholder="dd/mm/yy" data-options='{"disableMobile":true}' />
                            
                            <span id="error-message" style="color: red;"></span>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="status">Estado</label>
                            <input class="form-control" id="status" type="text" placeholder="libre" readonly/>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="initialpay">Pago Inicial</label>
                            <input class="form-control" id="initialpay" type="text" placeholder="100" readonly />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="totalguest">Numero de Personas</label>
                            <input class="form-control" id="totalguest" type="text" placeholder="2" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="paymethod">Método de Pago</label>
                            <select class="form-select" id="paymethod" disabled>
                                <option selected="selected">Elegir</option>
                                @foreach($paymethods as $paymethod)
                                    <option value= "{{$paymethod->id}}">{{$paymethod->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12" hidden>
                            <label class="form-label" for="employeerid">Atendido por:</label>
                            <input class="form-control" id="employeerid" type="text" value={{ $user->id}} readonly/>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="employeername">Atendido por:</label>
                            <input class="form-control" id="employeername" type="text" value= {{$user->name}} readonly />
                        </div>

                      </form>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="id" name="id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="guardar" onclick="saveReservations()">Crear Reservación</button>
                </div>
            </div>
        </div>
    </div>



    

@endsection

@section('plugins')

@endsection

@section('scripts')
    <script src="{{asset('js/reservation/indexReservations.js')}}"></script>
    <script src="{{ asset('js/reservation/allReservations.js') }}"></script>
    <script src="{{asset('admin/assets/js/flatpickr.js')}}"></script>
    <!-- Incluye moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script>
        var csrfToken = "{{ csrf_token() }}";
    </script>
@endsection