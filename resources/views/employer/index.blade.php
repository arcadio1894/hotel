@extends('layouts.appadmin')

@section('styles-plugins')

@endsection

@section('styles-dist')

@endsection

@section('styles-own')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.28/dist/sweetalert2.min.css" rel="stylesheet">

@endsection

@section('plugins')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.28/dist/sweetalert2.all.min.js"></script>
@endsection

@section('scripts')

    <script src="{{asset('js/employer/index.js')}}"></script>
    <script>
        var csrfToken = "{{ csrf_token() }}";
    </script>
@endsection

@section('page-header')
    <h3 class="m-0 text-dark">Listado de Empleados</h3>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
        <li class="breadcrumb-item active">Empleados</li>
    </ol>
@endsection

@section('page-title')
    <div class="row">
        <div class="col-10">
        <h5 class="card-title col-7">Listado de Empleados Registrados</h5>
        </div>
        <div class="d-flex justify-content-end col-2">
        <button type="button" class="btn btn-outline-success" onclick="addEmployer()">
            <i class="fa fa-plus"></i> Nuevo
        </button>
        </div>
    </div>
@endsection

@section('content')
    <div id="tableExample2" data-list='{"valueNames":["id","name","lastname","position_id","dni","address","birth","phone"],"page":5,"pagination":true}'>
        <div class="table-responsive scrollbar">
            <table class="table table-bordered table-striped fs--1 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort" data-sort="id">ID</th>
                        <th class="sort" data-sort="name">Nombres</th>
                        <th class="sort" data-sort="lastname">Apellidos</th>
                        <th class="sort" data-sort="position_id">Puesto</th>
                        <th class="sort" data-sort="dni">Dni</th>
                        <th class="sort" data-sort="address">Dirección</th>
                        <th class="sort" data-sort="birth">Nacimiento</th>
                        <th class="sort" data-sort="phone">Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="list">
                @foreach ($employers as $employer)
                    <tr>
                        <td>{{ $employer->id }}</td>
                        <td>{{ $employer->name }}</td>
                        <td>{{ $employer->lastname }}</td>
                        <td>{{ $employer->position->name }}</td>
                        <td>{{ $employer->dni }}</td>
                        <td>{{ $employer->address }}</td>
                        <td>{{ $employer->birth }}</td>
                        <td>{{ $employer->phone }}</td>
                        <td>
                            @if ($employer->trashed())
                                    <button type="button" class="btn btn-outline-warning" onclick="restoreEmployer(this)" data-id="{{ $employer->id }}">
                                        <i class="nav-icon fas fa-trash"></i> Restaurar
                                    </button>
                            @else
                                    <button type="button" class="btn btn-outline-primary" onclick="updateEmployer(this)" data-id="{{ $employer->id }}" data-name="{{ $employer->name }}" data-lastname="{{ $employer->lastname }}" data-position_id="{{ $employer->position->id  }}" data-dni="{{ $employer->dni }}" data-address="{{ $employer->address }}" data-birth="{{ $employer->birth }}" data-phone="{{ $employer->phone }}">
                                        <i class="nav-icon fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" onclick="deleteEmployer(this)" data-id="{{ $employer->id }}">
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
    <div class="modal fade" id="employerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Datos del Empleado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="employerForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombres <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastname" name="lastname">
                        </div>
                        <div class="form-group">
                            <label for="position_id">Puesto <span class="text-danger">*</span></label>
                            <select class="form-select mb-3" aria-label="position_id" id="position_id" name="position_id" required="">
                                <option value="">-Seleccione-</option>
                                <?php foreach ($positions as $position) { ?>
                                <option value="<?= $position->id ?>" <?= ((!empty($employer->position_id) && $employer->position_id== $position->id ) ? 'selected=""' : "") ?>><?= $position->name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dni">DNI <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="dni" name="dni">
                        </div>
                        <div class="form-group">
                            <label for="address">Dirección <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" name="address">
                        </div>
                        <div class="form-group">
                            <label for="birth">Fecha de Nacimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="birth" name="birth">
                        </div>
                        <div class="form-group">
                            <label for="phone">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone">
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
