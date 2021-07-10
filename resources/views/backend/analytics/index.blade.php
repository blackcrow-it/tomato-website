@extends('backend.master')

@section('title')
    Google Analytics
@endsection

@section('content-header')
    <style>
        .link-visited-page {
            font-size: 12px;
        }
    </style>
    <h1>Google Analytics</h1><br />
    <div class="row">
        <section class="col-lg-7">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="user-analytics-tab" data-toggle="pill" href="#user-analytics"
                                role="tab" aria-controls="user-analytics" aria-selected="true">
                                <h5>Người dùng</h5>
                                <h4 id="numberUserAnalytics">0</h4>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="session-analytics-tab" data-toggle="pill" href="#session-analytics"
                                role="tab" aria-controls="session-analytics" aria-selected="false">
                                <h5>Số phiên</h5>
                                <h4 id="numberSessionAnalytics">0</h4>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="bounce-rate-analytics-tab" data-toggle="pill"
                                href="#bounce-rate-analytics" role="tab" aria-controls="bounce-rate-analytics"
                                aria-selected="false">
                                <h5>Tỷ lệ thoát</h5>
                                <h4 id="percentBounceRateAnalytics">0%</h4>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="session-duration-analytics-tab" data-toggle="pill"
                                href="#session-duration-analytics" role="tab" aria-controls="session-duration-analytics"
                                aria-selected="false">
                                <h5>Thời lượng phiên</h5>
                                <h4 id="timeSessionDurationAnalytics">0 giây</h4>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade show active" id="user-analytics" role="tabpanel"
                            aria-labelledby="user-analytics-tab">
                            <dir class="overlay-wrapper">
                                <div class="overlay loading">
                                    <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                    <div class="text-bold pt-2">Đang tải...</div>
                                </div>
                                <canvas id="userAnalyticsChart" width="400" height="200"></canvas>
                            </dir>
                        </div>
                        <div class="tab-pane fade" id="session-analytics" role="tabpanel"
                            aria-labelledby="session-analytics-tab">
                            <dir class="overlay-wrapper">
                                <div class="overlay loading">
                                    <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                    <div class="text-bold pt-2">Đang tải...</div>
                                </div>
                                <canvas id="sessionAnalyticsChart" width="400" height="200"></canvas>
                            </dir>
                        </div>
                        <div class="tab-pane fade" id="bounce-rate-analytics" role="tabpanel"
                            aria-labelledby="bounce-rate-analytics-tab">
                            <dir class="overlay-wrapper">
                                <div class="overlay loading">
                                    <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                    <div class="text-bold pt-2">Đang tải...</div>
                                </div>
                                <canvas id="bounceRateAnalyticsChart" width="400" height="200"></canvas>
                            </dir>
                        </div>
                        <div class="tab-pane fade" id="session-duration-analytics" role="tabpanel"
                            aria-labelledby="session-duration-analytics-tab">
                            <dir class="overlay-wrapper">
                                <div class="overlay loading">
                                    <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                    <div class="text-bold pt-2">Đang tải...</div>
                                </div>
                                <canvas id="sessionDurationAnalyticsChart" width="400" height="200"></canvas>
                            </dir>
                        </div>
                    </div>
                    <div class="tab-custom-content">
                        <div class="form-group" style="margin-bottom: 0;">
                            <div class="input-group">
                                <button type="button" class="btn btn-default float-right" id="daterange-btn">
                                    <i class="far fa-calendar-alt"></i> <span id="text-daterange">Chọn khoảng thời
                                        gian</span>
                                    <i class="fas fa-caret-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </section>
        <section class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Trang được truy cập nhiều nhất</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0" style="height: 392px;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>Đường dẫn</th>
                                <th>Số lần xem trang</th>
                                <th>Trang</th>
                            </tr>
                        </thead>
                        <div class="overlay-wrapper">
                            <div class="overlay loading-visited-page">
                                <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                                <div class="text-bold pt-2">Đang tải...</div>
                            </div>
                            <tbody id="table-visited-pages">
                                <tr>
                                    <td><a href="/">/</a></td><td>26</td><td>Học ngoại ngữ Online Tomato | Website học Online ngoại ngữ tốt nhất</td>
                                </tr>
                            </tbody>
                        </div>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <div class="form-group" style="margin-bottom: 0;">
                        <div class="input-group">
                            <button type="button" class="btn btn-default float-right" id="daterange-visited-page">
                                <i class="far fa-calendar-alt"></i> <span id="text-daterange-visited-page">Chọn khoảng thời
                                    gian</span>
                                <i class="fas fa-caret-down"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thống kê theo thiết bị</h3>
                    <div class="card-tools">
                        <div class="form-group" style="margin-bottom: 0;">
                            <div class="input-group">
                                <button type="button" class="btn btn-default float-right" id="daterange-device">
                                    <i class="far fa-calendar-alt"></i> <span id="text-daterange-device">Chọn khoảng thời
                                        gian</span>
                                    <i class="fas fa-caret-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="overlay-wrapper">
                        <div class="overlay loading-device">
                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                            <div class="text-bold pt-2">Đang tải...</div>
                        </div>
                    <div class="row">
                        <div class="col-lg-5">
                            <canvas id="deviceChart" width="400" height="400"></canvas>
                        </div>
                        <div class="col-lg-7">
                            <div class="table-responsive" style="height: 457px">
                            <table class="table table-head-fixed text-nowrap">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Thiết bị di động</th>
                                        <th>Số người dùng</th>
                                        <th>Số phiên</th>
                                        <th>Thời gian trung bình một phiên</th>
                                        <th>Tỉ lệ thoát</th>
                                    </tr>
                                </thead>
                                <tbody id="table-device-mobile">
                                    <tr>
                                        <td>1</td><td>Apple Iphone 11</td><td>10</td><td>20</td><td>5 phút</td><td>20%</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        function secondsToHms(d) {
            d = Number(d);
            if (d == 0) {
                return '0 giây'
            }
            var h = Math.floor(d / 3600);
            var m = Math.floor(d % 3600 / 60);
            var s = Math.floor(d % 3600 % 60);

            var hDisplay = h > 0 ? h + (" giờ ") : "";
            var mDisplay = m > 0 ? m + (" phút ") : "";
            var sDisplay = s > 0 ? s + (" giây ") : "";
            return hDisplay + mDisplay + sDisplay;
        }

        function enumerateDaysBetweenDates(startDate, endDate) {
            var dates = [];

            var currDate = moment(startDate).startOf('day');
            var lastDate = moment(endDate).startOf('day');

            while (currDate.add(1, 'days').diff(lastDate) < 0) {
                dates.push(currDate.clone().toDate());
            }
            return dates;
        };

        //Date range as a button
        $('#daterange-btn').daterangepicker({
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 ngày trước': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
                    '28 ngày trước': [moment().subtract(28, 'days'), moment().subtract(1, 'days')],
                    '90 ngày trước': [moment().subtract(90, 'days'), moment().subtract(1, 'days')],
                    '180 ngày trước': [moment().subtract(180, 'days'), moment().subtract(1, 'days')]
                },
                startDate: moment().subtract(1, 'days'),
                endDate: moment().subtract(1, 'days')
            },
            function(start, end) {
                if (start.format('DD/MM/YYYY') == end.format('DD/MM/YYYY')) {
                    $('#text-daterange').html(start.format('DD/MM/YYYY'))
                } else {
                    $('#text-daterange').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                }
                getDataSummary(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
            }
        )

        var labelShow = [];
        var labels = [];
        var labelsAgo = [];

        const footerTooltipChart = (tooltipItems) => {
            let labelReturn = '-'
            if (tooltipItems[0].parsed.y != 0 && tooltipItems[1].parsed.y != 0) {
                let ratio = 0;
                ratio = Math.round(((tooltipItems[0].parsed.y - tooltipItems[1].parsed.y) / tooltipItems[1].parsed.y) *
                    100,
                    1)
                let labelCompare = ''
                if (ratio < 0) {
                    labelCompare = 'Giảm '
                } else if (ratio > 0) {
                    labelCompare = 'Tăng '
                }
                labelReturn = labelCompare + Math.abs(ratio) + '%';
            }
            return labelReturn
        };
        const titleTooltipChart = (tooltipItems) => {
            return labels[tooltipItems[0].parsed.x] + ' vs ' + labelsAgo[tooltipItems[0].parsed.x]
        };

        var ctxUser = document.getElementById('userAnalyticsChart').getContext('2d');
        var userAnalyticsChart = new Chart(ctxUser, {
            type: 'line',
            data: {
                labels: labelShow,
                datasets: [{
                    label: 'Hiện tại',
                    data: [],
                    fill: false,
                    borderColor: '#1a73e8',
                    tension: 0.1
                }, {
                    label: 'Trước',
                    data: [],
                    fill: false,
                    borderColor: '#f0f0f0',
                    borderDash: [5, 5],
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'right', // `axis` is determined by the position as `'y'`
                    },
                    x: [{
                        type: 'time',
                        distribution: 'series'
                    }]
                },
                fill: false,
                radius: 0,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            footer: footerTooltipChart,
                            title: titleTooltipChart,
                            label: function(context) {
                                var label = context.parsed.y + ' người';
                                return label;
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            },
        });

        var ctxSession = document.getElementById('sessionAnalyticsChart').getContext('2d');
        var sessionAnalyticsChart = new Chart(ctxSession, {
            type: 'line',
            data: {
                labels: labelShow,
                datasets: [{
                    label: 'Hiện tại',
                    data: [],
                    fill: false,
                    borderColor: '#1a73e8',
                    tension: 0.1
                }, {
                    label: 'Trước',
                    data: [],
                    fill: false,
                    borderColor: '#f0f0f0',
                    borderDash: [5, 5],
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'right', // `axis` is determined by the position as `'y'`
                    }
                },
                fill: false,
                radius: 0,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            footer: footerTooltipChart,
                            title: titleTooltipChart,
                            label: function(context) {
                                var label = context.parsed.y + ' phiên';
                                return label;
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            },
        });

        var ctxBounceRate = document.getElementById('bounceRateAnalyticsChart').getContext('2d');
        var bounceRateAnalyticsChart = new Chart(ctxBounceRate, {
            type: 'line',
            data: {
                labels: labelShow,
                datasets: [{
                    label: 'Hiện tại',
                    data: [],
                    fill: false,
                    borderColor: '#1a73e8',
                    tension: 0.1
                }, {
                    label: 'Trước',
                    data: [],
                    fill: false,
                    borderColor: '#f0f0f0',
                    borderDash: [5, 5],
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'right', // `axis` is determined by the position as `'y'`
                        ticks: {
                            callback: function(value, index, values) {
                                return value + '%';
                            }
                        }
                    }
                },
                fill: false,
                radius: 0,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            footer: footerTooltipChart,
                            label: function(context) {
                                var label = context.parsed.y + '%';
                                return label;
                            },
                            title: titleTooltipChart
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            },
        });

        var ctxSessionDuration = document.getElementById('sessionDurationAnalyticsChart').getContext('2d');
        var sessionDurationAnalyticsChart = new Chart(ctxSessionDuration, {
            type: 'line',
            data: {
                labels: labelShow,
                datasets: [{
                    label: 'Hiện tại',
                    data: [],
                    fill: false,
                    borderColor: '#1a73e8',
                    tension: 0.1
                }, {
                    label: 'Trước',
                    data: [],
                    fill: false,
                    borderColor: '#f0f0f0',
                    borderDash: [5, 5],
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'right', // `axis` is determined by the position as `'y'`
                        ticks: {
                            callback: function(value, index, values) {
                                return secondsToHms(value);
                            }
                        }
                    }
                },
                fill: false,
                radius: 0,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            footer: footerTooltipChart,
                            label: function(context) {
                                var label = secondsToHms(context.parsed.y);
                                return label;
                            },
                            title: titleTooltipChart
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            },
        });

        // Tính tỷ lệ thoát 100/tổng(sessions)*tổng(bounces)
        // Tính thời lượng phiên tổng(sessionDuration)/tổng(sessions)
        async function getDataSummary(start, end) {
            $(".loading").show();
            let typeData = 'date';
            let daysBetween = enumerateDaysBetweenDates(new Date(start), new Date(end));
            let offset = 0
            if (start == end) {
                typeData = 'dateHour'
                offset = 1
            } else {
                if (new Date(end).toDateString() == moment().subtract(1, 'days').toDate().toDateString()) {
                    if (daysBetween.length == 88 || daysBetween.length == 178) {
                        typeData = 'yearWeek'
                    } else if (daysBetween.length >= 363) {
                        typeData = 'month'
                    }
                }
                offset = daysBetween.length + 2
            }

            $.get("{{ route('admin.api.analytics.get_summary') }}", {
                    start: start,
                    end: end,
                    type: typeData
                })
                .done(function(result) {
                    $('#numberUserAnalytics').text(result.total.totalsForAllResults['ga:users']);
                    $('#numberSessionAnalytics').text(result.total.totalsForAllResults['ga:sessions']);
                    $('#percentBounceRateAnalytics').text(parseFloat(result.total.totalsForAllResults[
                        'ga:bounceRate']).toFixed(1) + '%');
                    $('#timeSessionDurationAnalytics').text(secondsToHms(result.total.totalsForAllResults[
                        'ga:avgSessionDuration']));
                    let startAgo = new Date(start)
                    startAgo.setDate(startAgo.getDate() - offset);
                    let endAgo = new Date(end)
                    endAgo.setDate(endAgo.getDate() - offset);

                    labels = result.labels

                    if (typeData == 'dateHour') {
                        labelShow = []
                        labels.forEach(element => {
                            labelShow.push(element.split(' ')[0])
                        });
                    } else {
                        labelShow = labels
                    }

                    $.get("{{ route('admin.api.analytics.get_summary') }}", {
                            start: moment(startAgo).format("YYYY-MM-DD"),
                            end: moment(endAgo).format("YYYY-MM-DD"),
                            type: typeData
                        })
                        .done(function(resultAgo) {
                            labelsAgo = resultAgo.labels

                            userAnalyticsChart.data.datasets[0].data = result.dataUser
                            userAnalyticsChart.data.datasets[1].data = resultAgo.dataUser
                            userAnalyticsChart.data.labels = labelShow
                            userAnalyticsChart.update()

                            sessionAnalyticsChart.data.datasets[0].data = result.dataSession
                            sessionAnalyticsChart.data.datasets[1].data = resultAgo.dataSession
                            sessionAnalyticsChart.data.labels = labelShow
                            sessionAnalyticsChart.update()

                            bounceRateAnalyticsChart.data.datasets[0].data = result.dataBounceRate
                            bounceRateAnalyticsChart.data.datasets[1].data = resultAgo.dataBounceRate
                            bounceRateAnalyticsChart.data.labels = labelShow
                            bounceRateAnalyticsChart.update()

                            sessionDurationAnalyticsChart.data.datasets[0].data = result.dataSessionDuration
                            sessionDurationAnalyticsChart.data.datasets[1].data = resultAgo.dataSessionDuration
                            sessionDurationAnalyticsChart.data.labels = labelShow
                            sessionDurationAnalyticsChart.update()
                        })
                        .fail(function() {})
                        .always(function() {});
                })
                .fail(function() {})
                .always(function() {
                    $(".loading").hide();
                });
        }

        getDataSummary(moment().subtract(1, 'days').format('YYYY-MM-DD'), moment().subtract(1, 'days').format(
        'YYYY-MM-DD'));
        $('#text-daterange').html(moment().subtract(1, 'days').format('DD/MM/YYYY') + ' - ' + moment().subtract(1, 'days')
            .format('DD/MM/YYYY'));
    </script>
    <script>
        function getMostVisitedPages(start, end, limit) {
            $(".loading-visited-page").show();
            $("#table-visited-pages").html('');
            $.get("{{ route('admin.api.analytics.get_most_visited_pages') }}", {
                start: start,
                end: end,
                limit: limit
            }).done(function(result) {
                $.each(result, function(i, data){
                    $("#table-visited-pages").append('<tr><td class="link-visited-page"><a target="_blank" href="' + data.url + '">' + data.url + '</a></td><td>' + data.pageViews + '</td><td>' + data.pageTitle + '</td></tr>');
                })
            }).always(function() {
                $(".loading-visited-page").hide();
            })
        }
        getMostVisitedPages(moment().subtract(7, 'days').format('YYYY-MM-DD'), moment().subtract(1, 'days').format('YYYY-MM-DD'), 20)
        $('#text-daterange-visited-page').html(moment().subtract(7, 'days').format('DD/MM/YYYY') + ' - ' + moment().subtract(1, 'days')
            .format('DD/MM/YYYY'));
        //Date range as a button
        $('#daterange-visited-page').daterangepicker({
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 ngày trước': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
                    '28 ngày trước': [moment().subtract(28, 'days'), moment().subtract(1, 'days')],
                    '90 ngày trước': [moment().subtract(90, 'days'), moment().subtract(1, 'days')],
                    '180 ngày trước': [moment().subtract(180, 'days'), moment().subtract(1, 'days')]
                },
                startDate: moment().subtract(7, 'days'),
                endDate: moment().subtract(1, 'days')
            },
            function(start, end) {
                if (start.format('DD/MM/YYYY') == end.format('DD/MM/YYYY')) {
                    $('#text-daterange-visited-page').html(start.format('DD/MM/YYYY'))
                } else {
                    $('#text-daterange-visited-page').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                }
                getMostVisitedPages(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'), 20)
            }
        )
    </script>
    <script>
        var ctxDevice = document.getElementById('deviceChart').getContext('2d');
        var deviceChart = new Chart(ctxDevice, {
            type: 'doughnut',
            data: {
                labels: [
                    'Máy tính để bàn',
                    'Thiết bị di động',
                    'Máy tính bảng'
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: [1, 1, 1],
                    backgroundColor: [
                    'rgb(51, 103, 214)',
                    'rgb(66, 133, 244)',
                    'rgb(123, 170, 247)'
                    ],
                    hoverOffset: 4
                }],
                options: {}
            }
        });
        function getDevices(start, end) {
            var labelDevice = [];
            var dataDevice = [];
            $("#table-device-mobile").html('');
            $(".loading-device").show();
            $.get("{{ route('admin.api.analytics.get_devices') }}", {
                start: start,
                end: end
            }).done(function(result) {
                $.each(result.deviceMobile, function(i, data){
                    $("#table-device-mobile").append('<tr><td>'+ (i+1) +'</td><td>'+ data.name +'</td><td>'+ data.users +'</td><td>'+ data.sessions +'</td><td>'+ secondsToHms(data.avgSessionDuration) +'</td><td>'+ parseFloat(data.bounceRate).toFixed(1) +'%</td></tr>');
                })
                $.each(result.deviceCategory, function(i, data){
                    if (data[0] == 'desktop') {
                        labelDevice.push('Máy tính để bàn');
                    } else if (data[0] == 'mobile') {
                        labelDevice.push('Thiết bị di động');
                    } else if (data[0] == 'tablet') {
                        labelDevice.push('Máy tính bảng');
                    } else {
                        labelDevice.push(data[0]);
                    }
                    dataDevice.push(data[1]);
                })
                deviceChart.data.datasets[0].data = dataDevice
                deviceChart.data.labels = labelDevice
                deviceChart.update()
            }).always(function() {
                $(".loading-device").hide();
            })
        }
        getDevices(moment().subtract(7, 'days').format('YYYY-MM-DD'), moment().subtract(1, 'days').format('YYYY-MM-DD'));
        $('#text-daterange-device').html(moment().subtract(7, 'days').format('DD/MM/YYYY') + ' - ' + moment().subtract(1, 'days')
            .format('DD/MM/YYYY'));
        //Date range as a button
        $('#daterange-device').daterangepicker({
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 ngày trước': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
                    '28 ngày trước': [moment().subtract(28, 'days'), moment().subtract(1, 'days')],
                    '90 ngày trước': [moment().subtract(90, 'days'), moment().subtract(1, 'days')],
                    '180 ngày trước': [moment().subtract(180, 'days'), moment().subtract(1, 'days')]
                },
                startDate: moment().subtract(7, 'days'),
                endDate: moment().subtract(1, 'days')
            },
            function(start, end) {
                if (start.format('DD/MM/YYYY') == end.format('DD/MM/YYYY')) {
                    $('#text-daterange-device').html(start.format('DD/MM/YYYY'))
                } else {
                    $('#text-daterange-device').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                }
                getDevices(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'), 20)
            }
        )
    </script>
@endsection
