<?php

namespace App\Http\Controllers\Backend;

use App\Book;
use App\Category;
use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Invoice;
use App\InvoiceItem;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use Analytics;
use App\Survey;
use Spatie\Analytics\Period;

class HomeController extends Controller
{
    public function index()
    {
        $surveys = Survey::where('is_received', false)->get();
        // Lấy thông tin tất cả các hoá đơn
        $countInvoices = 0;
        $totalInvoiceAll = 0;
        $listInvoices = Invoice::where('status', 'complete')->get();
        $countInvoices = count($listInvoices);
        foreach ($listInvoices as $invoice) {
            foreach ($invoice->items as $item) {
                $totalInvoiceAll += $item->price;
            }
        }

        $listCourses = Course::get();
        $countCourses = count($listCourses);
        $listUsers = User::get();
        $countUsers = count($listUsers);

        // Doanh thu ngày hôm nay
        $totalInvoicesToday = 0;
        $listCategories = (object) array();
        $invoiceItemsToday = InvoiceItem::groupBy('type', 'object_id')
            ->selectRaw('sum(price) as sum_price, type, object_id')
            ->where('created_at', '>=', [Carbon::today()->format('Y-m-d')])
            ->get();
        foreach ($invoiceItemsToday as $item) {
            $totalInvoicesToday += $item->sum_price;
            if($item->type == 'course') {
                $course = Course::where('id', $item->object_id)->first();
                $categoryTitle = "Khác";
                if ($course->category) {
                    $categoryTitle = $course->category->title;
                }
                if (property_exists($listCategories, $categoryTitle)) {
                    $listCategories->$categoryTitle += $item->sum_price;
                } else {
                    $listCategories->$categoryTitle = $item->sum_price;
                }
            } elseif ($item->type == 'book') {
                $categoryTitle = "Sách";
                if (property_exists($listCategories, $categoryTitle)) {
                    $listCategories->$categoryTitle += $item->sum_price;
                } else {
                    $listCategories->$categoryTitle = $item->sum_price;
                }
            }
        }

        // Doanh thu ngày hôm qua
        $totalInvoicesYesterday = 0;
        $invoiceYesterday = Invoice::where('status', 'complete')
            ->whereBetween('created_at', [Carbon::today()->subDays(1)->format('Y-m-d'), Carbon::today()->format('Y-m-d')])
            ->get();
        foreach ($invoiceYesterday as $invoice) {
            foreach ($invoice->items as $item) {
                $totalInvoicesYesterday += $item->price;
            }
        }

        // Lấy hoá đơn trong vòng 7 ngày trước
        $arrayDateWeekAgo = array();
        $dataInvoiceOneWeekAgo = array();
        $totalInvoicesOneWeekAgo = 0;
        for ($i=7; $i>=1; $i--) {
            $list = Invoice::where('status', 'complete')
                ->whereBetween('created_at', [Carbon::today()->subDays($i)->format('Y-m-d'), Carbon::today()->subDays($i-1)->format('Y-m-d')])
                ->get();
            if ($list) {
                $totalInvoice = 0;
                foreach ($list as $invoice) {
                    foreach ($invoice->items as $item) {
                        $totalInvoice += $item->price;
                        $totalInvoicesOneWeekAgo += $item->price;
                    }
                }
                array_push($dataInvoiceOneWeekAgo, $totalInvoice);
            } else {
                array_push($dataInvoiceOneWeekAgo, 0);
            }
            array_push($arrayDateWeekAgo, Carbon::today()->subDays($i)->format('d/m'));
        }

        // Lấy hoá đơn trong vòng 14 ngày trước
        $dataInvoiceTwoWeekAgo = array();
        $totalInvoicesTwoWeekAgo = 0;
        for ($i=14; $i>=8; $i--) {
            $list = Invoice::where('status', 'complete')
                ->whereBetween('created_at', [Carbon::today()->subDays($i)->format('Y-m-d'), Carbon::today()->subDays($i-1)->format('Y-m-d')])
                ->get();
            if ($list) {
                $totalInvoice = 0;
                foreach ($list as $invoice) {
                    foreach ($invoice->items as $item) {
                        $totalInvoice += $item->price;
                        $totalInvoicesTwoWeekAgo += $item->price;
                    }
                }
                array_push($dataInvoiceTwoWeekAgo, $totalInvoice);
            } else {
                array_push($dataInvoiceTwoWeekAgo, 0);
            }
        }

        $dataTopSellerMonth = array();
        $topSellerMonth = InvoiceItem::groupBy('type', 'object_id')
            ->selectRaw('sum(amount) as sum_amount, type, object_id')
            ->where('created_at', '>=', Carbon::today()->subDays(30))
            ->orderBy('sum_amount', 'desc')
            ->limit(10)
            ->get();

        foreach ($topSellerMonth as $item) {
            if($item->type == 'course') {
                $course = Course::where('id', $item->object_id)->first();
                $objCourse = (object) array(
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'type' => 'course',
                    'amount' => $item->sum_amount,
                );
                array_push($dataTopSellerMonth, $objCourse);
            } elseif ($item->type == 'book') {
                $book = Book::where('id', $item->object_id)->first();
                $objBook = (object) array(
                    'id' => $book->id,
                    'title' => $book->title,
                    'slug' => $book->slug,
                    'type' => 'book',
                    'amount' => $item->sum_amount,
                );
                array_push($dataTopSellerMonth, $objBook);
            }
        }

        return view('backend.home.index', [
            'count_invoices' => $countInvoices,
            'total_invoice' => $totalInvoiceAll,
            'count_courses' => $countCourses,
            'count_users' => $countUsers,
            'array_date_week_ago' => json_encode($arrayDateWeekAgo),
            'data_invoices_one_week_ago' => json_encode($dataInvoiceOneWeekAgo),
            'total_invoices_one_week_ago' => $totalInvoicesOneWeekAgo,
            'data_invoices_two_week_ago' => json_encode($dataInvoiceTwoWeekAgo),
            'total_invoices_two_week_ago' => $totalInvoicesTwoWeekAgo,
            'top_seller_month' => $dataTopSellerMonth,
            'total_invoices_today' => $totalInvoicesToday,
            'total_invoices_yesterday' => $totalInvoicesYesterday,
            'list_categories' => $listCategories,
            'surveys' => $surveys
        ]);
    }

    public function getInvoice(Request $request)
    {
        $start = Carbon::createFromFormat('Y-m-d', $request->input('start'));
        $end = Carbon::createFromFormat('Y-m-d', $request->input('end'));

        $data = (object) array(
            "date" => array(),
            "price" => array(),
            "total" => 0
        );
        $period = CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            $total = 0;
            $invoiceItems = InvoiceItem::whereBetween('created_at', [$date->format('Y-m-d'), Carbon::createFromFormat('Y-m-d', $date->format('Y-m-d'))->addDay()->format('Y-m-d')])
            ->get();
            foreach ($invoiceItems as $item) {
                $total += $item->price;
            }
            array_push($data->date, $date->format('d/m'));
            array_push($data->price, $total);
            $data->total += $total;
        }

        return json_encode($data);
    }
}
