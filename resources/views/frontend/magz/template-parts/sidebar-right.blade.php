<div class="sidebar-title for-tablet">{{ __('Sidebar') }}</div>
@include('frontend.magz.inc._popular-sidebar')
<aside>
    <div class="aside-body">
        @include('frontend.magz.inc._newsletter')
    </div>
</aside>
@include('frontend.magz.inc._recomended')
@if (Ads::checkAdPlacementActive('sidebar-right-bottom'))
    @include('frontend.magz.inc._ads-sidebar-right-bottom')
@endif
