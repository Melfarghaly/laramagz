@extends('layouts.newApp')
@section('content')
<!-- about start -->
<div class="about-area mt-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mb-5 mb-lg-0">
                <div class="blog_left_sidebar">
                    <article class="blog_item">
                        <p>
                            <img class="float-left pr-5" src="{{ Posts::getImage($post->post_content,$post->post_image)}}" width="280"/>
                        <h3>  {{ $post->post_title }}      </h3>
                        <p>
                            28 تشرين الأول 2022
                        </p>
                        <p>
                            {!! $post->post_content !!}

                        </p>
                        <br style="clear:both"/>



                    </article>
                    <div class="sharing">
                        <div class="title"><i class="ion-android-share-alt"></i> {{ __('Sharing is caring') }}</div>
                        {!! Share::sendTo('laramagz', null, [], '<ul class="social">','</ul>')
                        ->facebook()
                        ->twitter()
                        ->linkedin()
                        ->whatsapp()
                         ->telegram()!!}
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="blog_right_sidebar">

                    @empty($term_taxonomy)
                    @else
                    @foreach ( $term_taxonomy->skip(0)->take(2) as $relpost)
                    @if ($post->post_title !== $relpost->post_title)
                    <aside class="single_sidebar_widget popular_post_widget">
                        <div class="">
                            <img src="{{ Posts::getImage($relpost->post_content, $relpost->post_image) }}" width="200"  height="100" alt="{{ $relpost->post_image }}" alt="{{ $relpost->post_image }}">
                            <div class="media-body mt-2">
                                <a href="single-blog.html">
                                    <h4><a href="{{ Settings::getRoutePost($relpost) }}">{{ $relpost->post_title }}</a></h4>
                                </a>

                                <p>{{ $relpost->created_at->format('F d, Y') }}</p>

                                <h6>
                                   {!! $relpost->post_summary !!}
                                </h6>

                            </div>
                        </div>

                    </aside>
                    @endif
                    @endforeach
                    @endempty
                </div>
            </div>
        </div>

    </div>
</div>
<!-- about end -->
@endsection
