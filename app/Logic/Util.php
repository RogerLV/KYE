<?php

namespace App\Logic;

class Util
{
    public static function getElapsedTimeString($timeStr)
    {
        $start_date = new \DateTime($timeStr);
        $end_date = new \DateTime('now');
        $dd = date_diff($start_date,$end_date);

        if ($dd->y > 1) return "$dd->y years ago";
        else if($dd->y == 1) return "1 year ago";

        if ($dd->m > 1) return "$dd->m months ago";
        else if($dd->m == 1) return "1 month ago";

        if ($dd->d > 1) return "$dd->d days ago";
        else if($dd->d == 1) return "1 day ago";

        if ($dd->h > 1) return "$dd->h hours ago";
        else if($dd->h == 1) return "1 hour ago";

        if ($dd->i > 1) return "$dd->i minutes ago";
        else if($dd->i == 1) return "1 minute ago";

        if ($dd->s > 1) return "$dd->s seconds ago";
        else if($dd->s == 1) return "1 second ago";
    }
}