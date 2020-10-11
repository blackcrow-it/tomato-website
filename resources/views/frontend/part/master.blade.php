@extends('frontend.master')

@section('header')
<title>{{ $part->title }}</title>
<meta content="description" value="{{ $course->meta_description ?? $course->description }}">
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
                <li class="breadcrumb-item"><a href="{{ $course->url }}">{{ $course->title }}</a></li>
            </ol>
        </nav>
        <h1 class="page-title__title">{{ $part->title }}</h1>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="layout layout--right learning-lesson-page">
            <div class="row stickyJs fix-header-top">
                <div class="col-xl-9">
                    <div class="layout-content">
                        <div class="learningLesson__header">
                            @yield('content')
                        </div>
                        <div class="learningLesson__footer">
                            <div class="commentbox-wrap">
                                <h3 class="title-page-min">Bình luận bài viết</h3>

                                <div class="tabJs">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Ý kiến học viên</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Bình luận Facebook</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="commentbox">
                                                <div class="commentForm">
                                                    <div class="commentForm__avatar avatar">
                                                        <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                    </div>
                                                    <form class="commentForm__form">
                                                        <textarea name="text" placeholder="Viết bình luận"></textarea>
                                                        <div class="text-right">
                                                            <button type="submit">Gửi bình luận</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="commentList">
                                                    <ul class="commentList__item">
                                                        <li>
                                                            <div class="commentList__inner">
                                                                <div class="commentList__avatar">
                                                                    <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                </div>
                                                                <div class="commentList__body">
                                                                    <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                    <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                    <div class="commentList__meta">
                                                                        <span class="meta-reply"><a data-toggle="collapse" href="#replay-id1" role="button" aria-expanded="false" aria-controls="replay-id1"><i class="fa fa-comments"></i>2 phản hồi</a></span>
                                                                        <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="collapse" id="replay-id1">
                                                                <ul class="commentList__submenu">
                                                                    <li>
                                                                        <div class="commentList__inner">
                                                                            <div class="commentList__avatar">
                                                                                <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                            </div>
                                                                            <div class="commentList__body">
                                                                                <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                                <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                                <div class="commentList__meta">
                                                                                    <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="commentList__inner">
                                                                            <div class="commentList__avatar">
                                                                                <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                            </div>
                                                                            <div class="commentList__body">
                                                                                <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                                <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                                <div class="commentList__meta">
                                                                                    <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="commentForm">
                                                                            <div class="commentForm__avatar avatar">
                                                                                <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                            </div>
                                                                            <form class="commentForm__form">
                                                                                <textarea name="text" placeholder="Viết bình luận"></textarea>
                                                                                <div class="text-md-right">
                                                                                    <button type="submit">Trả lời</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="commentList__inner">
                                                                <div class="commentList__avatar">
                                                                    <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                </div>
                                                                <div class="commentList__body">
                                                                    <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                    <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                    <div class="commentList__meta">
                                                                        <span class="meta-reply"><a data-toggle="collapse" href="#replay-id2" role="button" aria-expanded="false" aria-controls="replay-id2"><i class="fa fa-comments"></i>2 phản hồi</a></span>
                                                                        <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="collapse" id="replay-id2">
                                                                <ul class="commentList__submenu">
                                                                    <li>
                                                                        <div class="commentList__inner">
                                                                            <div class="commentList__avatar">
                                                                                <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                            </div>
                                                                            <div class="commentList__body">
                                                                                <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                                <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                                <div class="commentList__meta">
                                                                                    <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="commentList__inner">
                                                                            <div class="commentList__avatar">
                                                                                <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                            </div>
                                                                            <div class="commentList__body">
                                                                                <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                                <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                                <div class="commentList__meta">
                                                                                    <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="commentForm">
                                                                            <div class="commentForm__avatar avatar">
                                                                                <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                            </div>
                                                                            <form class="commentForm__form">
                                                                                <textarea name="text" placeholder="Viết bình luận"></textarea>
                                                                                <div class="text-md-right">
                                                                                    <button type="submit">Trả lời</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="commentList__inner">
                                                                <div class="commentList__avatar">
                                                                    <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                </div>
                                                                <div class="commentList__body">
                                                                    <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                    <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                    <div class="commentList__meta">
                                                                        <span class="meta-reply"><a data-toggle="collapse" href="#replay-id3" role="button" aria-expanded="false" aria-controls="replay-id3"><i class="fa fa-comments"></i>2 phản hồi</a></span>
                                                                        <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="collapse" id="replay-id3">
                                                                <ul class="commentList__submenu">
                                                                    <li>
                                                                        <div class="commentList__inner">
                                                                            <div class="commentList__avatar">
                                                                                <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                            </div>
                                                                            <div class="commentList__body">
                                                                                <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                                <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                                <div class="commentList__meta">
                                                                                    <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="commentList__inner">
                                                                            <div class="commentList__avatar">
                                                                                <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                            </div>
                                                                            <div class="commentList__body">
                                                                                <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                                <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                                <div class="commentList__meta">
                                                                                    <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="commentForm">
                                                                            <div class="commentForm__avatar avatar">
                                                                                <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                            </div>
                                                                            <form class="commentForm__form">
                                                                                <textarea name="text" placeholder="Viết bình luận"></textarea>
                                                                                <div class="text-md-right">
                                                                                    <button type="submit">Trả lời</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="commentList__inner">
                                                                <div class="commentList__avatar">
                                                                    <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                </div>
                                                                <div class="commentList__body">
                                                                    <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                    <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                    <div class="commentList__meta">
                                                                        <span class="meta-reply"><a data-toggle="collapse" href="#replay-id4" role="button" aria-expanded="false" aria-controls="replay-id4"><i class="fa fa-comments"></i>2 phản hồi</a></span>
                                                                        <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="collapse" id="replay-id4">
                                                                <ul class="commentList__submenu">
                                                                    <li>
                                                                        <div class="commentList__inner">
                                                                            <div class="commentList__avatar">
                                                                                <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                            </div>
                                                                            <div class="commentList__body">
                                                                                <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                                <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                                <div class="commentList__meta">
                                                                                    <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="commentList__inner">
                                                                            <div class="commentList__avatar">
                                                                                <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                            </div>
                                                                            <div class="commentList__body">
                                                                                <h3 class="commentList__name">Nguyễn Quốc Khánh</h3>
                                                                                <p class="commentList__text">Thầy ơi!! Tư vấn khóa combo N4-3, và bộ giáo trình kèm theo với ạ! cảm ơn ạ!!</p>
                                                                                <div class="commentList__meta">
                                                                                    <span class="meta-date"><i class="fa fa-clock-o"></i>14-05-2020 11:34</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="commentForm">
                                                                            <div class="commentForm__avatar avatar">
                                                                                <img src="assets/img/icon/icon-avatar-comment.jpg" alt="">
                                                                            </div>
                                                                            <form class="commentForm__form">
                                                                                <textarea name="text" placeholder="Viết bình luận"></textarea>
                                                                                <div class="text-md-right">
                                                                                    <button type="submit">Trả lời</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>

                                                    <a href="#" class="btn-load-comment btn btn--sm btn--block">Tải thêm bình luận <span><img src="assets/img/icon/icon-loading.gif"></span></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                            <img src="assets/img/image/comment-facebook.jpg" class="img-fullwidth">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 sticky">
                    <div class="layout-sidebar">
                        <div class="learning-process">
                            <div class="learning-process__header">
                                <h3 class="f-title">Tiến trình học tập</h3>
                            </div>

                            <div class="learning-process__list">
                                <div id="accordion" class="accordionJs">
                                    @foreach($lessons as $lesson)
                                        <div class="panel">
                                            <h3 class="panel__title" data-toggle="collapse" data-target="#collapse-id-{{ $loop->index }}" aria-expanded="true" aria-controls="collapse-id-{{ $loop->index }}">
                                                {{ $lesson->title }}
                                            </h3>
                                            <div id="collapse-id-{{ $loop->index }}" class="collapse show" data-parent="#accordion">
                                                <div class="panel__entry">
                                                    <ul class="collapse__submenu">
                                                        @foreach($lesson->parts as $p)
                                                            <li class="{{ $p->id == $part->id ? 'done current' : '' }}"><a href="{{ $p->url }}"><span></span> {{ $p->title }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('footer')
<script>
    $('.learning-process__list').animate({
        scrollTop: $('.learning-process__list .panel .collapse__submenu li.current').position().top - 100
    }, 500);

</script>
@yield('part_script')
@endsection
