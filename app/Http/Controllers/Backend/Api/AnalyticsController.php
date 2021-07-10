<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Analytics;
use Spatie\Analytics\Period;
use Illuminate\Http\Response;
use DateTime;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // Hàm lấy lượng user và visitor theo thời gian hiện tại trở về trước
    public function getUsersAndVisitorsAgo(Request $request)
    {
        // Type: dateHour, date, week, month, year
        $type = $request->input('type');
        $daysAgo = $request->input('daysAgo');
        $analyticsData = Analytics::performQuery(
            Period::days($daysAgo),
            'ga:sessions',
            [
                'metrics' => 'ga:users, ga:visitors',
                'dimensions' => 'ga:' . $type,
                'includes-empty-rows' => true
            ]
        );
        return response()->json($analyticsData, Response::HTTP_OK);
        // Format return: Mỗi phần tử trong row 1 một giá trị tương ứng trên biểu đồ
        // totalsForAllResults là tổng giá trị
        // "rows": [
        //     [
        //         "20210630",
        //         "0",
        //         "0"
        //     ],
        // ]
        // "totalsForAllResults": {
        //     "ga:users": "5",
        //     "ga:visitors": "5"
        // },
    }

    // Hàm lấy dữ liệu tổng quan
    public function getSummary(Request $request)
    {
        // Type: dateHour, date, week, month, year
        $type = $request->input('type');
        // Format time: 2005-08-15 15:52:01
        if (!$type) {
            $type = 'date';
        }
        $start = $request->input('start');
        $end = $request->input('end');
        $timeQuery = Period::create(DateTime::createFromFormat('Y-m-d', $start), DateTime::createFromFormat('Y-m-d', $end));
        $analyticsData = Analytics::performQuery(
            $timeQuery,
            'ga:sessions',
            [
                'metrics' => 'ga:users, ga:sessions, ga:sessionDuration, ga:avgSessionDuration, ga:bounces, ga:bounceRate',
                'dimensions' => 'ga:' . $type
            ]
        );
        $totalAnalyticsData = Analytics::performQuery(
            $timeQuery,
            'ga:sessions',
            [
                'metrics' => 'ga:users, ga:sessions, ga:sessionDuration, ga:avgSessionDuration, ga:bounces, ga:bounceRate'
            ]
        );

        $labels = [];
        $dataUser = [];
        $dataSession = [];
        $dataBounceRate = [];
        $dataSessionDuration = [];
        if ($type == 'dateHour') {
            for ($i = 0; $i < 24; $i++) {
                $date = explode('-', $analyticsData->query->startDate)[2];
                $month = explode('-', $analyticsData->query->startDate)[1];
                array_push($labels, strval($i) . 'h ' . $date . '/' . $month);
                array_push($dataUser, 0);
                array_push($dataSession, 0);
                array_push($dataBounceRate, 0);
                array_push($dataSessionDuration, 0);

                if ($analyticsData->rows) {
                    foreach ($analyticsData->rows as $item) {
                        if (intval(substr($item[0], -2)) == $i) {
                            array_pop($dataUser);
                            array_push($dataUser, intval($item[1]));
                            array_pop($dataSession);
                            array_push($dataSession, intval($item[2]));
                            array_pop($dataBounceRate);
                            array_push($dataBounceRate, intval($item[6]));
                            array_pop($dataSessionDuration);
                            array_push($dataSessionDuration, intval($item[4]));
                            break;
                        }
                    }
                }
            }
        } else {
            foreach ($analyticsData->rows as $index => $item) {
                array_push($dataUser, intval($item[1]));
                array_push($dataSession, intval($item[2]));
                array_push($dataBounceRate, intval($item[6]));
                array_push($dataSessionDuration, intval($item[4]));
                if ($type == 'date') {
                    $date = substr($item[0], 6, 2);
                    $month = substr($item[0], 4, 2);
                    array_push($labels, $date . '/' . $month);
                } else if ($type == 'yearWeek') {
                    $dto = new DateTime();
                    error_log(substr($item[0], 0, 4));
                    $weekStart = $dto->setISODate(substr($item[0], 0, 4), substr($item[0], 4, 2) - 1)->modify('-1 days')->format('Y-m-d');
                    $weekEnd = $dto->modify('+6 days')->format('Y-m-d');
                    if ($index == 0) {
                        $weekStart = $analyticsData->query->startDate;
                    }
                    if (($index + 1) == sizeof($analyticsData)) {
                        $weekEnd = $analyticsData->query->endDate;
                    }
                    array_push($labels, explode('-', $weekStart)[2] . '-' . explode('-', $weekEnd)[2] . '/' . explode('-', $weekEnd)[1] . '/' . explode('-', $weekEnd)[0]);
                } else {
                    array_push($labels, $item[0]);
                }
            }
        }

        $result = [
            'total' => $totalAnalyticsData,
            'detail' => $analyticsData,
            'dataUser' => $dataUser,
            'dataSession' => $dataSession,
            'dataBounceRate' => $dataBounceRate,
            'dataSessionDuration' => $dataSessionDuration,
            'labels' => $labels
        ];
        return response()->json($result, Response::HTTP_OK);
    }

    // Lấy ra danh sách các trang được truy cập nhiều nhất
    public function getMostVisitedPages(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $maxResults = $request->input('limit');
        $timeQuery = Period::create(DateTime::createFromFormat('Y-m-d', $start), DateTime::createFromFormat('Y-m-d', $end));
        $analyticsData = Analytics::fetchMostVisitedPages($timeQuery, $maxResults);
        return response()->json($analyticsData, Response::HTTP_OK);
        // Format Result: Kết quả trả ra danh sách page được vào nhiều nhất
        // [
        //     {
        //         "url": "/",
        //         "pageTitle": "Học ngoại ngữ Online Tomato | Website học Online ngoại ngữ tốt nhất",
        //         "pageViews": 26
        //     },
        // ]
    }

    // Hàm lấy thông tin phiên làm việc từ hiện tại trở về trước
    public function getSessionsAgo(Request $request)
    {
        // Type: dateHour, date, week, month, year
        $type = $request->input('type');
        $daysAgo = $request->input('daysAgo');
        $analyticsData = Analytics::performQuery(
            Period::days($daysAgo),
            'ga:sessions',
            [
                'metrics' => 'ga:sessions, ga:sessionDuration, ga:avgSessionDuration',
                'dimensions' => 'ga:' . $type
            ]
        );
        return response()->json($analyticsData, Response::HTTP_OK);
        // Format return: Mỗi phần tử trong row 1 một giá trị tương ứng trên biểu đồ
        // totalsForAllResults là tổng giá trị
        // "rows": [
        //     [
        //          "2021070500", time
        //          "1",          session
        //          "0.0",        sessionDuration
        //          "0.0"         avgSessionDuration
        //      ],
        // ]
        // "totalsForAllResults": {
        //     "ga:sessions": "8",
        //     "ga:sessionDuration": "2535.0",
        //     "ga:avgSessionDuration": "316.875"
        // },
        // "columnHeaders": [
        //     {
        //         "columnType": "DIMENSION",
        //         "dataType": "STRING",
        //         "name": "ga:dateHour"
        //     },
        //     {
        //         "columnType": "METRIC",
        //         "dataType": "INTEGER",
        //         "name": "ga:sessions"
        //     },
        //     {
        //         "columnType": "METRIC",
        //         "dataType": "TIME",
        //         "name": "ga:sessionDuration"
        //     },
        //     {
        //         "columnType": "METRIC",
        //         "dataType": "TIME",
        //         "name": "ga:avgSessionDuration"
        //     }
        // ]
    }

    public function getStartAndEndDateByWeek($week, $year)
    {
        $dto = new DateTime();
        $ret['week_start'] = $dto->setISODate($year, $week)->format('Y-m-d');
        $ret['week_end'] = $dto->modify('+6 days')->format('Y-m-d');
        return $ret;
    }

    public function getDevices(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $timeQuery = Period::create(DateTime::createFromFormat('Y-m-d', $start), DateTime::createFromFormat('Y-m-d', $end));
        $analyticsData = Analytics::performQuery(
            $timeQuery,
            'ga:sessions',
            [
                'metrics' => 'ga:users, ga:sessions, ga:avgSessionDuration, ga:bounceRate',
                'dimensions' => 'ga:mobileDeviceBranding, ga:mobileDeviceModel, ga:mobileDeviceMarketingName',
                'sort' => '-ga:users'
            ]
        );
        $totalData = Analytics::performQuery(
            $timeQuery,
            'ga:sessions',
            [
                'metrics' => 'ga:users',
                'dimensions' => 'ga:deviceCategory'
            ]
        );

        $deviceMobile = [];
        $deviceCategory = [];
        if ($analyticsData->rows) {
            foreach ($analyticsData->rows as $index => $item) {
                $deviceName = '';
                if ($item[0] != '(not set)') {
                    if ($item[2] == '(not set)') {
                        $deviceName = $item[0].' '.$item[1];
                    } else {
                        $deviceName = $item[0].' '.$item[2];
                    }
                } else {
                    $deviceName = 'Chưa xác định';
                }
                $deviceInfo = [
                    'name' => $deviceName,
                    'users' => $item[3],
                    'sessions' => $item[4],
                    'avgSessionDuration' => $item[5],
                    'bounceRate' => $item[6],
                ];
                array_push($deviceMobile, $deviceInfo);
            }
        }
        if ($totalData->rows) {
            $deviceCategory = $totalData->rows;
        }

        $result = [
            "deviceMobile" => $deviceMobile,
            "deviceCategory" => $deviceCategory
        ];

        return response()->json($result, Response::HTTP_OK);

    }
}
