@extends('adminlte::page')

@section('title', 'Add New Page')

@section('content_header')
    <x-breadcrumbs title="{{ __('Add New Page') }}" currentActive="{{ __('Add New') }}" :addLink="[route('pages.index') => __('Pages')]"/>
@stop

@section('content')
<form action="{{route('pages.store')}}" method="POST" role="form" enctype="multipart/form-data">
@csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label for="titlePost">{{ __('Title') }}</label>
                        <input id="titlePost" type="text" name="title"
                            class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                            placeholder="Enter Title" onkeyup="typeUrl()" value="{{ old('title') }}" required autofocus>
                        @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <p>
                            <a class="btn btn-info btn-sm" data-toggle="collapse" href="#collapseSlug" role="button" aria-expanded="false" aria-controls="collapseSlug">
                                {{ __('Custom Permalink') }}
                            </a>
                        </p>
                        <div class="collapse{{ $errors->has('slug') ? ' show' : '' }}" id="collapseSlug">
                            <input type="text" name="slug"
                                   class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" id="slugPost"
                                   placeholder="Enter Slug" value="{{ old('slug') }}">
                            @error('slug')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('Summary') }}</label>
                        <input type="hidden" name="summary">
                        <textarea name="summary" class="form-control" rows="3" placeholder="{{ __('Enter summary') }}"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('Content') }}</label>
                        <textarea name="content" placeholder="{{ __('Place some text here') }}"
                            style="width: 100%; height: 200px; font-weight:normal"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-default">
                <div class="card-body">
                    <div class="form-group">
                        <label for="">{{ __('Featured Image') }}</label>
                        <div class="upload-photo">
                            <input id="upload" type="file" name="image" value="{{ __('Choose a file') }}" accept="image/*"
                                data-role="none" hidden>
                            <div class="col-md-12">
                                <div class="upload-msg">{{ __('Click to upload image') }}</div>
                                <div id="display">
                                    <img id="image_preview_container" src="#" name="image" alt="preview image"
                                        style="width: 100%;">
                                </div>
                                <div class="buttons text-center mt-3">
                                    <button id="reset" type="button"
                                        class="reset btn btn-danger">{{ __('Change Image') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="meta_description">{{ __('Meta Description') }}</label>
                        <textarea id="meta_description" name="meta_description" class="form-control" rows="3"
                            placeholder="{{ __('Enter desciption page') }}"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="meta_keyword">{{ __('Meta Keyword') }}</label>
                        <textarea id="meta_keyword" name="meta_keyword" class="form-control" rows="3"
                            placeholder="{{ __('Enter keyword page') }}"></textarea>
                    </div>
                </div>
            </div>
            <div class="card card-default">
                <div class="card-header">{{ __('Publish') }}</div>
                <div class="card-body">
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" name="publish" value="{{ __('Publish') }}">
                        <input class="btn btn-secondary" type="submit" name="draft" value="{{ __('Save as Draft') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@stop

@section('adminlte_css')
<link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/pace-progress/themes/blue/pace-theme-minimal.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/summernote-add-text-tags/summernote-add-text-tags.css') }}">
@include('admin.page.style')
@stop

@section('adminlte_js')
<script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('vendor/summernote-image-attributes-editor/summernote-image-attributes.js') }}"></script>
<script src="{{ asset('vendor/summernote-image-attributes-editor/lang/en-us.js') }}"></script>
<script src="{{ asset('vendor/summernote-add-text-tags/summernote-add-text-tags.js') }}"></script>
<script src="{{ asset('vendor/summernote-ext-highlight/summernote-ext-highlight.js') }}"></script>
<script src="{{ asset('vendor/summernote-video-attributes/summernote-video-attributes.js') }}"></script>
@include('layouts.partials._script')
@include('admin.page.script')
<script>
    "use strict";

    $(document).on('click', '.upload-msg', function() {
        $("#upload").trigger("click");
    });

    $('#reset').on("click", function() {
        $('#display').removeAttr('hidden');
        $('#reset').attr('hidden');
        $('.upload-photo').removeClass('ready result');
        $('#image_preview_container').attr('src', '#');
    });

    function readFile(input) {
        let reader = new FileReader();
        reader.onload = (e) => {
            $('.upload-photo').addClass('ready');
            $('#image_preview_container').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }

    $('#upload').on('change', function() {
        readFile(this);
    });
</script>
@stop

@section('footer')
@include('layouts.partials._footer')
@stop
