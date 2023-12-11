@extends('layouts.appadmin')

@section('styles-plugins')
<link href="{{asset('admin/vendors/flatpickr/flatpickr.min.css')}}" rel="stylesheet" />
@endsection

@section('styles-dist')
@endsection

@section('styles-own')
    <style>
        /* Estilo para habitaciones seleccionadas */
        .selected {
            border: 2px solid #007bff; /* Cambia el color del borde según tus preferencias */
        }
    </style>
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
    @section('activeListReservations')
        active
    @endsection
@else
    @if($tipo=='eliminados')
        @section('activeDeletedReservations')
        active
        @endsection
    @else
        @section('activeReportReservations')
        active
        @endsection
    @endif

@endif

@section('title')
    Reserva
@endsection

@section('page-header')
    <h3 class="m-0 text-dark">Nueva Reserva</h3>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{route('reservations.indexReservations')}}">Reservas</a></li>
        <li class="breadcrumb-item active">Crear</li>
    </ol>
@endsection

@section('page-title')
    <div class="col-10">
        <h5 class="card-title col-7">Crea una nueva reserva</h5>
    </div>
@endsection

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <form class="row g-3">
        @csrf
        <div class="col-md-6">
            <label class="form-label" for="document">Documento <span class="text-danger">*</span></label>
            <input class="form-control" id="document" type="text" >
        </div>
        <div class="col-md-6">
            <label>&nbsp;</label><br>
            <button type="button" id="buscarBtn" class="btn btn-outline-facebook">Buscar Cliente</button>
        </div>
    </form>
    <hr>
    <b>DATOS DEL CLIENTE</b>
    <form class="row g-3" id="reservationForm">
        @csrf
        <div class="col-md-12" hidden>
            <label class="form-label" for="idCustomer">ID</label>
            <input class="form-control" id="idCustomer" type="text">
        </div>
        <div class="col-md-4 d-none" id="inputDocumentType">
            <label for="documentType">Tipo de Documento <span class="text-danger">*</span></label>
            <select name="documentType" id="documentType" class="form-select">
                <option value="">Selecciona</option>
                @foreach( $documentTypes as $documentType )
                    <option value="{{ $documentType ->name }}">{{ $documentType ->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 d-none" id="inputName">
            <label class="form-label" id="name-label" for="name">Nombres<span class="text-danger">*</span></label>
            <input class="form-control" id="name" type="text" placeholder="Ejm: Ana María">
        </div>
        <div class="col-md-4 d-none" id="inputLastname">
            <label for="lastname" id="lastname-label">Apellidos <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="lastname" name="lastname"  placeholder="Ejm: Diaz Aguilar">
        </div>
        <div class="col-md-3 d-none" id="inputPhone" >
            <label class="form-label" for="phone">Teléfono<span class="text-danger">*</span></label>
            <input class="form-control" id="phone" type="text" placeholder="Ejm: 987654321">
        </div>
        <div class="col-md-6 d-none" id="inputEmail">
            <label for="email">Email <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="email" name="email"  placeholder="Ejm: alguien@correo.com">
        </div>
        <div class="col-md-3 d-none" id="inputBirth">
            <label for="birth" id="birth-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
            <input class="form-control datetimepicker" id="birth" type="text" placeholder="dd/mm/yy" data-options='{"disableMobile":true}'>


        </div>
        <div class="col-md-12 d-none" id="inputAddress">
            <label for="address">Dirección <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="address" name="address"  placeholder="Ejm: Av. Grau 325 - Huanchaco">
        </div>
        <hr>

        <b>DATOS DE LA RESERVA</b>
        <div class="col-md-6">
            <label class="form-label" for="code">Codigo de Reserva</label>
            <input class="form-control" id="code" type="text" placeholder="RS-00000" readonly>
        </div>
        <div class="col-md-6">
            <label for="reservationType">Tipo de reserva <span class="text-danger">*</span></label>
            <select name="reservationType" id="reservationType" class="form-select">
                <option value="0">Selecciona</option>
                <option value="1">Por Hora</option>
                <option value="2">Por día</option>
            </select>
        </div>
        <!-- Campos para "Reserva por horas" -->
        <div class="row" id="hourFields" style="display: none;">
            <div class="col-md-4">
                <label for="selectedDate">Fecha: <span class="text-danger">*</span></label>
                <input class="form-control" type="date" id="selectedDate" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-md-4">
                <label for="selectedStartTime">Hora de inicio: <span class="text-danger">*</span></label>
                <input class="form-control"  type="time" id="selectedStartTime" value="<?php echo date('H:i'); ?>">
            </div>
            <div class="col-md-4">
                <label for="hoursQuantity">Cantidad de Horas <span class="text-danger">*</span></label>
                <input class="form-control"  type="number" id="hoursQuantity">
            </div>
        </div>

        <!-- Campos para "Reserva por días" -->
        <div class="row" id="dayFields" style="display: none;">
            <div class="col-md-4">
                <label for="startDate">Fecha de Inicio: <span class="text-danger">*</span></label>
                <input class="form-control" type="date" id="startDate" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-md-4">
                <label for="endDate">Fecha de Fin: <span class="text-danger">*</span></label>
                <input class="form-control" type="date" id="endDate" value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="col-md-4">
                <label for="startTime">Hora de inicio: <span class="text-danger">*</span></label>
                <input class="form-control"  type="time" id="startTime" value="<?php echo date('H:i'); ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label class="form-label" for="total_guest">Número de Personas <span class="text-danger">*</span></label>
                <input class="form-control" id="total_guest" name="total_guest" type="number" placeholder="2" autocomplete="off">
            </div>

            <div class="col-md-4">
                <label class="form-label" for="select_room_type">Tipo de Habitación</label>
                <select name="select_room_type" id="select_room_type" class="form-select">
                    <option value="">Selecciona</option>
                    @foreach( $roomTypes as $roomType )
                        <option value="{{ $roomType->id }}">{{ $roomType->name }} - {{ $roomType->capacity }} Personas</option>
                    @endforeach
                </select>
            </div>

            <div class=" col-md-4">
                <label>&nbsp;</label><br>
                <button type="button" id="btn-search-rooms" class="btn btn-outline-warning me-5 ">Buscar Habitaciones</button>
            </div>
        </div>
        <div class="" id="rooms">
            <div class="row" id="body-card"></div>
            <div class="d-flex flex-stack flex-wrap pt-1">
                <div class="fw-bold text-gray-700" id="textPagination"></div>
                <!--begin::Pages-->
                <ul class="pagination" style="margin-left: auto" id="pagination">

                </ul>
                <!--end::Pages-->
            </div>
        </div>

        <hr>

        <b>DATOS DE PAGO</b>
        <div class="col-md-4">
            <button id="btnGenerarCosto" class="btn btn-outline-twitter">Generar Costo</button>
        </div>

        <div id="resumenReserva" class="col-8">
            <p class="lead">Resumen de Reserva</p>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Habitación</th>
                        <th>Precio Total</th>
                    </tr>
                    </thead>
                    <tbody id="resumenTablaBody">
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Total Final:</th>
                        <td id="totalFinal">S/. 0.00</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>


        <div class="col-md-3">
            <label class="form-label" for="paymethod">Método de Pago <span class="text-danger">*</span></label>
            <select class="form-select" id="paymethod">
                <option selected="selected">Elegir</option>
                @foreach($paymethods as $paymethod)
                    <option value= "{{$paymethod->id}}">{{$paymethod->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="initialpay">Pago Inicial <span class="text-danger">*</span></label>
            <input class="form-control" id="initialpay" type="number" placeholder="100" >
        </div>
        <hr>

        <div class="col-12" hidden>
            <label class="form-label" for="employeerid">Atendido por:</label>
            <input class="form-control" id="employeerid" type="number" value="{{$user->id}}">
        </div>

    {{--<div class="col-12">
        <label class="form-label" for="employeername">Atendido por:</label>
        <input class="form-control" id="employeername" type="text" value= {{$user->name}} readonly />
    </div>--}}
    <p>
    <div class="col-12 d-flex justify-content-center">
        <input type="hidden" id="id" name="id">
        <button type="button" class="btn btn-outline-success" id="guardar" onclick="saveReservations()">Guardar</button>
    </div>
</form>

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
                            <li class="small"><span class="fa-li"><i class="fas fa-layer-group"></i></span>Piso: <span data-nivel></span></li>
                            <li class="small"><span class="fa-li"><i class="fas fa-check"></i></span>Capacidad: <span data-capacity></span></li>
                            <li class="small"><span class="fa-li"><i class="fas fa-dollar-sign"></i></span>Precio: <span data-price></span></li>
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

@endsection

@section('plugins')

@endsection

@section('scripts')
<script src="{{ asset('js/reservation/allReservations.js') }}"></script>
<script src="{{ asset('js/reservation/searchRooms.js') }}"></script>
<script src="{{ asset('js/reservation/generateCost.js') }}"></script>
<script src="{{asset('admin/assets/js/flatpickr.js')}}"></script>
<!-- Incluye moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script>
    var csrfToken = "{{ csrf_token() }}";
</script>
@endsection