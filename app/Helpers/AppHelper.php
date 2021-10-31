<?php
namespace App\Helpers;

class AppHelper
{
    public function nicetime($date)
    {
        if(empty($date)) {
            return "Không có ngày tháng được cung cấp";
        }

        $periods         = array("giây", "phút", "giờ", "ngày", "tuần", "tháng", "năm", "thế kỷ");
        $lengths         = array("60","60","24","7","4.35","12","10");

        $now             = time();
        $unix_date         = strtotime($date);

        // check validity of date
        if(empty($unix_date)) {
            return "Không đúng format ngày";
        }

        // is it future date or past date
        if($now > $unix_date) {
            $difference     = $now - $unix_date;
            $tense         = "trước";

        } else {
            $difference     = $unix_date - $now;
            $tense         = "đến nay";
        }

        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if($difference != 1) {
            $periods[$j].= "";
        }

        return "$difference $periods[$j] {$tense}";
    }

    public static function instance()
    {
        return new AppHelper();
    }
}
