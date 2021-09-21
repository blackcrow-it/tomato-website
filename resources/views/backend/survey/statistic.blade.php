@extends('backend.master')

@section('title')
Thống kê phiếu khảo sát
@endsection

@section('content-header')
<style>
    .title-question {
        font-family: "Google Sans",Roboto,Arial,sans-serif;
        font-size: 16px;
        letter-spacing: .1px;
        line-height: 24px;
        color: #202124;
        font-weight: 400;
        max-width: 100%;
        min-width: 0%;
        word-break: break-word;
    }
    .number-answer {
        font: 400 13px Roboto,RobotoDraft,Helvetica,Arial,sans-serif;
    }
    .chart-answer {
        width: 400px;
        height: 400px;
        margin: auto;
    }
    .list-answer {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 12px;
    }
    .list-answer .item-answer {
        font-family: Roboto,Arial,sans-serif;
        font-size: 14px;
        font-weight: 400;
        letter-spacing: .2px;
        line-height: 20px;
        color: #202124;
        background-color: #f8f9fa;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        margin: 4px 0 0;
        padding: 10px;
    }
</style>
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Phiếu: {{ $part_survey->title }} (#{{ $part_survey->id }})</h1>
        <div class="text-dark">Khoá học: <a href="{{$part_survey->lesson->course->url}}" target="_blank">{{ $part_survey->lesson->course->title }}</a></div>
        {{-- <i class="fas fa-calendar-alt"></i> {{ $survey->created_at }}<br/> <i class="fas fa-sync"></i> {{ $survey->updated_at }} --}}
    </div>
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="{{ redirect()->back()->getTargetUrl() }}" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Quay lại</a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row mb-2">
    <div class="offset-sm-2 col-sm-8">
        @if ($questions)
            @foreach ($questions as $question)
            <div class="card">
                <div class="card-body" style="padding: 12px 12px 24px 24px;">
                    <div class="title-question">{{ $loop->index + 1 }}. {{$question['question']}}</div>
                    <div class="number-answer">({{$question['totalAnswer']}} câu trả lời)</div>
                    @if ($question['type'] == 'radio')
                        <div class="chart-answer">
                            <canvas id="myChart{{ $loop->index }}" width="100" height="100"></canvas>
                        </div>
                        @if ($question['comments'])
                        <div class="number-answer">Nhận xét: </div>
                        <div class="list-answer">
                            @foreach ($question['comments'] as $comment)
                                <div class="item-answer">{{ $comment }}</div>
                            @endforeach
                        </div>
                        @endif
                    @elseif ($question['type'] == 'textarea')
                        <div class="list-answer">
                            @foreach ($question['answers'] as $answer)
                                <div class="item-answer">{{ $answer }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        @else
        <h4 style="text-align: center">Chưa có câu hỏi trong phiếu khảo sát</h4>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
    @foreach ($questions as $question)
    @if ($question['type'] == 'radio')
    new Chart(
        document.getElementById('myChart{{ $loop->index }}'),
        {
            type: 'pie',
            data: {
                labels: [
                    @foreach ($question['options'] as $option)
                    '{{$option}}',
                    @endforeach
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: [
                        @foreach ($question['answers'] as $answer)
                        '{{$answer}}',
                        @endforeach
                    ],
                    backgroundColor: [
                    'rgb(51, 102, 204)',
                    'rgb(220, 57, 18)',
                    'rgb(255, 153, 0)',
                    'rgb(16, 150, 24)',
                    'rgb(153, 0, 153)',
                    'rgb(0, 153, 198)',
                    'rgb(221, 68, 119)',
                    'rgb(102, 170, 0)',
                    'rgb(184, 46, 46)',
                    'rgb(49, 99, 149)',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "right",
                        align: "middle"
                    },
                }
            }
        }
    );
    @endif
    @endforeach
</script>
@endsection
