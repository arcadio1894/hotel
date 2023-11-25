@extends('layouts.appadmin')

@section('styles-plugins')
@endsection

@section('styles-dist')
@endsection

@section('styles-own')

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
        @if($lista and !$report)
            <h5 class="card-title col-7">Administra la lista de los {{$title}}</h5>
        @else
            @if($report)
                <h5 class="card-title col-7">Reporte de la lista de los {{$title}}</h5>
            @else
                <h5 class="card-title col-7">Administra la lista de los {{$title}}</h5>
            @endif
        @endif
        </div>
        <div class="d-flex justify-content-end col-2">
            @if($lista and !$report)
                <button type="button" class="btn btn-outline-success" onclick="cleanRoomType()">
                    <i class="fa fa-plus"></i> Nuevo
                </button>
            @else
                @if($report)
                    <button type="button" class="btn btn-outline-info" onclick="exportarExcel()">
                        <i class="far fa-file-excel"></i> Descargar Excel
                    </button>
                @endif
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div id="tableExample2" data-list='{"valueNames":["id","document_type","document","name","lastname","phone","email","birth","address"],"page":10,"pagination":true}'>
        <div class="table-responsive scrollbar">
            <table class="table table-bordered table-striped fs--1 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort" data-sort="id">ID</th>
                        <th class="sort" data-sort="document_type">Tipo de documento</th>
                        <th class="sort" data-sort="document">Nro. Documento</th>
                        <th class="sort" data-sort="name">Nombre</th>
                        <th class="sort" data-sort="lastname">Apellidos</th>
                        <th class="sort" data-sort="phone">Telefono</th>
                        <th class="sort" data-sort="email">Email</th>
                        <th class="sort" data-sort="birth">Cumpleaños</th>
                        <th class="sort" data-sort="address">Dirección</th>

                        @if (!$report)
                            <th>Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="list">
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->document_type }}</td>
                        <td>{{ $customer->document }}</td>
                        <td>{{ $customer->name}}</td>
                        <td>{{ $customer->lastname }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->birth }}</td>
                        <td>{{ $customer->address}}</td>
                        @if (!$report)
                        <td>
                                @if ($customer->trashed())
                                        <button type="button" class="btn btn-outline-warning" onclick="restoreRoomType(this)" data-id="{{ $customer->id }}">
                                            <i class="nav-icon fas fa-check"></i> Restaurar
                                        </button>
                                @else
                                        <button type="button" class="btn btn-outline-primary" onclick="updateRoomType(this)" data-id="{{ $customer->id }}" 
                                            data-document_type="{{ $customer->document_type }}" data-document="{{ $customer->document }}" data-name="{{ $customer->name }}"
                                            data-lastname="{{ $customer->lastname }}" data-phone="{{ $customer->phone }}" data-email="{{ $customer->email }}" 
                                            data-birth="{{ $customer->birth }}" data-address="{{ $customer->address }}">
                                            <i class="nav-icon fas fa-pen"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" onclick="deleteRoomType(this)" data-id="{{ $customer->id }}">
                                            <i class="nav-icon fas fa-trash"></i>
                                        </button>
                                @endif

                        </td>
                        @endif
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
                            <select name="document_type" id="document_type">
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
                            <label for="name">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" >
                        </div>
                        <div class="form-group">
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
                            <label for="birth">Cumpleaños <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="birth" name="birth" >
                        </div>
                        <div class="form-group">
                            <label for="address">Dirección <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" name="address" >
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

@section('plugins')

@endsection

@section('scripts')
    <script src="{{asset('js/customer/index.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script>
        var csrfToken = "{{ csrf_token() }}";
    </script>
@endsection