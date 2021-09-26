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

                            @if (count($related_courses) > 0)
                            <div class="post-detail__relatedCourse">
                                <div class="title-page-min">Khoá học liên quan</div>
                                <div class="owl-carousel fixheight lessonbox-wrap-min">
                                    @foreach($related_courses as $item)
                                        @include('frontend.category.course_item', [ 'course' => $item ])
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <br/>
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
                                                <div class="h6">{{ $item->title }}</div>
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

    <section class="section pt-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 offset-xl-3">
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
                                <div class="commentbox" id="comment__js">
                                    <div class="commentForm">
                                    @if (auth()->check())
                                        <div class="commentForm__avatar avatar">
                                            <img src="{{ Auth::user()->avatar }}" alt="">
                                        </div>
                                        <form class="commentForm__form">
                                            <textarea name="text" placeholder="Viết bình luận" v-model="content" required></textarea>
                                            <div class="text-right">
                                                <button type="button" @click="addComment(null, content)" :disabled="loadingComment">Gửi bình luận</button>
                                            </div>
                                        </form>
                                    @else
                                    <div style="text-align: center">
                                        <a href="{{ route('login') }}" class="btn">Đăng nhập để thêm ý kiến</a>
                                    </div><br/>
                                    @endif
                                    </div>

                                    <div style="text-align: center;" v-if="loading">
                                        <img src="/tomato/assets/img/icon/icon-loading.gif">
                                    </div>
                                    <div class="commentList" v-else>
                                        <ul class="commentList__item">
                                            @if (auth()->check())
                                            <li v-for="(comment, index) in comments" v-if="comment.approved || comment.user_id == {{Auth::user()->id}}">
                                            @else
                                            <li v-for="(comment, index) in comments" v-if="comment.approved">
                                            @endif
                                                <div class="commentList__inner">
                                                    <div class="commentList__avatar">
                                                        <img :src="comment.user.avatar" alt="">
                                                    </div>
                                                    <div class="commentList__body">
                                                        <h3 class="commentList__name">
                                                            @{{ comment.user.name }}
                                                            <span style="color: #aaaaaa" v-if="!comment.approved">(Đang chờ phê duyệt)</span>
                                                        </h3>
                                                        <p class="commentList__text">@{{ comment.content }}</p>
                                                        <div class="commentList__meta">
                                                            <span class="meta-reply">
                                                                <a data-toggle="collapse" :href="'#replay-id' + comment.id" role="button" aria-expanded="false" aria-controls="replay-id1">
                                                                    <i class="fa fa-comments"></i>
                                                                    <span v-if="comment.child.length > 0">@{{ comment.child.filter(d => d.approved).length }} phản hồi</span>
                                                                    <span v-else>Phản hồi</span>
                                                                </a>
                                                            </span>
                                                            <span class="meta-date"><i class="fa fa-clock-o"></i>@{{ datetimeFormat(comment.created_at) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="collapse" :id="'replay-id' + comment.id">
                                                    <ul class="commentList__submenu">
                                                        @if (auth()->check())
                                                        <li v-for="(childComment, index) in comment.child" v-if="childComment.approved || childComment.user_id == {{Auth::user()->id}}">
                                                        @else
                                                        <li v-for="(childComment, index) in comment.child" v-if="childComment.approved">
                                                        @endif
                                                            <div class="commentList__inner">
                                                                <div class="commentList__avatar">
                                                                    <img :src="childComment.user.avatar" alt="">
                                                                </div>
                                                                <div class="commentList__body">
                                                                    <h3 class="commentList__name">
                                                                        @{{ childComment.user.name }}
                                                                        <span style="color: #aaaaaa" v-if="!childComment.approved">(Đang chờ phê duyệt)</span>
                                                                    </h3>
                                                                    <p class="commentList__text">@{{ childComment.content }}</p>
                                                                    <div class="commentList__meta">
                                                                        <span class="meta-date"><i class="fa fa-clock-o"></i>@{{ datetimeFormat(childComment.created_at) }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            @if (auth()->check())
                                                            <div class="commentForm">
                                                                <div class="commentForm__avatar avatar">
                                                                    <img src="{{ Auth::user()->avatar }}" alt="">
                                                                </div>
                                                                <form class="commentForm__form">
                                                                    <textarea name="text" placeholder="Viết bình luận" v-model="listContent[index]"></textarea>
                                                                    <div class="text-md-right">
                                                                        <button type="button" @click="addComment(comment.id, listContent[index])" :disabled="loadingComment">Trả lời</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>

                                        {{-- <a href="#" class="btn-load-comment btn btn--sm btn--block">Tải thêm bình luận <span><img src="assets/img/icon/icon-loading.gif"></span></a> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="fb-comments" data-href="{{ $post->url }}" data-width="100%" data-numposts="10"
                                    data-order-by="reverse_time"></div>
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
    new Vue({
        el: '#comment__js',
        data: {
            comments: [],
            content: '',
            listContent: [],
            loadingComment: false,
            loading: true,
        },
        mounted() {
            this.getComments();
        },
        methods: {
            addComment(parent_id, content) {
                if (content) {
                    this.loadingComment = true;
                    axios.post("{{ route('api.comment.add') }}", {
                        object_id: {{ $post->id }},
                        type: '{{ \App\Constants\ObjectType::POST }}',
                        parent_id: parent_id,
                        content: content
                    }).then(res => {
                        if (parent_id == null) {
                            this.comments.unshift(res.data);
                            this.content = '';
                        } else {
                            for (let i in this.comments){
                                if(this.comments[i].id == parent_id){
                                    this.comments[i].child.push(res.data);
                                    this.listContent[i] = '';
                                    break;
                                }
                            }
                        }
                    }).catch(err => {
                        // console.log(err);
                    }).finally(() => {
                        this.loadingComment = false;
                    });
                }
            },
            getComments() {
                this.loading = true;
                axios.get("{{ route('api.comment.getAll') }}", {
                    params: {
                        object_id: {{ $post->id }},
                        type: '{{ \App\Constants\ObjectType::POST }}'
                    }
                }).then(res => {
                    this.comments = res.data;
                    res.data.forEach(element => {
                        this.listContent.unshift('');
                    });
                    // console.log(res);
                }).catch(err => {
                    // console.log(err);
                }).finally(() => {
                    this.loading = false;
                });
            },
            datetimeFormat(str) {
                return moment(str).format('YYYY-MM-DD HH:mm:ss');
            },
        },
    });

</script>
@endsection
