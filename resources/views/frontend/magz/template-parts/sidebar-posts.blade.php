@if (Ads::checkAdPlacementActive('sidebar-right-top'))
    @include('frontend.magz.inc._ads-sidebar-right-top')
@endif
<aside>
    @include('frontend.magz.inc._recent-post-sidebar')
</aside>
<aside>
    <div class="aside-body">
        @include('frontend.magz.inc._newsletter')
    </div>
</aside>
