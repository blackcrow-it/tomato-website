@extends('backend.master')

@section('title')
    Homepage
@endsection

@section('content-header')
    <h1 class="text-center">Have a nice day!</h1>
    <br />
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-money-check-alt"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Doanh thu</span>
                    <span class="info-box-number">
                        {{ currency($total_invoice, '0 ₫') }}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Đơn hàng</span>
                    <span class="info-box-number">
                        {{ $count_invoices }}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-graduation-cap"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Khoá học</span>
                    <span class="info-box-number">
                        {{ $count_courses }}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Người dùng</span>
                    <span class="info-box-number">
                        {{ $count_users }}
                        <small>người</small>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>

    <div class="row">
        <section class="col-lg-7">
            <div class="card">
                <div class="card-header border-0">
                    <div class="card-header">
                        <h3 class="card-title">Doanh thu tuần</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">
                                {{ currency($total_invoices_one_week_ago, '0 ₫') }}
                            </span>
                            <span><small>(Tuần trước {{ currency($total_invoices_two_week_ago, '0 ₫') }})</small></span>
                        </p>
                        @if ($total_invoices_one_week_ago != 0 && $total_invoices_two_week_ago != 0)
                        <p class="ml-auto d-flex flex-column text-right">
                            @if ($total_invoices_one_week_ago > $total_invoices_two_week_ago)
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i>
                                    {{ round((($total_invoices_one_week_ago - $total_invoices_two_week_ago) / $total_invoices_two_week_ago) * 100) }}
                                    %
                                </span>
                            @else
                                <span class="text-danger">
                                    <i class="fas fa-arrow-down"></i>
                                    {{ round(abs((($total_invoices_one_week_ago - $total_invoices_two_week_ago) / $total_invoices_two_week_ago) * 100)) }}
                                    %
                                </span>
                            @endif
                            <span class="text-muted">so với tuần trước</span>
                        </p>
                        @endif
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="sale-chart" height="400" width="1064" class="chartjs-render-monitor"
                            style="display: block; height: 200px; width: 532px;"></canvas>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <section class="col-lg-5">
            <div class="card">
                <div class="card-header border-0">
                    <div class="card-header">
                        <h3 class="card-title">Top 10 sản phẩm của tháng</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Tên sản phẩm</th>
                                <th>Loại</th>
                                <th>SL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($top_seller_month as $item)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>
                                    @if($item->type == 'course')
                                    <a href="{{ route('course', [ 'slug' => $item->slug, 'id' => $item->id ]) }}" data-toggle="tooltip" title="{{ $item->title }}">
                                        {{ truncate($item->title) }}
                                    </a>
                                    @elseif($item->type == 'book')
                                    <a href="{{ route('book', [ 'slug' => $item->slug, 'id' => $item->id ]) }}" data-toggle="tooltip" title="{{ $item->title }}">
                                        {{ truncate($item->title) }}
                                    </a>
                                    @endif
                                </td>
                                <td>
                                    @if($item->type == 'course')
                                    Khoá học
                                    @elseif($item->type == 'book')
                                    Sách
                                    @endif
                                </td>
                                <td>{{ $item->amount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            'use strict'

            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            }

            var mode = 'index'
            var intersect = true

            var $saleChart = $('#sale-chart')
            // eslint-disable-next-line no-unused-vars
            var saleChart = new Chart($saleChart, {
                data: {
                    labels: {!! $array_date_week_ago !!},
                    datasets: [{
                            type: 'line',
                            label: 'Tuần này',
                            data: {!! $data_invoices_one_week_ago !!},
                            backgroundColor: '#007bff',
                            borderColor: '#007bff',
                            pointBorderColor: '#007bff',
                            pointBackgroundColor: '#007bff',
                            fill: false,
                            pointHoverBackgroundColor: '#007bff',
                            pointHoverBorderColor: '#007bff'
                        },
                        {
                            type: 'line',
                            label: 'Tuần trước',
                            data: {!! $data_invoices_two_week_ago !!},
                            backgroundColor: '#ced4da',
                            borderColor: '#ced4da',
                            pointBorderColor: '#ced4da',
                            pointBackgroundColor: '#ced4da',
                            fill: false,
                            pointHoverBackgroundColor: '#ced4da',
                            pointHoverBorderColor: '#ced4da'
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                suggestedMax: 200
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })
        })
    </script>
@endsection
