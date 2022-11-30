<div class="container">
    <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="block">
                <h1 class="block-title">{{ __('Company Info') }}</h1>
                <div class="block-body">
                    <figure class="foot-logo">
                        @empty(Settings::get('logowebsite_footer'))
                            <img src="{{ asset('themes/magz/images/logo-light.png') }}" alt="Web Logo">
                        @else
                            <img src="{{  route('logo.display', Settings::get('logowebsite_footer')) }}" alt=" Web Logo">
                        @endempty
                    </figure>
                    <p class="brand-description">
                        {{ Settings::get('sitedescription') }}
                    </p>
                    <a href="/page/about" class="btn btn-magz white">{{ __('About Us') }} <i class="ion-ios-arrow-thin-right"></i></a>
                </div>
            </div>
            <div class="line"></div>
            <div class="block">
                @include('frontend.magz.inc._follow-us-footer')
            </div>
            <div class="line"></div>
            <div class="block">
                @include('frontend.magz.inc._menu-footer')
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="block">
                @include('frontend.magz.inc._popular-tags-footer')
            </div>
            <div class="line"></div>
            <div class="block">
                @include('frontend.magz.inc._newsletter-footer')
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="block">
                @include('frontend.magz.inc._latest-news-footer')
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="copyright">
                @include('frontend.magz.inc._credit-footer')
            </div>
        </div>
    </div>
</div>
