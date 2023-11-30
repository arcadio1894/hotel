@extends('layouts.appadmin')

@section('styles-plugins')

@endsection

@section('styles-dist')

@endsection

@section('styles-own')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.28/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('vendors/choices/choices.min.css')}}">
@endsection

@section('plugins')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.28/dist/sweetalert2.all.min.js"></script>
@endsection

@section('scripts')
    <script src="{{asset('vendors/choices/choices.min.js')}}"></script>
    <script src="{{asset('js/permission/index.js')}}"></script>
    <script src="{{asset('js/permission/pagination.js')}}"></script>
    <script>
        var csrfToken = "{{ csrf_token() }}";
    </script>
@endsection

@section('openPermission')
    show
@endsection

@section('activePermission')
    aria-expanded="true"
@endsection

@section('activeListPermission')
    @if($tipo=='lista')
        active
    @endif
@endsection

@section('page-header')
    <h3 class="m-0 text-dark">Listado de Permisos</h3>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
        <li class="breadcrumb-item active">Permisos</li>
    </ol>
@endsection

@section('page-title')
    <div class="row">
        <div class="col-10">
            <h5 class="card-title col-7">Administrar Listado de Permisos</h5>
        </div>
        <div class="d-flex justify-content-end col-2">
            @if($tipo == 'lista')
            <button type="button" class="btn btn-outline-success" onclick="addPermission()">
            <i class="fa fa-plus"></i> Nuevo Permiso
        </button>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <input type="hidden" id="tipo" value="{{ $tipo }}">
    <form action="#">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="position-relative w-md-400px me-md-2">
                        <span class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black" />
                            </svg>
                        </span>
                        <input type="text" class="form-control form-control-solid ps-10" id="inputName" name="search" value="" placeholder="Descripcion" />
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="button" id="btn-search" class="btn btn-primary me-5">Buscar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="d-flex flex-wrap flex-stack pb-3">
        <div class="d-flex flex-wrap align-items-center my-1">
            <h5 class=" me-5 my-1"><span id="numberItems"></span> Permisos encontrados
                <span class="text-gray-400 fs-2"> ↓</span>
            </h5>
        </div>
    </div>

    <div class="tab-content">
        <div id="kt_project_users_table_pane" >
            <div class="table-responsive scrollbar">
                <table id="kt_project_users_table" class="table table-bordered table-striped fs--1 mb-0">
                    <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort">ID</th>
                        <th class="sort">Nombre</th>
                        <th class="sort">Descripcion</th>
                        <th class="sort">Acciones</th>
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

        <div class="d-flex flex-stack flex-wrap pt-1">
            <div class="fw-bold text-gray-700" id="textPagination"></div>
            <ul class="pagination" style="margin-left: auto" id="pagination">
            </ul>
        </div>
    </div>


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
        <tr>
            <td data-id></td>
            <td data-name></td>
            <td data-description></td>
            @if($tipo=='lista' or $tipo =='eliminados')
                <td class="text-end" data-buttons>
                </td>
            @endif
        </tr>
        <!--end::Col-->
    </template>
    <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Datos del Permiso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="permissionForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="description" name="description">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="id" name="id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="guardar" onclick="save()">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
