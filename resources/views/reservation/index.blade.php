@extends('layouts.appadmin')

@section('styles-plugins')
@endsection

@section('styles-dist')
@endsection

@section('styles-own')

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
            <button type="button" class="btn btn-outline-success" onclick="cleanReservation()">
                <i class="fa fa-plus"></i> Nuevo
            </button>
            @endif
            @if($tipo == 'reporte')
                <button type="button" class="btn btn-outline-info" onclick="exportarExcel()">
                        <i class="far fa-file-excel"></i> Descargar Excel
                </button>
            @endif
        </div>
    </div>
@endsection

@section('content')




    

@endsection

@section('plugins')

@endsection

@section('scripts')
    <script src="{{asset('js/reservation/index.js')}}"></script>
    <!--<script src="{{-- asset('js/customer/all.js') --}}"></script>-->
    <!-- Incluye moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script>
        var csrfToken = "{{ csrf_token() }}";
    </script>
@endsection