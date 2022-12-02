@extends('layouts.newApp')
@section('content')

<!-- about start -->
<div class="about-area mt-4">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-9 mb-5 mb-lg-0">
                <div class="blog_left_sidebar">

                    <h3 class="section-title">
                        <img src="img/xd/section-title.svg" width="40" alt="">
                        مكتبة الكتب والمخططات
                    </h3>

                    <div class="row">
                        @foreach($books as $row)
                        @php
                        $data=json_decode($row->post_image_meta, true);
                        @endphp
                        <div class="col-md-4 mt-4">
                            <div class="card">
                                <img class="card-img-top" width="200" height="200" src="https://www.seekpng.com/png/detail/197-1973656_old-book-png-open-book-png.png" alt="Card image cap">
                                <div class="card-body">
                                    <h3 class="card-title">{{ $row->post_title }}</h3>
                                    <p class="card-text"> {{ $data['author'] ?? ''  }} </p>
                                    <a href="#" class="btn btn-site float-right">تحميل الكتاب</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <h3 class="section-title" style="margin-top: 5em">
                        <img src="img/xd/section-title.svg" width="40" alt="">
                        المقالات
                    </h3>


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
