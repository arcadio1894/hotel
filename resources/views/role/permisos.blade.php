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
    <script src="{{asset('js/role/index.js')}}"></script>
    <script>
        var csrfToken = "{{ csrf_token() }}";
    </script>
@endsection

@section('openRole')
    show
@endsection

@section('activeRole')
    aria-expanded="true"
@endsection

@section('activeListRole')
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
        <li class="breadcrumb-item"><a href="{{route('roles.index')}}">Roles</a></li>
        <li class="breadcrumb-item active">Permisos</li>
    </ol>
@endsection

@section('page-title')
    <div class="row">
        <div class="col-10">
            <h5 class="card-title col-7">Administrar Asignación de Permisos</h5>
        </div>
    </div>
@endsection

@section('content')
    <input type="hidden" id="tipo" value="{{ $tipo }}">
    <form id="asignarEdit" class="form-horizontal" method="POST" data-url="{{ route('roles.savePermissions',$role->id) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="role_id" value="{{ $role->id }}">
        <div class="form-group row">
            <div class="col-md-6">
                <label for="name" class="col-12 col-form-label">Nombre del Rol <span class="text-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" placeholder="Ejm: admin" value="{{ $role->name }}">
                </div>
            </div>
            <div class="col-md-6">
                <label for="description" class="col-12 col-form-label">Descripción del Rol <span class="text-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="description" placeholder="Ejm: Administrador" value="{{ $role->description }}">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <label for="inputEmail3" class="col-sm-12 col-form-label">Permisos</label>
            </div>
        </div>
        <div class="row">
            @foreach($groups as $index => $group)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $group['name'] }}</h5>
                            <div class="info-box">
                                <div class="card-text">
                                    <div class="card">
                                        <div class="card-body btn-dark">
                                    <span class="info-box-icon">
                                        <input type="checkbox" class="form-check-input checkbox-select-all" data-group-id="{{ $index }}" id="checkbox-select-all-{{ $index }}">
                                        <label for="checkbox-select-all-{{ $index }}" class="ml-2">SELECCIONAR TODO</label>
                                    </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mt-2 ml-3">
                                    <div class="card-body">
                                        <h6 class="card-title"></h6>
                                        <div class="info-box-content">
                                            @foreach($permissions as $permission)
                                                @if(substr($permission->name, strpos($permission->name, '_')+1) === $group['group'])
                                                    <div class="form-check">
                                                        <input class="form-check-input group-checkbox-{{ $index }}" id="permission{{ $permission->id }}" type="checkbox" name="permissions[]" value="{{ $permission->id }}" {{ in_array($permission->name, $permissionsSelected) ? 'checked' : '' }}>
                                                        <label for="permission{{ $permission->id }}" class="form-check-label text-dark" style="font-weight: normal">{{ $permission->description }}</label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center">
            <button type="button" id="asignar" class="btn btn-outline-primary" onclick="asignarForm(this)">Guardar</button>
        </div>
    </form>
@endsection
