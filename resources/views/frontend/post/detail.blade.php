@extends('frontend.master')

@section('header')
    <title>{{ $post->meta_title ?? $post->title }}</title>
    <meta content="description" value="{{ $post->meta_description ?? $post->description }}">
    <meta property="og:title" content="{{ $post->og_title ?? $post->meta_title ?? $post->title }}">
    <meta property="og:description"
          content="{{ $post->og_description ?? $post->meta_description ?? $post->description }}">
    <meta property="og:url" content="{{ $post->url }}">
    <meta property="og:image" content="{{ $post->og_image ?? $post->cover }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ $post->url }}">
@endsection

@section('body')
    <section class="section page-title">
        <div class="container">
            <nav class="breadcrumb-nav">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    @foreach($breadcrumb as $item)
                        <li class="breadcrumb-item"><a href="{{ $item->url }}">{{ $item->title }}</a></li>
                    @endforeach
                </ol>
            </nav>
            <h1 class="page-title__title">{{ $post->title }}</h1>
        </div>
    </section>

    <section class="section pb-0">
        <div class="container">
            <div class="layout layout--right">
                <div class="row">
                    <div class="col-xl-9 order-xl-2">
                        <div class="layout-content">
                            <div class="post-detail detailbox">
                                @if($post->cover)
                                    <div class="post-detail__img">
                                        <img src="{{ $post->cover }}" alt="{{ $post->title }}">
                                    </div>
                                @endif
                                <div class="detailbox__content entry-detail">{!! $post->content !!}</div>
                                <div class="mb-3">
                                    <div class="sharethis-inline-share-buttons"></div>
                                </div>

                                <div class="post-detail__navigation">
                                    <div class="f-item first">
                                        @if($prev_post)
                                            <div class="f-item__inner">
                                                <a href="{{ $prev_post->url }}">
                                                    <div class="h3">{{ $prev_post->title }}</div>
                                                    <span><i class="fa fa-long-arrow-left"></i> Bài trước</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="f-item last">
                                        @if($next_post)
                                            <div class="f-item__inner">
                                                <a href="{{ $next_post->url }}">
                                                    <div class="h3">{{ $next_post->title }}</div>
                                                    <span>Bài tiếp theo <i class="fa fa-long-arrow-right"></i></span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="post-detail__relatedCourse">
                                <div class="title-page-min">Khoá học liên quan</div>
                                <div class="owl-carousel fixheight lessonbox-wrap-min">
                                    @foreach($related_courses as $item)
                                        @include('frontend.category.course_item', [ 'course' => $item ])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 order-xl-1">
                        <div class="layout-sidebar">
                            <div class="widget widget--relatePost">
                                <div class="widget__title">Bài viết liên quan</div>

                                <ul>
                                    @foreach($related_posts as $item)
                                        <li>
                                            <a href="{{ $item->url }}">
                                                <img src="{{ $item->thumbnail }}">
                                                <div class="h3">{{ $item->title }}</div>
                                                <span><i class="fa fa-clock-o"></i>{{ $item->updated_at->format('d/m/Y') }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="widget widget--course">
                                <div class="widget__title">Khoá học</div>

                                <ul>
                                    @foreach(get_categories(null, 'course-categories') as $c1)
                                        <li class="item">
                                            <a href="{{ $c1->url }}">
                                            <span class="item__icon">
                                                <img src="{{ $c1->icon }}" alt="{{ $c1->title }}">
                                            </span>
                                                <div class="item__title">{{ $c1->title }}</div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="widget widget--book">
                                <div class="widget__title">Sách</div>
                                <div class="owl-carousel">
                                    @foreach($featured_books as $item)
                                        <a href="{{ $item->url }}" class="item">
                                            <div class="item__img">
                                                <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}">
                                            </div>
                                            <div class="item__title">{{ $item->title }}</div>
                                            <div class="item__price">
                                                <ins>{{ currency($item->price) }}</ins>
                                                @if($item->original_price)
                                                    <del>{{ currency($item->original_price) }}</del>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>
    <div class="card-footer text-muted">
        <h3>Ý kiến học viên</h3>
        @if(Auth::user())
            <form method="post" action="post/{{$post->id}}/comment">
                {{ csrf_field() }}
                <div class="form-group">
                    <textarea class="form-control" name="content_cmt" placeholder="Viết bình luận"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Gửi bình luận</button>
            </form>
        @else
            <a class="nav-link" href="{{ route('login') }}">{{ __('You must LOGIN to writing comments.') }}</a>
            <a class="nav-link"
               href="{{ route('register') }}">{{ __('If you do not have an account please click in here to REGISTER ') }}</a>
        @endif
        <br>
        @foreach($post->postComments as $cmt)
            <div class="card mb-4">
                <div class="card-body">
                    <i style='font-size:16px' class='fas'>&#xf304;</i>
                    <a href="#">{{$cmt->user->name}}</a> :
                    <i style="font-size:18px" class="fa">&#xf017;</i>
                    {{$cmt->created_at}}
                    <hr>
                    <p class="card-title">{{$cmt->content}}</p>
                    @if(Auth::user() == null)
                        <br>
                    @elseif(Auth::user()->id == $cmt->user_id )
                        <a href="{{route('postComments.destroy', $cmt->id)}}" class="btn btn-danger"
                           onclick="return confirm('You want to delete ?')">Delete</a>
                    @endif
                </div>
            </div>
            @foreach($cmt->childPostComments as $childPostCmt)
                <div class="card mb-4">
                    <div class="card-body">
                        <i style='font-size:16px' class='fas'>&#xf304;</i>
                        <a href="#">{{$childPostCmt->user->name}}</a> :
                        <i style="font-size:18px" class="fa">&#xf017;</i>
                        {{$childPostCmt->created_at}}
                        <hr>
                        <p class="card-title">{{$childPostCmt->content}}</p>
                        @if(Auth::user() == null)
                            <br>
                        @elseif(Auth::user()->id == $childPostCmt->user_id )
                            <a href="{{route('childPostComments.destroy', $childPostCmt->id)}}" class="btn btn-danger"
                               onclick="return confirm('You want to delete ?')">Delete</a>
                        @endif
                    </div>
                </div>
            @endforeach
            @if(Auth::user())
                <form method="post" action="childPost/{{$post->id}}/comment">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="comment">Phản hồi của bạn:</label>
                        <textarea class="form-control" name="content_cmt"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            @else
                <a class="nav-link" href="{{ route('login') }}">{{ __('You must LOGIN to writing comments.') }}</a>
                <a class="nav-link"
                   href="{{ route('register') }}">{{ __('If you do not have an account please click in here to REGISTER ') }}</a>
            @endif
            <br>
            <hr>
        @endforeach

        <section class="section pt-0">
            <div class="container">
                <div class="row">
                    <div class="col-xl-9 offset-xl-3">
                        <div class="commentbox-wrap">
                            <div class="title-page-min">Bình luận bài viết</div>
                            <div class="fb-comments" data-href="{{ $post->url }}" data-width="100%" data-numposts="10"
                                 data-order-by="reverse_time"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection
