{{ __('Copyright') }} &copy; {{ \Carbon\Carbon::now()->format('Y') }}
<a href="{{ Settings::get('siteurl') }}">
    <strong>{{ Settings::get('company_name') }}</strong>
</a>. {{ __('All Rights Reserved') }}
<div class="float-right d-none d-sm-inline-block">
    <strong>Env</strong>&nbsp;&nbsp; {{ App::environment() }}
    &nbsp;&nbsp;&nbsp;&nbsp;
    <strong>Version</strong> {{ config('retenvi.version') }}
</div>
