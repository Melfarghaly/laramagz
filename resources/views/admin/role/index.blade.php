@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <x-breadcrumbs title="{{ __('Roles') }} ({{ Utl::roleCount() }})" currentActive="{{ __('Roles') }}"/>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        @include('layouts.partials._table')
    </div>
</div>
@stop

@section('adminlte_css')
<link rel="stylesheet" href="{{ asset('vendor/pace-progress/themes/blue/pace-theme-minimal.css') }}">
@stop

@section('adminlte_js')
<script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
@include('layouts.partials._script')
@stop

@section('footer')
@include('layouts.partials._footer')
@stop