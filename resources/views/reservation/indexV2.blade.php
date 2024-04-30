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

@section('activeListReservation')
    active
@endsection

@section('title')
    Reservas
@endsection

@section('page-header')
    <h3 class="m-0 text-dark">Reservas</h3>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
        <li class="breadcrumb-item active">Listado General de Reservas</li>
    </ol>
@endsection

@section('page-title')
    <div class="row">
        <div class="col-10">
            <h5 class="card-title col-7">Administrar lista de los Reservas</h5>
        </div>
        <div class="d-flex justify-content-end col-2">

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

                <!--begin::Input group-->
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label fw-bolder text-dark" for="room_type">Tipos de Habitación: </label>
                        <select class="form-select" id="room_type">
                            <option value="0" selected="selected">TODAS</option>
                            @foreach($room_types as $room_type)
                                <option value= "{{$room_type->id}}">{{ strtoupper($room_type->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bolder text-dark" for="state">&nbsp; Estados :&nbsp;</label>
                        <select class="form-select" id="state">
                            @foreach($arrayStates as $state)
                                @if ($state['value'] == 'd')
                                    <option value= "{{$state['value']}}" selected="selected">{{$state['display']}}</option>
                                @else
                                    <option value= "{{$state['value']}}">{{$state['display']}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bolder text-dark" for="dateSearch">&nbsp; Fecha :&nbsp;</label>

                        <div>
                            <input class="form-control datetimepicker" id="dateSearch" type="text" placeholder="dd/mm/yy" data-options='{"disableMobile":true}'/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bolder text-dark" for="dateSearch2">&nbsp; Hora :&nbsp;</label>

                        <div>
                            <input class="form-control datetimepicker2" id="dateSearch2" type="text"/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bolder text-dark" for="dateSearch">&nbsp;</label><br>

                        <button class="btn btn-primary" id="btn-search">Buscar</button>
                    </div>
                </div>

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
            <h5 class=" me-5 my-1"><span id="numberItems"></span> Habitaciones encontradas por
                <span class="text-gray-400 fs-2">Nivel ↓</span>
            </h5>
        </div>
        <!--end::Title-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Tab Content-->
    <div class="tab-content">
        <!--begin::Tab pane-->

        <!--begin::Row-->
        <div class="row" id="body-card">

        </div>
        <!--end::Row-->

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
        <div class="col-12 col-sm-6 col-md-3 pb-4">
            <!--begin::Card-->
                <!--begin::Card body-->
                <div class="card">
                    <!--<img src="https://images.unsplash.com/photo-1579705790929-7b93d00463f6?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1050&q=80" class="card-img-top" alt="...">-->
                    <div class="card-header" >
                        <h5 class="card-title">
                            <span style="display: inline;">
                                Habitación
                                <p data-number style="display: inline;"></p>
                                <p data-room_type_name style="display: inline;"></p>
                                <p data-room_type_id hidden></p>
                            </span>
                            <a href="#" target="_blank" data-button_reservation class="btn btn-light float-end"><i class="far fa-share-square"></i></a>
                        </h5>
                    </div>
                    <div class="card-body">
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
                        <p class="card-text">
                            <p data-id hidden></p>
                            <span style="display: inline;">
                                    Tiempo Faltante:
                                <p data-time style="display: inline;"></p>
                            </span>
                        </p>
                        <p data-buttons>
                            <!--
                            <button type="button" class="btn btn-outline-primary" onclick="cleanCustomer()">
                                <i class="fa fa-plus"></i>
                            </button>
                            -->
                        </p>
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
        </tr>
        <!--end::Col-->
    </template>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="modal fade" id="addReservationDetailModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Creación de Detalle de Reservación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    

                                        
                    <form class="row g-3" id="addReservationDetailForm">
                        @csrf
                        <h5 class="modal-title" >Reserva</h5>
                        <div class="col-md-12" hidden>
                            <label class="form-label" for="idCustomerAdd">ID</label>
                            <input class="form-control" id="idCustomerAdd" type="text"/>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="codeAdd">Codigo de Reserva</label>
                            <input class="form-control" id="codeAdd" type="text" placeholder="RS-00000" readonly/>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="startdateAdd">Fecha de Inicio</label>

                            <input class="form-control datetimepicker" id="startdateAdd" type="text" placeholder="dd/mm/yy" data-options='{"disableMobile":true}' disabled/>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="enddateAdd">Fecha de Fin</label>
               
                            <input class="form-control datetimepicker" id="enddateAdd" type="text" placeholder="dd/mm/yy" data-options='{"disableMobile":true}' disabled/>
                            <span id="error-message" style="color: red;"></span>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="statusAdd">Estado</label>
                            <input class="form-control" id="statusAdd" type="text" placeholder="libre" readonly/>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="initialpayAdd">Pago Inicial</label>
                            <input class="form-control" id="initialpayAdd" type="text" placeholder="100" readonly/>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="totalguestAdd">Total de Personas</label>
                            <input class="form-control" id="totalguestAdd" type="text" placeholder="2" readonly/>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="paymethodAdd">Método de Pago</label>
                            <select class="form-select" id="paymethodAdd" disabled>
                                <option selected="selected">Elegir</option>
                                @foreach($paymethods as $paymethod)
                                    <option value= "{{$paymethod->id}}">{{$paymethod->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12" hidden>
                            <label class="form-label" for="employeeridAdd">Atendido por:</label>
                            <input class="form-control" id="employeeridAdd" type="text" value={{ $user->id}} readonly/>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="employeernameAdd">Atendido por:</label>
                            <input class="form-control" id="employeernameAdd" type="text" value= {{$user->name}} readonly />
                        </div>

                        <h5 class="modal-title" >Detalles de Reserva</h5>

                        <div class="col-12" hidden>
                            <label class="form-label" for="roomidAdd">ID cuarto</label>
                            <input class="form-control" id="roomidAdd" type="text" readonly/>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="startHourAdd">Hora de Inicio</label>

                            <input class="form-control datetimepicker" id="startHourAdd" type="text" placeholder="H:i" data-options='{"enableTime":true,"noCalendar":true,"dateFormat":"H:i","disableMobile":true}' />
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="endHourAdd">Hora de Fin</label>
               
                            <input class="form-control datetimepicker" id="endHourAdd" type="text" placeholder="H:i" data-options='{"enableTime":true,"noCalendar":true,"dateFormat":"H:i","disableMobile":true}' />
                            <span id="error-message" style="color: red;"></span>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="numguest">Numero de Personas</label>
                            <input class="form-control" id="numguest" type="text" placeholder="3"/>
                        </div>

                      </form>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="id" name="id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Crear Detalle de Reserva</button>
                </div>
            </div>
        </div>
    </div>

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
                    </form>
                        
                    <form class="row g-3" id="reservationForm">
                        @csrf
                        <div class="col-md-12" hidden>
                            <label class="form-label" for="idCustomer">ID</label>
                            <input class="form-control" id="idCustomer" type="text"/>
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
                            <input class="form-control" id="employeerid" type="text" value={{ $user->id}} readonly />
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="employeername">Atendido por:</label>
                            <input class="form-control" id="employeername" type="text" value= {{$user->name}} readonly />
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="id" name="id">
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
    <script src="{{asset('admin/assets/js/flatpickr.js')}}"></script>
    <!-- Incluye moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script>
        var csrfToken = "{{ csrf_token() }}";
    </script>
    <script>
        flatpickr("#dateSearch", {
            defaultDate: "today",
            dateFormat: "d/m/Y"
        });
        var now = new Date();
        flatpickr("#dateSearch2", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: false,
            defaultDate: now,
        });
    </script>
    <script src="{{asset('js/reservation/indexV2.js')}}"></script>
@endsection
