@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <x-breadcrumbs title="{{ __('Roles') }}" currentActive="{{ __('Edit') }}" :addLink="[route('roles.index') => __('Roles')]"/>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('roles.update', [$role->id])}}" method="POST" role="form">
                @method('PUT')
                @csrf
                <input type="hidden" id="{{ $role->id }}" name="role_id">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Update Role') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" name="name"
                                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name"
                                   placeholder="Enter name.." value="{{ $role->name }}" required autofocus>
                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">{{ __('Update Role') }}</button>
                    </div>
                </div>
            </form>
        </div>
        @can('read-permissions')
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Permissions') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row" id="checkAllBox">
                            <div class="col-md-12 text-center mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input checkbox" type="checkbox" id="checkAll"
                                           @if($ifCheckAll) checked @endif>
                                    <label for="checkAll"
                                           class="custom-control-label font-weight-normal">{{ __('Check All') }}</label>
                                </div>
                            </div>
                            <div class="col-sm-3 mb-3">
                                @php $no = 1; @endphp
                                @foreach ($permissions as $key => $row)
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input checkbox clickcheckbox" type="checkbox" id="checkbox-{{ $key }}" data-id="{{ $key }}"
                                               name="permissions[]" value="{{ $key }}"
                                            {{ in_array($key, $rolePermissions) ? "checked" : "" }}>
                                        <label for="checkbox-{{ $key }}"
                                               class="custom-control-label font-weight-normal">{{ $row }}</label>
                                    </div>
                                    @if ($no++%4 == 0)
                            </div>
                            <div class="col-sm-3 mb-3">
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@stop

@section('plugins.Toastr', true)

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/pace-progress/themes/blue/pace-theme-minimal.css') }}">
@stop

@section('adminlte_js')
    <script>
        "use strict";
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr("content")
            }
        })
        $('#checkAll').change(function() {
            $('#checkAllBox input:checkbox').not(this).prop('checked', this.checked);
            let role_id = $('input[name=role_id]').attr('id'),
                status = '';
            if( $(this).prop('checked')) {
                status = 'true';
            }else {
                status = 'false';
            }
            $.ajax({
                method: 'PATCH',
                url: '/change-all-permission',
                data: {
                    'role_id': role_id,
                    'status': status
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp.success) {
                        toastr.success(resp.success);
                    } else if (resp.info) {
                        toastr.info(resp.info);
                    } else {}
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
                }
            })
        });
        $('.clickcheckbox').on('click', function() {
            let permission_id = $(this).data('id'),
                role_id = $('input[name=role_id]').attr('id'),
                status = this.hasAttribute('checked');
            $.ajax({
                method: 'PATCH',
                url: '/change-permission',
                data: {
                    'permissions': permission_id,
                    'role_id': role_id,
                    'status': status
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp.success) {
                        toastr.success(resp.success);
                    } else if (resp.info) {
                        toastr.info(resp.info);
                    } else {}
                }
            })
        })
    </script>
@stop

@section('footer')
    @include('layouts.partials._footer')
@stop
