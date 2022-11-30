<div class="firstbar">
    <div class="container">
        <div class="row">
            <div class="hidden-xs col-md-3 col-sm-12">
                <div class="brand">
                    <a href="/">
                        @empty(Settings::get('logowebsite'))
                            <img src="{{ asset('themes/magz/images/logo.png') }}" alt="Web Logo">
                        @else
                            <img src="{{ route('logo.display', Settings::get('logowebsite')) }}" alt=" Web Logo">
                        @endempty
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                @include('frontend.magz.inc._search-form')
            </div>
            @auth
                <div class="col-md-3 col-sm-12 text-right">
                    <ul class="nav-icons">
                        <li><a href="{{ route('profile.index') }}"><i class="ion-person"></i><div>{{ Auth::user()->name }}</div></a></li>
                    </ul>
                </div>
            @else
            @endif
        </div>
    </div>
</div>

<!-- Start nav -->
@include('frontend.magz.inc._menu-nav')
<!-- End nav -->
