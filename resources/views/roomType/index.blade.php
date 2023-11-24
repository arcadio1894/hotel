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
    <script src="{{asset('js/habitacion/roomType.js')}}"></script>
    <script>
        var csrfToken = "{{ csrf_token() }}";
    </script>
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
        <div class="d-flex justify-content-end col-2">
            @if($lista)
                <button type="button" class="btn btn-outline-success" onclick="cleanRoomType()">
                    <i class="fa fa-plus"></i> Nuevo
                </button>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div id="tableExample2" data-list='{"valueNames":["id","name","description","capacity"],"page":5,"pagination":true}'>
        <div class="table-responsive scrollbar">
            <table class="table table-bordered table-striped fs--1 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort" data-sort="id">ID</th>
                        <th class="sort" data-sort="name">Nombre</th>
                        <th class="sort" data-sort="description">Descripción</th>
                        <th class="sort" data-sort="capacity">Capacidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="list">
                @foreach ($roomTypes as $roomType)
                    <tr>
                        <td>{{ $roomType->id }}</td>
                        <td>{{ $roomType->name }}</td>
                        <td>{{ $roomType->description }}</td>
                        <td>{{ $roomType->capacity }}</td>
                        <td>
                            @if ($roomType->trashed())
                                    <button type="button" class="btn btn-outline-warning" onclick="restoreRoomType(this)" data-id="{{ $roomType->id }}">
                                        <i class="nav-icon fas fa-check"></i> Restaurar
                                    </button>
                            @else
                                    <button type="button" class="btn btn-outline-primary" onclick="updateRoomType(this)" data-id="{{ $roomType->id }}" data-name="{{ $roomType->name }}" data-description="{{ $roomType->description }}" data-capacity="{{ $roomType->capacity }}">
                                        <i class="nav-icon fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" onclick="deleteRoomType(this)" data-id="{{ $roomType->id }}">
                                        <i class="nav-icon fas fa-trash"></i>
                                    </button>
                            @endif

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <button class="btn btn-sm btn-falcon-default me-1" type="button" title="Previous" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
            <ul class="pagination mb-0"></ul>
            <button class="btn btn-sm btn-falcon-default ms-1" type="button" title="Next" data-list-pagination="next"><span class="fas fa-chevron-right"> </span></button>
        </div>
    </div>
    <div class="modal fade" id="roomTypeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Datos del tipo de habitación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="roomTypeForm">
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
                            <button type="button" class="btn btn-primary" id="guardar" onclick="saveRoomType()">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
