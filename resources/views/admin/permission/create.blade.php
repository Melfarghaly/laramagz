@extends('adminlte::page')

@section('title', 'Permission')

@section('content_header')
    <x-breadcrumbs title="{{ __('Permissions') }}" currentActive="{{ __('Add New') }}" :addLink="[route('permissions.index') => __('Permissions')]"/>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">{{ __('Add New Permission') }}</h3>
            </div>
            <form action="{{ route('permissions.store') }}" method="POST" role="form">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="alias">{{ __('Permission') }}</label>
                        <input type="text" name="alias" class="form-control @error('alias') is-invalid @enderror"
                            id="alias" placeholder="Enter permission.." value="{{ old('alias') }}" required autofocus>
                        <small class="form-text text-muted">
                            Generate the permission prefix: <code>read-</code>, <code>add-</code>, <code>store-</code>, <code>update-</code>, and <code>delete-</code>
                        </small>
                        @error('alias')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right">{{ __('Add New Permission') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('adminlte_css')
<link rel="stylesheet" href="{{ asset('vendor/pace-progress/themes/blue/pace-theme-minimal.css') }}">
@stop

@section('adminlte_js')
@if(session('info'))
<script>
    var sessionId = "{{ uniqid() }}";
    if (sessionStorage) {
        if (!sessionStorage.getItem('shown-' + sessionId)) {
            toastr.info("{{session('info')}}")
        }
        sessionStorage.setItem('shown-' + sessionId, '1');
    }
</script>
@endif
@stop

@section('footer')
@include('layouts.partials._footer')
@stop
