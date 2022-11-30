@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        @can('read-posts')
            <div class="col-12 col-sm-6 col-md-3">
                <a class="link-info-box" href="{{ route('posts.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Posts') }}</span>
                            <span class="info-box-number">{{ $count->post }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('read-pages')
            <div class="col-12 col-sm-6 col-md-3">
                <a class="link-info-box" href="{{ route('pages.index') }}">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-copy"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Pages') }}</span>
                            <span class="info-box-number">{{ $count->page }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        <div class="clearfix hidden-md-up"></div>

        @can('read-categories')
            <div class="col-12 col-sm-6 col-md-3">
                <a class="link-info-box" href="{{ route('categories.index') }}">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-tags"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Categories') }}</span>
                            <span class="info-box-number">{{ $count->category }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('read-tags')
            <div class="col-12 col-sm-6 col-md-3">
                <a class="link-info-box" href="{{ route('tags.index') }}">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-thumbtack"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Tags') }}</span>
                            <span class="info-box-number">{{ $count->tag }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('read-users')
            <div class="col-12 col-sm-6 col-md-3">
                <a class="link-info-box" href="{{ route('users.index') }}">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-indigo elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Users') }}</span>
                            <span class="info-box-number">{{ $count->user }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('read-roles')
            <div class="col-12 col-sm-6 col-md-3">
                <a class="link-info-box" href="{{ route('roles.index') }}">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-fuchsia elevation-1"><i class="fas fa-user-shield"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Roles') }}</span>
                            <span class="info-box-number">{{ $count->role }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('read-permissions')
            <div class="col-12 col-sm-6 col-md-3">
                <a class="link-info-box" href="{{ route('permissions.index') }}">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-olive elevation-1"><i class="fas fa-shield-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Permissions') }}</span>
                            <span class="info-box-number">{{ $count->permission }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('read-galleries')
            <div class="col-12 col-sm-6 col-md-3">
                <a class="link-info-box" href="{{ route('galleries.index') }}">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-hdd"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Galleries') }}</span>
                            <span class="info-box-number">{{ $count->gallery }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endcan
    </div>

    @if(env('ANALYTICS_VIEW_ID'))
        @can('read-analytics')
            @if(Settings::check_connection())
                @if(Settings::checkCredentialFileExists())
                    <h4 class="mt-4 mb-4">{{ __('Google Analytics') }} <small>(<a href="{{ route('analytics.index') }}">{{ __('See More') }}</a>)</small></h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('Sessions by device') }}</h3>
                                </div>
                                <div class="card-body p-0">
                                    <!-- Chart's container -->
                                    <div id="deviceChart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('Visitor & Pageview') }}</h3>
                                    <div class="card-tools">
                                        {{ __('In 7 days') }}
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <!-- Chart's container -->
                                    <div id="chart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endcan
    @endif
@stop

@section('adminlte_css')
    <style>
        .card {
            box-shadow: none;
            border: 1px solid rgba(0, 0, 0, .125);
        }
        .link-info-box {
            color: #000;
        }
    </style>
@stop

@section('adminlte_js')
    @empty(env('ANALYTICS_VIEW_ID'))
    @else
        @can('read-analytics')
            @if(Settings::check_connection())
                @if(Settings::checkCredentialFileExists())
                    <!-- Charting library -->
                    <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
                    <!-- Chartisan -->
                    <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
                    <script>
                        const chart = new Chartisan({
                            el: '#chart',
                            url: "@chart('chart_route_name')",
                            hooks: new ChartisanHooks()
                                .colors(['#27ae60', '#7CD6FD'])
                                .tooltip()
                                .datasets('line')
                                .legend({ bottom: 0 })
                        });

                        const deviceChart = new Chartisan({
                            el: '#deviceChart',
                            url: "@chart('device_chart')",
                            hooks: new ChartisanHooks()
                                .colors(['#27ae60', '#7CD6FD', '#018786'])
                                .axis(false)
                                .tooltip()
                                .datasets('pie')
                        });
                    </script>
                @endif
            @endif
        @endcan
    @endempty
@stop

@section('footer')
    @include('layouts.partials._footer')
@stop
