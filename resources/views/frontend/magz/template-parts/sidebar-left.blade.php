@if (Ads::checkAdPlacementActive('sidebar-left-top'))
    @include('frontend.magz.inc._ads-sidebar-left-top')
@endif
<aside>
    @include('frontend.magz.inc._recent-post-sidebar')
</aside>
<aside>
    <div class="aside-body">
        @include('frontend.magz.inc._newsletter')
    </div>
</aside>
