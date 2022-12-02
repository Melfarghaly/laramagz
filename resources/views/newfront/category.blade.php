@extends('layouts.newApp')
@section('content')
<?php
use Alkoumi\LaravelHijriDate\Hijri;
?>
<!-- about start -->
<div class="about-area mt-4">
    <div class="container-fluid">
        <h3 class="section-title">
            <img src="img/xd/section-title.svg" width="40" alt="">
            {{ $term->name }}
        </h3>
        <div class="row">
            <div class="col-lg-9 mb-5 mb-lg-0">
                <div class="blog_left_sidebar">
                    @foreach($paginate_posts as $row)
                    <article class="blog_item">
                        <div class="about-info-text p-3 site-border">

                                <a href="{{ Settings::getRoutePost($row) }}"> <h3>{{ $row->post_title }}  </h3></a>

                            <p>

                               {{ Hijri::Date('j F Y',$row->created_at) }}  هـ
                            </p>
                            <p>
                                {!! $row->post_summary !!}
                            </p>
                        </div>
                    </article>
                    @endforeach
                    {!! $paginate_posts->links()  !!}
<!--
                    <nav class="blog-pagination justify-content-center d-flex">
                        <ul class="pagination">
                            <li class="page-item">
                                <a href="#" class="page-link" aria-label="Previous">
                                    <i class="ti-angle-right"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">1</a>
                            </li>
                            <li class="page-item active">
                                <a href="#" class="page-link">2</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link" aria-label="Next">
                                    <i class="ti-angle-left"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                -->
                </div>
            </div>
            <div class="col-lg-3">
                <div class="blog_right_sidebar">
                 @foreach($paginate_posts as $row)
                    <aside class="single_sidebar_widget popular_post_widget">
                        <div class="">
                            <img src="{{ Posts::getImage($row->post_content,$row->post_image) }}" width="200" height="100" alt="">
                            <div class="media-body mt-2">
                                <a href="{{ Settings::getRoutePost($row) }}"> <h4>{{ $row->post_title }}  </h4></a>
                                <p>
                                {{ Hijri::Date('j F Y',$row->created_at) }}  هـ
                                </p>
                                <h6>
                                   {!! $row->post_summary !!}
                                </h6>
                            </div>
                        </div>
                    </aside>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
<!-- about end -->
@endsection
