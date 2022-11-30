{{-- resources/views/admin/banner/edit.blade.php --}}

@extends('adminlte::page')

@section('title', 'Edit Advertisement')

@section('content_header')
    <x-breadcrumbs title="{{ __('Edit Advertisement') }}" currentActive="{{ __('Edit') }}" :addLink="[route('advertisement.index') => __('Advertisement')]"/>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-default">
            <form action="{{route('advertisement.update', [$ad->id])}}" method="POST" role="form" enctype="multipart/form-data">
            @method('PUT')
            @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>
                        <input id="name" type="text" name="name"  class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('Name your ad unit') }}" required value="{{ $ad->name }}">
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="type">{{ __('Type') }}</label>
                        <div id="ads_type" class="form-group">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn bg-cyan active">
                                    <input type="radio" name="type" id="option1" autocomplete="off" value="image" @if($ad->type == 'image') checked @endif><i class="fas fa-image"></i> Image
                                </label>
                                <label class="btn bg-cyan">
                                    <input type="radio" name="type" id="option2" autocomplete="off" value="ga" @if($ad->type == 'ga') checked @endif><i class="fab fa-google"></i> Google Adsense
                                </label>
                                <label class="btn bg-cyan">
                                    <input type="radio" name="type" id="option3" autocomplete="off" value="script_code" @if($ad->type == 'script_code') checked @endif><i class="fas fa-code"></i> Script Code
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="ad_image" @if($ad->type != 'image') hidden @endif>
                        <div class="form-group">
                            <label for="upload">{{ __('Upload Image') }}</label>
                            <div class="upload-image row justify-content-md-center @if($image) ready @endif">
                                <input id="upload" type="file" name="image" value="Choose a file" accept="image/*"
                                       data-role="none" hidden>
                                <div class="col-12 col-md-8 text-center">
                                    <div class="upload-msg">{{ __('Click to select image') }}</div>
                                    <div id="display">
                                        <img id="image_preview_container" src="{{ $image }}" alt="preview image"
                                             style="max-width: 100%;">
                                    </div>
                                    <div class="buttons text-center mt-3">
                                        <button id="reset" type="button" class="reset btn btn-danger">{{ __('Change Image') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">{{ __('Size') }}</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="number" name="width" class="form-control" placeholder="{{ __('Width') }}" value="{{ $width }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">px</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <input type="number" name="height" class="form-control" placeholder="{{ __('Height') }}" value="{{ $height }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">px</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="url">{{ __('URL') }}</label>
                            <input id="url" type="url" name="url"  class="form-control @error('url') is-invalid @enderror" placeholder="{{ __('https://www.example.com/') }}" value="{{ $url }}">
                            @error('url')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div id="ad_ga" class="form-group" @if($ad->type != 'ga') hidden @endif>
                        <label for="ad_unit">{{ __('Ad Unit Code') }}</label>
                        <textarea id="ga" name="script_ga" class="form-control scripts" cols="30" rows="7">{{ $ga }}</textarea>
                    </div>
                    <div id="ad_script_code" class="form-group" @if($ad->type != 'script_code') hidden @endif>
                        <label for="ad_unit">{{ __('Ad Custom Code') }}</label>
                        <textarea id="custom" name="script_custom" class="form-control scripts" cols="30" rows="7">{{ $script_code }}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right">{{ __('Save') }}</button>
                    <a href="{{ route('advertisement.index') }}" class="btn btn-warning">{{ __('Back') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/pace-progress/themes/blue/pace-theme-minimal.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/codemirror/lib/codemirror.css') }}">
    @include('admin.advertisement.style')
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/codemirror/lib/codemirror.js') }}"></script>
    <script src="{{ asset('vendor/codemirror/addon/selection/active-line.js') }}"></script>
    @include('layouts.partials._script')
    @include('admin.advertisement.script')
@stop

@section('footer')
@include('layouts.partials._footer')
@stop
