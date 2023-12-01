@extends('layouts.appadmin')

@section('styles-plugins')
@endsection

@section('styles-dist')
@endsection

@section('styles-own')
<link href="{{asset('admin/vendors/flatpickr/flatpickr.min.css')}}" rel="stylesheet" />
@endsection

@section('openReservation')
    menu-open
@endsection

@section('activeReservation')
    active
@endsection

@section('openUlReservation')
    show
@endsection
@if($tipo=='lista')
    @section('activeListReservation')
        active
    @endsection
@else
    @if($tipo=='eliminados')
        @section('activeDeletedReservation')
        active
        @endsection
    @else
        @section('activeReportReservation')
        active
        @endsection
    @endif

@endif

@section('title')
    Reservas
@endsection

@section('page-header')
    <h3 class="m-0 text-dark">Reservas</h3>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
        <li class="breadcrumb-item active">{{$tipo}} de Reservas</li>
    </ol>
@endsection

@section('page-title')
    <div class="row">
        <div class="col-10">
            
                <h5 class="card-title col-7">Administrar {{$tipo}} de los Reservas</h5>
        </div>
        <div class="d-flex justify-content-end col-2">
            @if($tipo == 'lista')
            <!--
            <button type="button" class="btn btn-outline-success" onclick="cleanReservation()">
                <i class="fa fa-plus"></i> Nuevo
            </button>
            -->
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
                            <!--
                            <span class="svg-icon svg-icon-3 svg-icon-gray-500 position-absolute top-50 translate-middle ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black" />
                                </svg>
                            </span>
                            -->
                            <!--end::Svg Icon-->
                            <label class="form-label fw-bolder text-dark">Tipo de Habitación</label>
                            <!--begin::Select-->
                            <select id="selectType" class="form-select form-select-solid" data-control="select2" data-placeholder="Seleccione un Tipo de Habitación" data-hide-search="true">
                                <option value=""></option>
                                @foreach( $room_types as $room_type )
                                    <option value="{{ $room_type->id }}">{{ $room_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Input group-->
                        <!--begin:Action-->
                        <div>
                            <label>&nbsp;</label><br>
                            <button type="button" id="btn-search" class="btn btn-primary me-5">Buscar</button>
                            <!--
                            <div class="btn-group" role="group" aria-label="Basic example" hidden>
                                <button class="btn btn-secondary" id="btn-search" type="button" value="">Todas</button>
                                @foreach( $room_types as $room_type )
                                    <button class="btn btn-secondary" id="btn-search" type="button" value="{{ $room_type->id }}">{{ $room_type->name }}</button>
                                @endforeach
                            </div>
                            -->
                        </div>
                        <!--end:Action-->
                    </div>
                    <!--end::Compact form-->




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
                <h5 class=" me-5 my-1"><span id="numberItems"></span> Habitaciones encontradas
                    <span class="text-gray-400 fs-2">por fecha de creación ↓</span>
                </h5>
            </div>
            <!--end::Title-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Tab Content-->
        <div class="tab-content">
                    <!--begin::Tab pane-->
            <div id="kt_project_users_card_pane" class="tab-pane fade show active">
                <!--begin::Row-->
                <div class="row g-9 g-xl-6" id="body-card">


                </div>
                <!--end::Row-->
            </div>

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
            <!--<div class="col-md-4 col-xxl-4">-->
            <div class="col-md-3">
                <!--begin::Card-->
                    <!--begin::Card body-->
                    <div class="card bg-success" style="width: 22rem;">
                        <!--<img src="https://images.unsplash.com/photo-1579705790929-7b93d00463f6?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1050&q=80" class="card-img-top" alt="...">-->
                        <div class="card-body">
                            <h5 class="card-title">
                                <span style="display: inline;">
                                    Habitación 
                                    <p data-number style="display: inline;"></p> 
                                    - 
                                    <p data-room_type_name style="display: inline;"></p>
                                    <p data-room_type_id hidden></p>
                                </span>
                            </h5>

                            <p class="card-text">Descripción de la habitación aquí...
                                    <p data-id hidden></p>
                                <span style="display: inline;">
                                    Nivel: 
                                    <p data-level style="display: inline;"></p>
                                </span>
                            </p>
                            <p class="card-text">
                                <p data-id hidden></p>
                                <span style="display: inline;">
                                    Estado: 
                                    <p data-status style="display: inline;"></p>
                                </span>
                            </p>
                            <button type="button" class="btn btn-outline-primary" onclick="cleanCustomer()">
                                <i class="fa fa-plus"></i> 
                            </button>
                        </div>
                    </div>
                    <!--end::Card body-->
                <!--end::Card-->
            </div>
            <!--end::Col-->
        </template>

        <template id="item-table">
            <!--begin::Col-->
            <tr>
                <td data-id></td>
                <td data-room_type_id></td>
                <td data-room_type_name></td>
                <td data-level></td>
                <td data-number></td>
                <td data-status></td>
                @if($tipo=='lista' or $tipo =='eliminados')
                <td class="text-end" data-buttons>

                </td>
                @endif
            </tr>
            <!--end::Col-->
        </template>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Creación de Reservación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <form class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label" for="document">Documento</label>
                            <input class="form-control" id="document" type="text" />
                        </div>
                        <div class="col-md-6">
                            <label>&nbsp;</label><br>
                            <button type="button" id="buscarBtn" class="btn btn-primary">Buscar</button>
                        </div>
                    <form>
                    
                        
                    <form class="row g-3" id="reservationForm">
                        @csrf
                        <div class="col-md-12" hidden>
                            <label class="form-label" for="id">ID</label>
                            <input class="form-control" id="id" type="text"/>
                        </div>

                        <div class="col-md-8 d-none" id="inputName">
                            <label class="form-label" for="name">Nombres</label>
                            <input class="form-control" id="name" type="text" placeholder="Pablito"/>
                        </div>
                        <div class="col-md-4 d-none" id="inputPhone" >
                            <label class="form-label" for="phone">Teléfono</label>
                            <input class="form-control" id="phone" type="text" placeholder="987654321"/>
                        </div>


                        <div class="col-md-4">
                            <label class="form-label" for="code">Codigo de Reserva</label>
                            <input class="form-control" id="code" type="text" placeholder="RS-00000" readonly/>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="startdate">Fecha de Inicio</label>

                            <input class="form-control datetimepicker" id="startdate" type="text" placeholder="dd/mm/yy HH:ii" data-options='{"enableTime":true,"dateFormat":"d/m/y H:i","disableMobile":true}' />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="enddate">Fecha de Fin</label>
               
                            <input class="form-control datetimepicker" id="enddate" type="text" placeholder="dd/mm/yy HH:ii" data-options='{"enableTime":true,"dateFormat":"d/m/y H:i","disableMobile":true}' />
                            <span id="error-message" style="color: red;"></span>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="status">Estado</label>
                            <input class="form-control" id="status" type="text" placeholder="libre" readonly/>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="initialpay">Pago Inicial</label>
                            <input class="form-control" id="initialpay" type="text" placeholder="100" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="totalguest">Numero de Personas</label>
                            <input class="form-control" id="totalguest" type="text" placeholder="2" />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="paymethod">Método de Pago</label>
                            <select class="form-select" id="paymethod">
                                <option selected="selected">Elegir</option>
                                @foreach($paymethods as $paymethod)
                                    <option value= "{{$paymethod->id}}">{{$paymethod->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12" hidden>
                            <label class="form-label" for="employeerid">Atendido por:</label>
                            <input class="form-control" id="employeerid" type="text" value={{ $user->id}} readonly/>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="employeername">Atendido por:</label>
                            <input class="form-control" id="employeername" type="text" value= {{$user->name}} readonly />
                        </div>

                      </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Crear Reservación</button>
                </div>
            </div>
        </div>
    </div>


    

@endsection

@section('plugins')

@endsection

@section('scripts')
    <script src="{{asset('js/reservation/index.js')}}"></script>
    <script src="{{ asset('js/reservation/all.js') }}"></script>
    <script src="{{asset('admin/assets/js/flatpickr.js')}}"></script>
    <!-- Incluye moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script>
        var csrfToken = "{{ csrf_token() }}";
    </script>
@endsection