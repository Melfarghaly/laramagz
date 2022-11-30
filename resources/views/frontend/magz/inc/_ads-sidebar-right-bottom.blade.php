<aside id="sponsored">
    <h1 class="aside-title">Sponsored</h1>
    <div class="aside-body">
        <figure class="ads">
            @if (Ads::type('sidebar-right-bottom') == 'image')
                @if (Ads::checkFileAd('sidebar-right-bottom'))
                    <a href="{{ Ads::url('sidebar-right-bottom') }}" target="_blank">
                        <img src="{{ Ads::image('sidebar-right-bottom') }}">
                    </a>
                @else
                    <a href="#">
                        <img src="{{ asset('themes/magz/images/ad.png') }}">
                    </a>
                @endif
            @elseif (Ads::type('sidebar-right-bottom') == 'ga')
                @if (File::exists(storage_path('app/public').'/ad/' . Ads::checkFileGa('sidebar-right-bottom')))
                    @include('ad::' . Ads::viewFileGa('sidebar-right-bottom'))
                @endif
            @elseif (Ads::type('sidebar-right-bottom') == 'script_code')
                @if (File::exists(storage_path('app/public').'/ad/' . Ads::checkFileScript('sidebar-right-bottom')))
                    @include('ad::' . Ads::viewFileScript('sidebar-right-bottom'))
                @endif
            @else
                <a href="#">
                    <img src="{{ asset('themes/magz/images/ad.png') }}">
                </a>
            @endif
        </figure>
    </div>
</aside>
