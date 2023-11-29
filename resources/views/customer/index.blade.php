@extends('layouts.appadmin')

@section('styles-plugins')
@endsection

@section('styles-dist')
@endsection

@section('styles-own')

@endsection

@section('openCustomer')
    menu-open
@endsection

@section('activeCustomer')
    active
@endsection

@section('openUlCustomer')
    show
@endsection
@if($tipo=='lista')
    @section('activeListCustomer')
        active
    @endsection
@else
    @if($tipo=='eliminados')
        @section('activeDeletedCustomer')
        active
        @endsection
    @else
        @section('activeReportCustomer')
        active
        @endsection
    @endif

@endif

@section('title')
    Clientes
@endsection

@section('page-header')
    <h3 class="m-0 text-dark">Clientes</h3>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
        <li class="breadcrumb-item active">{{$tipo}} de Clientes</li>
    </ol>
@endsection

@section('page-title')
    <div class="row">
        <div class="col-10">
            
                <h5 class="card-title col-7">Administrar {{$tipo}} de los Clientes</h5>
        </div>
        <div class="d-flex justify-content-end col-2">
            @if($tipo == 'lista')
            <button type="button" class="btn btn-outline-success" onclick="cleanCustomer()">
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
                                        @foreach( $document_types as $document_type )
                                            <option value="{{ $document_type }}">{{ $document_type }}</option>
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
                            <th class="sort">Tipo de Documento</th>
                            <th class="sort">Documento o RUC</th>
                            <th class="sort">Nombres y Apellidos o Razón Social</th>
                            <!--<th class="sort">Apellidos</th>-->
                            <th class="sort">Telefono</th>
                            <th class="sort">Email</th>
                            <th class="sort">Fecha</th>
                            <th class="sort">Direccíón</th>
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
            <td data-document_type></td>
            <td data-document></td>
            <td data-name></td>
            <!--<td data-lastname></td>-->
            <td data-phone></td>
            <td data-email></td>
            <td data-birth></td>
            <td data-address></td>
            @if($tipo=='lista' or $tipo =='eliminados')
            <td class="text-end" data-buttons>

            </td>
            @endif
        </tr>
        <!--end::Col-->
    </template>

    <div class="modal fade" id="roomTypeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Datos del Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="roomTypeForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="document_type">Tipo de Documento <span class="text-danger">*</span></label>
                            <select name="document_type" id="document_type" class="form-select">
                                @foreach ($document_types as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="document">Nro. Documento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="document" name="document" >
                        </div>
                        <div class="form-group">
                            <label for="name" id="name-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" >
                        </div>
                        <div class="form-group" id="lastname-group">
                            <label for="lastname">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastname" name="lastname" >
                        </div>
                        <div class="form-group">
                            <label for="phone">Telefono <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" >
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="email" name="email" >
                        </div>
                        <div class="form-group">
                            <label for="birth" id="birth-label">Cumpleaños <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="birth" name="birth" >
                        </div>
                        <div class="form-group">
                            <label for="address">Dirección <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" name="address" >
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="id" name="id">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="guardar" onclick="saveCustomer()">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    

@endsection

@section('plugins')

@endsection

@section('scripts')
    <script src="{{asset('js/customer/index.js')}}"></script>
    <script src="{{asset('js/customer/all.js')}}"></script>
    <!-- Incluye moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script>
        var csrfToken = "{{ csrf_token() }}";
    </script>
@endsection