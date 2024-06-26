<?php

namespace common\components;



class MyHelpers
{
    // Assuming this is in a helper class or a component

    public static function timeAgo($timestamp)
    {
        $current_time = time();
        $time_difference = $current_time - $timestamp;
        $seconds = $time_difference;

        $minutes      = round($seconds / 60);           // value 60 is seconds
        $hours        = round($seconds / 3600);         // value 3600 is 60 minutes * 60 sec
        $days         = round($seconds / 86400);        // value 86400 is 24 hours * 60 minutes * 60 sec
        $weeks        = round($seconds / 604800);       // value 604800 is 7 days * 24 hours * 60 minutes * 60 sec
        $months       = round($seconds / 2629440);      // value 2629440 is ((365+365+365+365+366)/5/12) days * 24 hours * 60 minutes * 60 sec
        $years        = round($seconds / 31553280);     // value 31553280 is ((365+365+365+365+366)/5) days * 24 hours * 60 minutes * 60 sec

        if ($seconds <= 60) {
            return "Just now";
        } elseif ($minutes <= 60) {
            return "$minutes minute" . ($minutes > 1 ? 's' : '') . " ago";
        } elseif ($hours <= 24) {
            return "$hours hour" . ($hours > 1 ? 's' : '') . " ago";
        } elseif ($days <= 7) {
            return "$days day" . ($days > 1 ? 's' : '') . " ago";
        } elseif ($weeks <= 4.3) {  // 4.3 == 30/7
            return "$weeks week" . ($weeks > 1 ? 's' : '') . " ago";
        } elseif ($months <= 12) {
            return "$months month" . ($months > 1 ? 's' : '') . " ago";
        } else {
            return "$years year" . ($years > 1 ? 's' : '') . " ago";
        }
    }

    public static function makeYearRange($before = 5, $after = 5)
    {
        $range = range(date('Y') - $before, date('Y') + $after);

        $output = [];

        foreach ($range as $idx => $value) {
            $output[$value] = $value;
        }

        return $output;
    }

    public static function makeMonths()
    {
        return  [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December',
        ];
    }
}
