@extends('adminlte::page')

@section('title', 'Categories')

@section('content_header')
    <x-breadcrumbs title="{{ __('Categories') }} ({{ Utl::categoryCount() }})" currentActive="{{ __('Categories') }}"/>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            @include('admin.category.create')
        </div>
        <div class="col-md-8">
            @include('layouts.partials._table')
        </div>
    </div>
@stop

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/pace-progress/themes/blue/pace-theme-minimal.css') }}">
@stop

@section('adminlte_js')
    @include('layouts.partials._script')
    @include('admin.category.script')
@stop

@section('footer')
    @include('layouts.partials._footer')
@stop
