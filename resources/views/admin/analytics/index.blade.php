@extends('adminlte::page')

@section('title', __('Google Analytics'))

@section('content_header')
    <x-breadcrumbs title="{{ __('Google Analytics') }}" currentActive="{{ __('Google Analytics') }}"/>
@stop

@section('content')
    @empty(env('ANALYTICS_VIEW_ID'))
    @else
        @can('read-analytics')
            @if(Settings::check_connection())
                @if(Settings::checkCredentialFileExists())
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('Most Visited Pages') }}</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Page') }}</th>
                                                <th>{{ __('PageViews') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($mostVisited as $i => $v)
                                                <tr>
                                                    <td>{{ $i+1 }}</td>
                                                    <td><a href="{{ $v['url'] }}" target="_blank">{{ $v['pageTitle'] }}</a></td>
                                                    <td>{{ $v['pageViews'] }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('Browser used by users') }}</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Browser') }}</th>
                                                <th>{{ __('Sessions') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($topBrowsers as $i => $v)
                                                <tr>
                                                    <td>{{ $i+1 }}</td>
                                                    <td>{{ $v['browser'] }}</td>
                                                    <td>{{ $v['sessions'] }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('Operating System used by users') }}</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Operating System') }}</th>
                                                <th>{{ __('Version') }}</th>
                                                <th>{{ __('Sessions') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($topOperatingSystem as $i => $v)
                                                <tr>
                                                    <td>{{ $i+1 }}</td>
                                                    <td>{{ $v['os'] }}</td>
                                                    <td>{{ $v['version'] }}</td>
                                                    <td>{{ $v['sessions'] }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('Sessions by country') }}</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Country') }}</th>
                                                <th>{{ __('Sessions') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($topCountry as $i => $v)
                                                <tr>
                                                    <td>{{ $i+1 }}</td>
                                                    <td>{{ $v['country'] }}</td>
                                                    <td>{{ $v['sessions'] }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endcan
    @endempty
@endsection

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
