@extends('adminlte::page')

@section('title', 'Change Password')

@section('content_header')
    <x-breadcrumbs title="{{ __('Change Password') }}" currentActive="{{ __('Change Password') }}"
                   :addLink="[url('admin/profile') => __('Users')]"/>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-default">
            <form action="{{ route('auth.password.update') }}" method="POST" role="form">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="old_password">{{ __('Old Password') }}</label>
                        <div class="input-group">
                            <input id="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" autofocus>
                            <div class="input-group-append old_password">
                                <div class="input-group-text">
                                    <span class="fas fa-eye"></span>
                                </div>
                            </div>
                            @error('old_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="new_password">{{ __('New Password') }}</label>
                        <div class="input-group">
                            <input id="new_password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password">
                            <div class="input-group-append password">
                                <div class="input-group-text">
                                    <span class="fas fa-eye"></span>
                                </div>
                            </div>
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">{{ __('New Password (Again)') }}</label>
                        <div class="input-group">
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                            <div class="input-group-append password_confirmation">
                                <div class="input-group-text">
                                    <span class="fas fa-eye"></span>
                                </div>
                            </div>
                            @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Change Your Password') }}</button>
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
    <script src="{{ asset('js/show-hide-password.js') }}"></script>
@include('layouts.partials._script')
@stop

@section('footer')
@include('layouts.partials._footer')
@stop
