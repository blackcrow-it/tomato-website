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
            <div id="upload-form">
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
                        <input v-model="title" type="text" placeholder="Tiêu đề" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Chọn nguồn upload</label>
                        <div class="form-check">
                            <input v-model="uploadType" class="form-check-input" type="radio" id="rb-upload-type-0" :value="undefined">
                            <label class="form-check-label" for="rb-upload-type-0">Không upload</label>
                        </div>
                        <div class="form-check">
                            <input v-model="uploadType" class="form-check-input" type="radio" id="rb-upload-type-1" value="transcode">
                            <label class="form-check-label" for="rb-upload-type-1">Upload thư mục transcode</label>
                        </div>
                        <div class="form-check">
                            <input v-model="uploadType" class="form-check-input" type="radio" id="rb-upload-type-2" value="local_file">
                            <label class="form-check-label" for="rb-upload-type-2">Upload video, transcode trên server (tối đa 1GB)</label>
                        </div>
                        <div class="form-check">
                            <input v-model="uploadType" class="form-check-input" type="radio" id="rb-upload-type-3" value="drive">
                            <label class="form-check-label" for="rb-upload-type-3">Upload từ google drive, transcode trên server (tối đa 10GB)</label>
                        </div>
                    </div>
                    <div v-if="uploadType == 'transcode'" class="form-group">
                        <label>Chọn thư mục transcode</label>
                        <input type="file" class="d-none" id="js-input-upload-transcode" directory webkitdirectory mozdirectory @change="inputTranscodeChange">
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-info mr-3" @click="openUploadTranscodeDialog">Chọn thư mục</button>
                            <span>@{{ uploadTranscodeFileCount }}</span>
                        </div>
                    </div>
                    <div v-if="uploadType == 'local_file'" class="form-group">
                        <label>Chọn video</label>
                        <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Việc transcode video sẽ được thực hiện trên server.<br>- Dung lượng video tối đa không quá 1GB."></i></small>
                        <input type="file" class="d-none" id="js-input-upload-local-file" accept="video/*" @change="inputLocalFileChange">
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-info mr-3" @click="openUploadLocalFileDialog">Chọn video</button>
                            <span>@{{ uploadLocalFilename }}</span>
                        </div>
                    </div>
                    <div v-if="uploadType == 'drive'" class="form-group">
                        <label>Chọn video</label>
                        <small><i class="fas fa-question-circle text-warning" data-toggle="popover" data-html="true" data-content="- Việc transcode video sẽ được thực hiện trên server.<br>- Dung lượng video tối đa không quá 10GB."></i></small>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-info mr-3" @click="openUploadDriveDialog">Chọn video</button>
                            <span>@{{ uploadDriveFilename }}</span>
                        </div>
                    </div>
                    <div v-if="submitting" class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" :style="{ 'width': progressPercent + '%' }">@{{ progressPercent }}%</div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" :disabled="submitting" @click="submitData"><i class="fas fa-save"></i> Lưu</button>
                    <a class="btn btn-warning float-right" href="/TomatoTranscode.exe"><i class="fas fa-download"></i> Download transcode app</a>
                </div>
            </div>
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
<script src="https://apis.google.com/js/api.js"></script>

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
    new Vue({
        el: '#upload-form',
        data: {
            title: "{{ old('title') ?? $part->title }}",
            uploadType: undefined,
            uploadTranscodeFileCount: undefined,
            uploadLocalFilename: undefined,
            uploadDriveFilename: undefined,
            uploadDriveFileId: undefined,
            developerKey: '{{ config("services.google.api_key") }}',
            clientId: '{{ config("services.google.client_id") }}',
            appId: '{{ config("services.google.project_number") }}',
            oauthToken: null,
            picker: undefined,
            submitting: false,
            progressPercent: 0,
        },
        updated() {
            $('[data-toggle="popover"]').popover({
                trigger: 'hover',
                boundary: 'viewport'
            });
        },
        methods: {
            openUploadTranscodeDialog() {
                $('#js-input-upload-transcode').trigger('click');
            },
            openUploadLocalFileDialog() {
                $('#js-input-upload-local-file').trigger('click');
            },
            inputTranscodeChange(e) {
                const files = $(e.target).prop('files');

                if (files.length == 0) {
                    this.uploadTranscodeFileCount = undefined;
                    return;
                }

                const webkitRelativePath = files[0].webkitRelativePath;
                const directoryName = webkitRelativePath.split('/')[0];

                this.uploadTranscodeFileCount = `${directoryName} (${files.length} files)`;
            },
            inputLocalFileChange(e) {
                const files = $(e.target).prop('files');

                if (files.length == 0) {
                    this.uploadLocalFilename = undefined;
                    return;
                }

                const file = files[0];

                this.uploadLocalFilename = file.name;
            },
            async openUploadDriveDialog() {
                this.uploadDriveFilename = undefined;
                this.uploadDriveFileId = undefined;

                if (this.picker != undefined) {
                    this.picker.setVisible(true);
                    return;
                }

                if (this.oauthToken == null) {
                    const response = await axios.post('{{ route("admin.part_video.get_drive_token") }}');
                    this.oauthToken = response.token;
                }

                await gapi.load('auth2');
                gapi.load('picker', () => {
                    this.createPicker();
                });
            },
            createPicker() {
                const viewId = new google.picker.DocsView(google.picker.ViewId.DOCS)
                    .setOwnedByMe(true)
                    .setIncludeFolders(true);

                this.picker = new google.picker.PickerBuilder()
                    .disableFeature(google.picker.Feature.MULTISELECT_ENABLED)
                    .addView(viewId)
                    .setOAuthToken(this.oauthToken)
                    .setDeveloperKey(this.developerKey)
                    .setCallback(this.pickerCallback)
                    .build();

                this.picker.setVisible(true);
            },
            async pickerCallback(data) {
                if (data[google.picker.Response.ACTION] !== google.picker.Action.PICKED) return;
                if (data.docs.length == 0) return;

                const file = data.docs[0];
                if (!file.mimeType.startsWith('video/')) {
                    alert('File bạn chọn không phải video. Vui lòng chọn lại.');
                    this.picker.setVisible(true);
                    return;
                }

                if (file.sizeBytes > 10 * 1024 * 1024 * 1024) {
                    alert('File bạn chọn vượt quá 10GB. Vui lòng chọn lại.');
                    this.picker.setVisible(true);
                    return;
                }

                this.uploadDriveFilename = file.name;
                this.uploadDriveFileId = file.id;
            },
            async submitData() {
                const transcodeStatus = '{{ $data->transcode_status ?? \App\Constants\TranscodeStatus::COMPLETED }}';
                if (
                    transcodeStatus != '{{ \App\Constants\TranscodeStatus::COMPLETED }}' &&
                    transcodeStatus != '{{ \App\Constants\TranscodeStatus::FAIL }}'
                ) {
                    alert('Video đang được xử lý, vui lòng thử lại sau.');
                    return;
                }

                this.submitting = true;
                window.onbeforeunload = function (e) {
                    e.returnValue = '';
                };

                await axios.post('{{ route("admin.part_video.edit", [ "part_id" => $part->id, "lesson_id" => $lesson->id ]) }}', {
                    title: this.title,
                });

                if (this.uploadType == undefined) {
                    location.href = '{{ route("admin.part.list", [ "lesson_id" => $lesson->id ]) }}';
                    return;
                }

                switch (this.uploadType) {
                    case 'transcode':
                        await this.submitTranscode();
                        break;

                    case 'local_file':
                        await this.submitLocalFile();
                        break;

                    case 'drive':
                        await this.submitDriveFile();
                        break;
                }

                this.submitting = false;
                window.onbeforeunload = function (e) {
                    delete e.returnValue;
                };
                location.reload();
            },
            async submitTranscode() {
                const files = $('#js-input-upload-transcode').prop('files');

                if (files.length == 0) return;

                await axios.post('{{ route("admin.part_video.clear_s3") }}', {
                    part_id: '{{ $part->id }}'
                });

                try {
                    for (let index = 0; index < files.length; index++) {
                        const file = files[index];

                        const webkitRelativePath = file.webkitRelativePath;
                        const pathSplit = webkitRelativePath.split('/');
                        pathSplit.splice(0, 1);
                        const path = pathSplit.join('/');

                        const formData = new FormData();
                        formData.append('part_id', '{{ $part->id }}');
                        formData.append('path', path);
                        formData.append('file', file);

                        await axios.post('{{ route("admin.part_video.upload_transcode") }}', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        });

                        const percent = Math.ceil((index + 1) / files.length * 100);
                        this.progressPercent = percent;
                    }
                } catch {
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                }
            },
            async submitLocalFile() {
                try {
                    const files = $('#js-input-upload-local-file').prop('files');

                    if (files.length == 0) return;

                    const formData = new FormData();
                    formData.append('part_id', '{{ $part->id }}');
                    formData.append('file', files[0]);

                    await axios.post('{{ route("admin.part_video.upload_video") }}', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                        onUploadProgress: event => {
                            console.log(event);
                            const percent = Math.round((event.loaded * 100) / event.total);
                            this.progressPercent = percent;
                        }
                    });
                } catch {
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                }
            },
            async submitDriveFile() {
                if (this.uploadDriveFileId == undefined) return;

                await axios.post('{{ route("admin.part_video.upload_drive") }}', {
                    part_id: '{{ $part->id }}',
                    drive_id: this.uploadDriveFileId
                });
            },
        },
    });

</script>
@endsection
