<nav class="menu">
    <div class="container">
        <div class="brand">
            <a href="/">
                @empty(Settings::get('logowebsite'))
                    <img src="{{ asset('themes/magz/images/logo.png') }}" alt="Web Logo">
                @else
                    <img src="{{ route('logo.display', Settings::get('logowebsite')) }}" alt="Web Logo">
                @endempty
            </a>
        </div>
        <div class="mobile-toggle">
            <a href="#" data-toggle="menu" data-target="#menu-list"><i class="ion-navicon-round"></i></a>
        </div>
        <div class="mobile-toggle">
            <a href="#" data-toggle="sidebar" data-target="#sidebar"><i class="ion-ios-arrow-left"></i></a>
        </div>
        <div id="menu-list">
            @if(Appearance::getMenuHeader())
                <ul class="nav-list">
                    @foreach(Appearance::getMenuHeader() as $menu)
                        <li class="@if($menu['child'])dropdown magz-dropdown @endif">
                            <a href="{{ $menu['link'] }}" title="">{{ $menu['label'] }} @if($menu['child'])<i class="ion-ios-arrow-right"></i>@endif</a>
                            @if( $menu['child'] )
                                @include('frontend.magz.template-parts._child',['childs'=>$menu['child']])
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</nav>
