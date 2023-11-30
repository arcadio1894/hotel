@extends('layouts.appadmin')

@section('styles-plugins')
@endsection

@section('styles-dist')
@endsection

@section('styles-own')

@endsection

@section('plugins')

@endsection

@section('scripts')

    <script src="{{asset('js/habitacion/roomPrice.js')}}"></script>
    <script>
        var csrfToken = "{{ csrf_token() }}";
    </script>
@endsection

@section('openRoomPrice')
    show
@endsection

@section('activeRoomPrice')
    aria-expanded="true"
@endsection

@section('activeListRoomPrice')
        active
@endsection


@section('page-header')
    <h3 class="m-0 text-dark">{{$title}}</h3>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
        <li class="breadcrumb-item active">{{$title}}</li>
    </ol>
@endsection

@section('page-title')
    <div class="row">
        <div class="col-10">
        <h5 class="card-title col-7">Administra la lista de los {{$title}}</h5>
        </div>
    </div>
@endsection

@section('content')
    <input type="hidden" id="tipo" value="{{ $tipo }}">
    <form action="#">
        <div class="card card-primary mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" placeholder="Nombre de la temporada" id="inputNameSeason" class="form-control rounded-0 typeahead seasonTypeahead">
                        </div>
                    </div>
                    <div class="d-flex align-items-center col-md-4">
                        <button type="button" id="btn-search" class="btn btn-primary btn-block">Buscar</button>
                        <a id="kt_horizontal_search_advanced_link" class="btn btn-link" data-bs-toggle="collapse" href="#kt_advanced_search_form">Búsqueda avanzada</a>
                    </div>

                </div>
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
                                    <label class="form-label fw-bolder text-dark">Tipo de Habitación</label>
                                    <!--begin::Select-->
                                    <input type="text" class="form-control form-control form-control-solid" name="typeRoom" id="typeRoom" />
                                    <!--end::Select-->
                                </div>
                                <!--end::Col-->
                                <div class="col">
                                    <label class="form-label fw-bolder text-dark">Precio</label>
                                    <!--begin::Select-->
                                    <input type="number" class="form-control form-control form-control-solid" name="priceRoom" id="priceRoom" />
                                    <!--end::Select-->
                                </div>
                                <!--begin::Col-->
                                <div class="col">
                                    <label class="form-label fw-bolder text-dark">Duración</label>
                                    <select id="durationHoursRoom" name="durationHoursRoom" class="form-select form-select-solid" data-control="select2" data-placeholder="Seleccione las horas" data-hide-search="true">
                                        <option value=""></option>
                                        <option value="1">1 Hora</option>
                                        <option value="24">1 Día</option>
                                    </select>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
            </div>
        </div>
    </form>
    <div class="d-flex flex-wrap flex-stack pb-3">
        <!--begin::Title-->
        <div class="d-flex flex-wrap align-items-center my-1">
            <h5 class=" me-5 my-1"><span id="numberItems"></span> {{$title}} encontradas
                <span class="text-gray-400 fs-1">por precio ↓</span>
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
                        <th class="sort">Temporada</th>
                        <th class="sort">Tipo de Hábitación</th>
                        <th class="sort">Precio</th>
                        <th class="sort">Duración</th>
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
                <i class="previous"><</i>
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
                <i class="next">></i>
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
            <td data-season></td>
            <td data-type_room></td>
            <td data-price></td>
            <td data-duration_hours></td>
        </tr>
        <!--end::Col-->
    </template>

    <div class="modal fade" id="roomPriceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Datos del tipo de habitación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="roomPriceForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" >
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="capacity">Cantidad máxima de personas<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="capacity" name="capacity" >
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="id" name="id">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="guardar" onclick="saveRoomPrice()">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
