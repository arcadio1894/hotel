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

    <script src="{{asset('js/habitacion/room.js')}}"></script>
    <script>
        var csrfToken = "{{ csrf_token() }}";
        var imageUrl = "{{ asset('images/rooms') }}/";
    </script>
@endsection

@section('openRoom')
    show
@endsection

@section('activeRoom')
    aria-expanded="true"
@endsection

@section('activeListRoom')
    @if($tipo=='Lista')
        active
    @endif
@endsection

@section('activeDeleteRoom')
    @if($tipo=='Eliminados')
        active
    @endif
@endsection

@section('title')
    {{$title}}
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
        <h5 class="card-title col-7">Administra la lista de las {{$title}}</h5>
        </div>
        <div class="d-flex justify-content-end col-2">
            @if($tipo=='Lista')
                <button type="button" class="btn btn-outline-success" onclick="cleanRoom()">
                    <i class="fa fa-plus"></i> Nuevo
                </button>
            @endif
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
                            <input type="text" placeholder="Tipo de habitación" id="inputType" class="form-control rounded-0 typeahead roomTypeahead">
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
                                    <label class="form-label fw-bolder text-dark">Piso</label>
                                    <!--begin::Select-->
                                    <input type="text" class="form-control form-control form-control-solid" name="inputLevel" id="inputLevel" />
                                    <!--end::Select-->
                                </div>
                                <!--end::Col-->
                                <div class="col">
                                    <label class="form-label fw-bolder text-dark">Número</label>
                                    <!--begin::Select-->
                                    <input type="number" class="form-control form-control form-control-solid" name="inputNumber" id="inputNumber" />
                                    <!--end::Select-->
                                </div>
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
                <span class="text-gray-400 fs-1">ordenadas por piso ↓</span>
            </h5>
        </div>
        <!--end::Title-->
    </div>

    <div class="">
        <div class="row" id="body-card"></div>
        <div class="d-flex flex-stack flex-wrap pt-1">
            <div class="fw-bold text-gray-700" id="textPagination"></div>
            <!--begin::Pages-->
            <ul class="pagination" style="margin-left: auto" id="pagination">

            </ul>
            <!--end::Pages-->
        </div>
    </div>


    <template id="item-card">
        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch pb-4">
            <div class="card d-flex flex-row col-md-12" data-color data-color_text>
                <!-- Imagen en la izquierda -->
                <div class="card-img-left">
                    <img data-image src="" alt="" class="img-circle img-fluidcpt-3">
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div style="display: none;" data-id></div>
                            <h2 class="lead text-center"><b data-type_room></b> <b data-level> </b><b data-number></b></h2>
                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                <li class="small"><span class="fa-li"><i class="fas fa-book"></i></span>Descripción: <span data-description></span></li>
                                <li class="small"><span class="fa-li"><i class="fas fa-check"></i></span>Piso: <span data-nivel></span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 offset-2 text-center mt-3">
                            <!-- Botones -->
                            <div class="text-center">
                                <a data-buttons></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </template>

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

    @if($tipo=='Lista')
    <div class="modal fade" id="roomModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Datos del tipo de habitación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="roomForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="room_type">Tipo de Habitación <span class="text-danger">*</span></label>
                            <select class="form-select " id="room_type" name="room_type" required data-options='{"removeItemButton":true,"placeholder":true}'>
                                <option value="">-Seleccione-</option>
                                @foreach ($types as $name => $id)
                                    <option value="{{ $id}}">{{ $name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="level">Piso <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="level" name="level" >
                        </div>

                        <div class="form-group">
                            <label for="number">Número <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="number" name="number" >
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">Estado<span class="text-danger">*</span></label>
                            <select class="form-select " id="status" name="status" required data-options='{"removeItemButton":true,"placeholder":true}'>
                                <option value="">-Seleccione-</option>
                                @foreach ($states as $id => $name)
                                    <option value="{{ $id}}">{{ $name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Imagen</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                            <img id="preview" src="#" alt="Vista previa de la imagen" style="display:none; max-width: 200px; margin-top: 10px;">
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" id="id" name="id">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="guardar" onclick="saveRoom()">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection
