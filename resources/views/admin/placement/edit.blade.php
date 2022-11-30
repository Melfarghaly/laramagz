@extends('adminlte::page')

@section('title', 'Edit Ad Placement')

@section('content_header')
    <x-breadcrumbs title="{{ __('Edit Placement') }}" currentActive="{{ __('Edit Ad Placement') }}" :addLink="[route('placements.index') => __('Edit')]"/>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <form action="{{route('placements.update', [$placement->id])}}" method="POST" role="form">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">{{ __('Ad Placement') }}</label>
                            <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('Ad placement name') }}" value="{{ $placement->name }}" required disabled>
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div id="ad_default" class="form-group">
                            <label for="ad_unit">{{ __('Ad Unit') }}</label>
                            <select id="ad_unit" name="ad_unit" class="select2 form-control" data-placeholder="Select Ad Unit" style="width: 100%;">
                                <option value="{{ $ad->id }}" selected="selected">{{ $ad->name .'('.$ad->size.')'}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">{{ __('Save') }}</button>
                        <a href="{{ route('placements.index') }}" class="btn btn-warning">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/pace-progress/themes/blue/pace-theme-minimal.css') }}">
@stop

@section('adminlte_js')
    @include('admin.placement.script')
@stop

@section('footer')
    @include('layouts.partials._footer')
@stop
