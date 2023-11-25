@extends('layouts.appadmin')

@section('styles-plugins')
@endsection

@section('styles-dist')
@endsection

@section('styles-own')

@endsection

@section('page-header')
    <h3 class="m-0 text-dark">Clientes</h3>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
        <li class="breadcrumb-item active">Lista de Clientes</li>
    </ol>
@endsection

@section('page-title')
    <div class="row">
        <div class="col-10">
            
                <h5 class="card-title col-7">Administra la lista de los Clientes</h5>
        </div>
        <div class="d-flex justify-content-end col-2">
            
            <button type="button" class="btn btn-outline-success" onclick="cleanRoomType()">
                <i class="fa fa-plus"></i> Nuevo
            </button>
        
            <button type="button" class="btn btn-outline-info" onclick="exportarExcel()">
                    <i class="far fa-file-excel"></i> Descargar Excel
            </button>
        </div>
    </div>
@endsection

@section('content')
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
                        <input type="text" class="form-control form-control-solid ps-10" id="inputCodigoOperacion" name="search" value="" placeholder="Nombre" />
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
                                    <select id="selectBanco" class="form-select form-select-solid" data-control="select2" data-placeholder="Seleccione un Tipo de Documento" data-hide-search="true">
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
        <!--begin::Controls-->
        <div class="d-flex flex-wrap my-1">
            <!--begin::Tab nav-->
            <ul class="nav nav-pills me-6 mb-2 mb-sm-0">
                <li class="nav-item m-0">
                    <a class="btn btn-sm btn-icon btn-light btn-color-muted btn-active-primary active" data-bs-toggle="tab" href="#kt_project_users_table_pane">
                        <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="black" />
                                <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </a>
                </li>
                <li class="nav-item m-0">
                    <a class="btn btn-sm btn-icon btn-light btn-color-muted btn-active-primary me-3 " data-bs-toggle="tab" href="#kt_project_users_card_pane">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen024.svg-->
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="5" y="5" width="5" height="5" rx="1" fill="#000000" />
                                    <rect x="14" y="5" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
                                    <rect x="5" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
                                    <rect x="14" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
                                </g>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </a>
                </li>
            </ul>
            <!--end::Tab nav-->
        </div>
        <!--end::Controls-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Tab Content-->
    <div class="tab-content">
        <!--begin::Tab pane-->
        <div id="kt_project_users_card_pane" class="tab-pane fade">
            <!--begin::Row-->
            <div class="row g-6 g-xl-9" id="body-card">

            </div>
            <!--end::Row-->
        </div>
        <!--end::Tab pane-->
        <!--begin::Tab pane-->
        <div id="kt_project_users_table_pane" >
            <div class="table-responsive scrollbar">
                <table id="kt_project_users_table" class="table table-bordered table-striped fs--1 mb-0">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th class="sort">ID</th>
                            <th class="sort">Tipo de Documento</th>
                            <th class="sort">Documento</th>
                            <th class="sort">Nombres</th>
                            <th class="sort">Apellidos</th>
                            <th class="sort">Telefono</th>
                            <th class="sort">Email</th>
                            <th class="sort">Cumpleaños</th>
                            <th class="sort">Direccíón</th>
                            <th class="sort">Acciones</th>
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

    <template id="item-card">
        <!--begin::Col-->
        <div class="col-md-4 col-xxl-4">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                    <h4 class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0">ID</h4>
                    <h5 class="fw-bold text-gray-400 mb-6" data-id></h5>
                    <h4 class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0">Tipo de Documento</h4>
                    <h5 class="fw-bold text-gray-400 mb-6" data-document_type></h5>
                    <h4 class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0">Documento</h4>
                    <h5 class="fw-bold text-gray-400 mb-6" data-document></h5>
                    <h4 class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0">Nombres</h4>
                    <h5 class="fw-bold text-gray-400 mb-6" data-name></h5>
                    <h4 class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0">Apellidos</h4>
                    <h5 class="fw-bold text-gray-400 mb-6" data-lastname></h5>
                    <h4 class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0">Telefono</h4>
                    <h5 class="fw-bold text-gray-400 mb-6" data-phone></h5>
                    <h4 class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0">Email</h4>
                    <h5 class="fw-bold text-gray-400 mb-6" data-email></h5>
                    <h4 class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0">Cumpleaños</h4>
                    <h5 class="fw-bold text-gray-400 mb-6" data-birth></h5>
                    <h4 class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0">Dirección</h4>
                    <h5 class="fw-bold text-gray-400 mb-6" data-address></h5>
                    <div data-buttons>

                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Col-->
    </template>

    <template id="item-table">
        <!--begin::Col-->
        <tr>
            <td data-id></td>
            <td data-document_type></td>
            <td data-document></td>
            <td data-name></td>
            <td data-lastname></td>
            <td data-phone></td>
            <td data-email></td>
            <td data-birth></td>
            <td data-address></td>
            <td class="text-end" data-buttons>

            </td>
        </tr>
        <!--end::Col-->
    </template>



    

@endsection

@section('plugins')

@endsection

@section('scripts')
    <script src="{{asset('js/customer/index.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script>
        var csrfToken = "{{ csrf_token() }}";
    </script>
@endsection