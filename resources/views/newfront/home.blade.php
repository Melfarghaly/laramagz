@extends('layouts.newApp')
@section('content')
<?php
use Alkoumi\LaravelHijriDate\Hijri;
?>
<!-- breadcrumb-start -->
<section class="breadcrumb breadcrumb_bg banner-bg-1 overlay2 ptb200" style="min-height: 600px">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 offset-lg-1">
            </div>
        </div>
    </div>
</section>
<!-- breadcrumb-end -->
@php
    $posts_news= \App\Models\Post::join('term_relationships as tr','tr.post_id','posts.id')
            ->join('term_taxonomies as tt','tt.id','tr.term_taxonomy_id')
            ->join('terms','terms.id','tt.term_id')
            ->where('tt.taxonomy','tag')
            ->where('terms.slug','alakhbar')
            ->select('posts.*')->orderBy('posts.id','desc')->get();


@endphp
<!-- about start -->
@if(!empty(sizeof($posts_news)))
<div class="about-area section-padding">
    <h3 class="section-title">
        <img src="{{asset('front/img/xd/section-title.svg')}}" width="40">
        أبرز الأخبار
    </h3>

    <div class="container">
        <div class="row mb-5 align-items-center">
            <div class="col-xl-3 col-md-3">
                <div class="review-thumb">
                    <img src={{ Posts::getImage($posts_news[sizeof($posts_news)-1]->post_content,$posts_news[sizeof($posts_news)-1]->post_image) }}" class="img-fluid rounded">
                </div>
            </div>
            <div class="col-xl-9 col-md-9">
                <div class="single-review">
                    <h2> {{ $posts_news[sizeof($posts_news)-1]->post_title ?? ''}}  </h2>
                    <p>
                        {{ Hijri::Date('j F Y',$posts_news[sizeof($posts_news)-1]->created_at) }}  هـ
                    </p>
                    <p>
                        {!! $posts_news[sizeof($posts_news)-1]->post_summary ?? ''!!}
                    </p>
                </div>
            </div>

        </div>

        <div class="row mt-5">

            <div class="col-md-12">
                <div class="carouselNews owl-carousel causes_slider" dir="ltr">
                    @foreach ($posts_news as $item )


                    <div class="item">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="single-review">
                                    <h4>{{ $item->post_title ?? ''}} </h4>
                                    <p>
                                        {{ Hijri::Date('j F Y',$item->created_at) }}  هـ
                                    </p>
                                    <p>
                                        {!! $item->post_summary ?? '' !!}
                                    </p>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-4">
                                <div class="review-thumb">
                                    <img src="{{ Posts::getImage($item->post_content,$item->post_image) }}" class="img-fluid rounded">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

        </div>

    </div>


</div>
@endif
<!-- about end -->
@php
    $posts_videos= \App\Models\Post::join('term_relationships as tr','tr.post_id','posts.id')
            ->join('term_taxonomies as tt','tt.id','tr.term_taxonomy_id')
            ->join('terms','terms.id','tt.term_id')
            ->where('tt.taxonomy','tag')
            ->where('terms.slug','mktb-alfydyohat')
            ->select('posts.*')->orderBy('posts.id','desc')->get();
          // dd($posts_videos);

@endphp
@if(!empty(sizeof($posts_videos)))
<!-- videos-->
<div class="about-area">
    <h3 class="section-title mb-4">
        <img src="{{asset('front/img/xd/section-title.svg')}}" width="40">
        أبرز الفيديوهات
    </h3>

    <div class="container">
        <div class="row mb-5 align-items-center">

            <div class="col-xl-3 col-md-3">
                <div class="review-thumb">
                    <img src={{ Posts::getImage($posts_videos[sizeof($posts_videos)-1]->post_content,$posts_videos[sizeof($posts_videos)-1]->post_image) }}" class="img-fluid rounded">
                </div>
            </div>

            <div class="col-xl-9 col-md-9">
                <div class="single-review">
                    <h2>{{ $posts_videos[sizeof($posts_videos)-1]->post_title ?? ''}}</h2>
                    <p>
                        {{ Hijri::Date('j F Y',$posts_videos[sizeof($posts_videos)-1]->created_at ) }}  هـ
                    </p>
                    <p>
                        {!! $posts_videos[sizeof($posts_videos)-1]->post_summary ?? ''!!}
                    </p>
                </div>
            </div>

        </div>

        <div class="row mt-5">

            <div class="col-md-12">
                <div class="carouselNews owl-carousel causes_slider" dir="ltr">
                    @foreach ($posts_videos as $item)
                    <div class="item">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="single-review">
                                  <a href="/blog/{{ $item->slug }}">  <h4>{{ $item->post_title }}   </h4></a>
                                    <p>
                                        {{ Hijri::Date('j F Y',$item->created_at) }}  هـ
                                    </p>
                                    <p>
                                        {!! $item->post_summary ?? '' !!}
                                    </p>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-4">
                                <div class="review-thumb position-relative">
                                    <img src="{{ Posts::getImage($item->post_content,$item->post_image) }}" class="img-fluid rounded" alt="">
                                   < <div class="position-absolute" style="top: 30%;right: 30%;">
                                        <!--
                                        <a class="video-icon popup-video"
                                           href="https://www.youtube.com/">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                                 viewBox="0 0 64 64">
                                                <g transform="translate(-3386 -7557)">
                                                    <circle id="Ellipse_18" data-name="Ellipse 18" cx="32" cy="32"
                                                            r="32" transform="translate(3386 7557)" fill="#d1d1d1"
                                                            opacity="0.92"/>
                                                    <path d="M11.056,18.912a8,8,0,0,1,14.888,0L32.7,36.07A8,8,0,0,1,25.254,47H11.746A8,8,0,0,1,4.3,36.07Z"
                                                          transform="translate(3449 7571) rotate(90)" fill="#a0b435"/>
                                                </g>
                                            </svg>
                                        </a>
                                    -->
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>


</div>
<!-- //videos-->
@endif
@php
    $posts_makalat= \App\Models\Post::join('term_relationships as tr','tr.post_id','posts.id')
            ->join('term_taxonomies as tt','tt.id','tr.term_taxonomy_id')
            ->join('terms','terms.id','tt.term_id')
            ->where('tt.taxonomy','tag')
            ->where('terms.slug','almkalat')
            ->select('posts.*')->orderBy('posts.id','desc')->get();
           //dd($posts_makalat);

@endphp
<!-- about start -->
<div class="about-area section-padding">
    <h3 class="section-title">
        <img src="{{asset('front/img/xd/section-title.svg')}}" width="40">
        أبرز المقالات
    </h3>
    <div class="container">
        <div class="about-pro-active owl-carousel owl-theme" dir="ltr">
            @foreach ( $posts_makalat as $row)
            <div class="row align-items-center site-border p-1">
                <div class="col-xl-12">
                    <div class="about-info-text">
                        <a href="{{ Settings::getRoutePost($row) }}"> <h3>{{ $row->post_title }}  </h3></a>
                        <p>

                            {{ Hijri::Date('j F Y',$row->created_at) }}  هـ
                        </p>
                        <p>
                           {!! $row->post_summary !!}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach


        </div>
    </div>
</div>
<!-- about end -->

@endsection
