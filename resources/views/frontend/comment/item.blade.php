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
                            @can('can_access_admin_dashboard')
                            <span class="meta-reply" v-if="!comment.approved">
                                <span style="color: blue; cursor: pointer;" @click="approveComment(comment.id, index)">
                                    <i class="fa fa-thumbs-o-up"></i>
                                    <span>Phê duyệt</span>
                                </span>
                            </span>
                            <span class="meta-reply">
                                <span href="#" style="color: #ff0000; cursor: pointer;" @click="deleteComment(comment.id, index)">
                                    <i class="fa fa-trash"></i>
                                    <span>Xoá</span>
                                </span>
                            </span>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="collapse" :id="'replay-id' + comment.id">
                    <ul class="commentList__submenu">
                        @if (auth()->check())
                        <li v-for="(childComment, indexChild) in comment.child" v-if="childComment.approved || childComment.user_id == {{Auth::user()->id}}">
                        @else
                        <li v-for="(childComment, indexChild) in comment.child" v-if="childComment.approved">
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
                                        @can('can_access_admin_dashboard')
                                        <span class="meta-reply" v-if="!childComment.approved">
                                            <span :style="[comment.approved ? {'color': 'blue', 'cursor': 'pointer'} : {'color': 'gray', 'cursor': 'not-allowed'}]" @click="approveComment(childComment.id, indexChild, index)">
                                                <i class="fa fa-thumbs-o-up"></i>
                                                <span>Phê duyệt</span>
                                            </span>
                                        </span>
                                        <span class="meta-reply" @click="deleteComment(comment.id, indexChild, index)">
                                            <span href="#" style="color: #ff0000; cursor: pointer;">
                                                <i class="fa fa-trash"></i>
                                                <span>Xoá</span>
                                            </span>
                                        </span>
                                        @endcan
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
                            @else
                            <div style="text-align: center">
                                <a href="{{ route('login') }}" class="btn btn--sm">Đăng nhập để phản hồi</a>
                            </div><br/>
                            @endif
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        {{-- <a href="#" class="btn-load-comment btn btn--sm btn--block">Tải thêm bình luận <span><img src="assets/img/icon/icon-loading.gif"></span></a> --}}
    </div>
</div>
