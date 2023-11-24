@extends('layouts.appadmin')

@section('styles-plugins')
@endsection

@section('styles-dist')
@endsection

@section('styles-own')
@endsection

@section('plugins')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.28/dist/sweetalert2.all.min.js"></script>
@endsection

@section('scripts')

    <script src="{{asset('js/habitacion/season.js')}}"></script>
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
        <h5 class="card-title col-7">Administra la lista de {{$title}}</h5>
        </div>
        <div class="d-flex justify-content-end col-2">
            @if ($lista)
                <button type="button" class="btn btn-outline-success" onclick="cleanSeason()">
                    <i class="fa fa-plus"></i> Nuevo
                </button>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div id="tableExample2" data-list='{"valueNames":["id","name","start_date","end_date"],"page":5,"pagination":true}'>
        <div class="table-responsive scrollbar">
            <table class="table table-bordered table-striped fs--1 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort" data-sort="id">ID</th>
                        <th class="sort" data-sort="name">Nombre</th>
                        <th class="sort" data-sort="start_date">Fecha de Inicio</th>
                        <th class="sort" data-sort="end_date">Fecha de Fin</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="list">
                @foreach ($seasons as $season)
                    <tr>
                        <td>{{ $season->id }}</td>
                        <td>{{ $season->name }}</td>
                        <td>{{ $season->start_date }}</td>
                        <td>{{ $season->end_date }}</td>
                        <td>
                            @if ($season->trashed())
                                    <button type="button" class="btn btn-outline-warning" onclick="restoreSeason(this)" data-id="{{ $season->id }}">
                                        <i class="nav-icon fas fa-check"></i> Restaurar
                                    </button>
                            @else
                                    <button type="button" class="btn btn-outline-primary" onclick="updateSeason(this)" data-id="{{ $season->id }}" data-name="{{ $season->name }}" data-start_date="{{ $season->start_date }}" data-end_date="{{ $season->end_date }}">
                                        <i class="nav-icon fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" onclick="deleteSeason(this)" data-id="{{ $season->id }}">
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
    <div class="modal fade" id="seasonModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Datos de la temporada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form id="seasonForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" >
                        </div>
                        <div class="form-group">
                            <label for="start_date">Fecha de Inicio <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="start_date" name="start_date" >
                        </div>
                        <div class="form-group">
                            <label for="end_date">Fecha de Fin <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="end_date" name="end_date" >
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="id" name="id">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="guardar" onclick="saveSeason()">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
