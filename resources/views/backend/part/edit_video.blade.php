@extends('backend.master')

@section('title')
Sửa đầu mục
@endsection

@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/video.js@7.5.0/dist/video-js.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/videojs-hls-quality-selector@1.1.1/dist/videojs-hls-quality-selector.css">
<style>
    .video-js .vjs-big-play-button {
        left: 50%;
        top: 50%;
        transform: translateX(-50%) translateY(-50%);
    }

</style>
@endsection

@section('content-header')
<div class="row">
    <div class="col-sm-2">
        <img src="{{ $course->thumbnail }}">
    </div>
    <div class="col-sm-5">
        <strong>{{ $course->title }}</strong>
        <br>
        <a href="{{ route('course', [ 'slug' => $course->slug ]) }}" target="_blank"><small><em>{{ route('course', [ 'slug' => $course->slug ]) }}</em></small></a>
        <br>
        {{ $course->description }}
    </div>
    <div class="col-sm-5">
        <strong>{{ $lesson->title }}</strong>
    </div>
</div>
<hr>
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Sửa đầu mục</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ route('admin.part.list', [ 'lesson_id' => $lesson->id ]) }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
        </div>
    </div><!-- /.col -->
</div>
@endsection

@section('content')
@if($errors->any())
    <div class="callout callout-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $msg)
                <li>{{ $msg }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="callout callout-success">
        @if(is_array(session('success')))
            <ul class="mb-0">
                @foreach(session('success') as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        @else
            {{ session('success') }}
        @endif
    </div>
@endif
@switch($data->transcode_status ?? null)
    @case(\App\Constants\TranscodeStatus::PENDING)
        <div class="callout callout-info">
            Video đang chờ tới lượt transcode.
        </div>
        @break
    @case(\App\Constants\TranscodeStatus::PROCESSING)
        <div class="callout callout-warning">
            Video đang được transcode ({{ $data->transcode_message }}).
        </div>
        @break
    @case(\App\Constants\TranscodeStatus::FAIL)
        <div class="callout callout-danger">
            <p>Có lỗi xảy ra trong quá trình transcode video.</p>
            <pre>{{ $data->transcode_message }}</pre>
        </div>
        @break
@endswitch
<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <form action="" method="POST" class="js-main-form">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Loại đầu mục</label>
                        <div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" id="cr-type-1" value="{{ \App\Constants\PartType::VIDEO }}" {{ $part->type == \App\Constants\PartType::VIDEO ? 'checked' : '' }} disabled>
                                <label class="form-check-label" for="cr-type-1">Video</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" id="cr-type-2" value="{{ \App\Constants\PartType::YOUTUBE }}" {{ $part->type == \App\Constants\PartType::YOUTUBE ? 'checked' : '' }} disabled>
                                <label class="form-check-label" for="cr-type-2">Youtube</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" id="cr-type-3" value="{{ \App\Constants\PartType::CONTENT }}" {{ $part->type == \App\Constants\PartType::CONTENT ? 'checked' : '' }} disabled>
                                <label class="form-check-label" for="cr-type-3">Bài viết</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" id="cr-type-4" value="{{ \App\Constants\PartType::TEST }}" {{ $part->type == \App\Constants\PartType::TEST ? 'checked' : '' }} disabled>
                                <label class="form-check-label" for="cr-type-4">Trắc nghiệm</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="radio" id="cr-type-5" value="{{ \App\Constants\PartType::SURVEY }}" {{ $part->type == \App\Constants\PartType::SURVEY ? 'checked' : '' }} disabled>
                                <label class="form-check-label" for="cr-type-5">Khảo sát</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tiêu đề</label>
                        <input type="text" name="title" placeholder="Tiêu đề" value="{{ $part->title ?? old('title') }}" class="form-control @error('title') is-invalid @enderror">
                        @error('title')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Thư mục stream video</label>
                        <input type="file" class="d-none js-input-directory" directory webkitdirectory mozdirectory>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-info mr-3 js-open-select-directory">Chọn thư mục</button>
                            <span class="js-selected-directory-name"></span>
                        </div>
                    </div>
                    <div class="mb-3">hoặc</div>
                    <div class="form-group">
                        <label>Chọn video</label>
                        <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Việc transcode video sẽ được thực hiện trên server."></i></small>
                        <br>
                        <input type="file" class="js-input-file" accept="video/*">
                    </div>
                    <div class="progress js-upload-progress" style="display:none">
                        <div class="progress-bar progress-bar-striped progress-bar-animated"></div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary js-save-button"><i class="fas fa-save"></i> Lưu</button>
                    <a class="btn btn-warning float-right" href="/TomatoTranscode.exe"><i class="fas fa-download"></i> Download transcode app</a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                @if($video_url)
                    <video id="video" class="video-js" controls preload="auto" data-setup="{}"></video>
                @else
                    <div class="text-center">
                        Video sau khi up lên hoàn tất sẽ được xem trước tại đây.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/video.js@7.5.0/dist/video.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.15.0/videojs-contrib-hls.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/videojs-contrib-quality-levels@2.0.9/dist/videojs-contrib-quality-levels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/videojs-hls-quality-selector@1.1.1/dist/videojs-hls-quality-selector.min.js"></script>

<script>
    var player = videojs('video');
    player.src({
        src: '{{ $video_url }}',
        type: 'application/x-mpegURL'
    });
    player.aspectRatio('16:9');
    player.fluid(true);
    player.hlsQualitySelector();

</script>

<script>
    $('.js-open-select-directory').click(function () {
        $('.js-input-directory').trigger('click');
    });

    $('.js-input-directory').change(function () {
        var files = $(this).prop('files');
        var count = files.length;

        if (count == 0) {
            $('.js-selected-directory-name').text('');
            return;
        }

        var webkitRelativePath = files[0].webkitRelativePath;

        var directoryName = webkitRelativePath.split('/')[0];

        $('.js-selected-directory-name').text(`${directoryName} (${count} files)`);
    });

    $('.js-main-form').submit(function (e) {
        e.preventDefault();

        var transcodeStatus = '{{ $data->transcode_status ?? \App\Constants\TranscodeStatus::COMPLETED }}';
        if (
            transcodeStatus != '{{ \App\Constants\TranscodeStatus::COMPLETED }}' &&
            transcodeStatus != '{{ \App\Constants\TranscodeStatus::FAIL }}'
        ) {
            alert('Video đang được xử lý, vui lòng thử lại sau.');
            return;
        }

        $('.js-save-button').prop('disabled', true);

        if ($('.js-input-directory').prop('files').length > 0 && $('.js-input-file').prop('files').length > 0) {
            var confirmUploadTarget = confirm('Vì bạn chọn cả video và thư mục transcode, hệ thống sẽ ưu tiên upload thư mục transcode. Bạn có muốn tiếp tục?');
            if (!confirmUploadTarget) {
                $('.js-save-button').prop('disabled', false);
                return;
            }
        }

        $.post('{{ route("admin.part_video.edit", [ "part_id" => $part->id, "lesson_id" => $lesson->id ]) }}', $(this).serialize())
            .done(function () {
                if ($('.js-input-directory').prop('files').length > 0) {
                    var files = $('.js-input-directory').prop('files');

                    if (files.length == 0) {
                        location.href = '{{ route("admin.part.list", [ "lesson_id" => $lesson->id ]) }}';
                        return;
                    }

                    $('.js-upload-progress').show();
                    window.onbeforeunload = function (e) {
                        e.returnValue = '';
                    };

                    $.post('{{ route("admin.part_video.clear_s3") }}', {
                        part_id: '{{ $part->id }}'
                    }).done(function () {
                        var uploadFail = function () {
                            alert('Có lỗi xảy ra, vui lòng thử lại.');
                            $('.js-save-button').prop('disabled', false);
                            $('.js-upload-progress').hide();
                            window.onbeforeunload = function (e) {
                                delete e['returnValue'];
                            };
                        };

                        var uploadNextFile = function (index) {
                            var file = files[index];

                            var webkitRelativePath = file.webkitRelativePath;
                            var pathSplit = webkitRelativePath.split('/');
                            pathSplit.splice(0, 1);
                            var path = pathSplit.join('/');

                            var formData = new FormData();
                            formData.append('part_id', '{{ $part->id }}');
                            formData.append('path', path);
                            formData.append('file', file);

                            $.ajax({
                                url: '{{ route("admin.part_video.upload_transcode") }}',
                                method: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                            }).done(function () {
                                var percent = Math.ceil((index + 1) / files.length * 100);
                                $('.js-upload-progress .progress-bar').width(percent + '%');
                                $('.js-upload-progress .progress-bar').text(percent + '%');

                                if (index + 1 < files.length) {
                                    uploadNextFile(index + 1);
                                    return;
                                }

                                window.onbeforeunload = function (e) {
                                    delete e['returnValue'];
                                };

                                setTimeout(function () {
                                    alert('Upload video thành công.');
                                    location.reload();
                                }, 500);
                            }).fail(function () {
                                uploadFail();
                            });
                        };

                        uploadNextFile(0);
                    }).fail(function () {
                        alert('Có lỗi xảy ra, vui lòng thử lại.');
                        $('.js-save-button').prop('disabled', false);
                        $('.js-upload-progress').hide();
                        window.onbeforeunload = function (e) {
                            delete e['returnValue'];
                        };
                    });
                } else {
                    var files = $('.js-input-file').prop('files');

                    if (files.length == 0) {
                        location.href = '{{ route("admin.part.list", [ "lesson_id" => $lesson->id ]) }}';
                        return;
                    }

                    $('.js-upload-progress').show();
                    window.onbeforeunload = function (e) {
                        e.returnValue = '';
                    };

                    var formData = new FormData();
                    formData.append('part_id', '{{ $part->id }}');
                    formData.append('file', files[0]);

                    $.ajax({
                        url: '{{ route("admin.part_video.upload_video") }}',
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        xhr: function () {
                            var xhr = $.ajaxSettings.xhr();
                            if (xhr.upload) {
                                xhr.upload.addEventListener('progress', function (event) {
                                    var percent = 0;
                                    var position = event.loaded || event.position;
                                    var total = event.total;
                                    if (event.lengthComputable) {
                                        percent = Math.ceil(position / total * 100);
                                    }

                                    $('.js-upload-progress .progress-bar').width(percent + '%');
                                    $('.js-upload-progress .progress-bar').text(percent + '%');
                                }, true);
                            }
                            return xhr;
                        },
                    }).done(function () {
                        window.onbeforeunload = function (e) {
                            delete e['returnValue'];
                        };

                        setTimeout(function () {
                            alert('Upload video thành công.');
                            location.reload();
                        }, 500);
                    }).fail(function () {
                        alert('Có lỗi xảy ra, vui lòng thử lại.');
                        $('.js-save-button').prop('disabled', false);
                        $('.js-upload-progress').hide();
                        window.onbeforeunload = function (e) {
                            delete e['returnValue'];
                        };
                    });
                }
            })
            .fail(function () {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
                $('.js-save-button').prop('disabled', false);
            });
    });

</script>
@endsection
