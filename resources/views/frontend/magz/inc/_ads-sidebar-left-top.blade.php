<aside>
    <div class="aside-body">
        <figure class="ads">
            @if (Ads::type('sidebar-left-top') == 'image')
                @if (Ads::checkFileAd('sidebar-left-top'))
                    <a href="{{ Ads::url('sidebar-left-top') }}" target="_blank">
                        <img src="{{ Ads::image('sidebar-left-top') }}">
                    </a>
                    <figcaption>{{ __('Advertisement') }}</figcaption>
                @else
                    <a href="#">
                        <img src="{{ asset('themes/magz/images/ad.png') }}">
                    </a>
                    <figcaption>{{ __('Advertisement') }}</figcaption>
                @endif
            @elseif (Ads::type('sidebar-left-top') == 'ga')
                @if (File::exists(storage_path('app/public').'/ad/' . Ads::checkFileGa('sidebar-left-top')))
                    @include('ad::' . Ads::viewFileGa('sidebar-left-top'))
                @endif
            @elseif (Ads::type('sidebar-left-top') == 'script_code')
                @if (File::exists(storage_path('app/public').'/ad/' . Ads::checkFileScript('sidebar-left-top')))
                    @include('ad::' . Ads::viewFileScript('sidebar-left-top'))
                @endif
            @else
                <a href="#">
                    <img src="{{ asset('themes/magz/images/ad.png') }}">
                </a>
                <figcaption>{{ __('Advertisement') }}</figcaption>
            @endif
        </figure>
    </div>
</aside>
