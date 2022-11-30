@extends('frontend.magz.index')

@section('content')
<section class="home">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-12">
                @include('frontend.magz.inc._headline')
                @include('frontend.magz.inc._featured-carousel')
                @include('frontend.magz.inc._latest-news-home')

                @if (Ads::checkAdPlacementActive('home-horizontal'))
                @include('frontend.magz.inc._ads-home-horizontal')
                @endif

                <div class="line transparent little"></div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 trending-tags">
                        @include('frontend.magz.inc._trending-tags')
                    </div>
                    <div class="col-md-6 col-sm-6">
                       @include('frontend.magz.inc._hot-news')
                    </div>
                </div>
                @include('frontend.magz.inc._just-another-news')
            </div>

            <div class="col-xs-6 col-md-4 sidebar" id="sidebar">
                @include('frontend.magz.template-parts.sidebar-right')
            </div>
        </div>
    </div>
</section>

@include('frontend.magz.inc._best-of-the-week')

@endsection
