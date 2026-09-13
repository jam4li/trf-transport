<?php

namespace Trust\INC\DateUtils;

use DateTime;

include TRUST_INC . 'DateUtils/class-jdate.php';

class DateGenerator
{
    /**
     * Generates Jalali date for warranty start
     * 
     * @return string Jalai date with `Y-m-d H:i:s` format
     */
    public static function generate_j_start_date(): string
    {
        $date = new DateTime('now', new \DateTimeZone(wp_timezone_string()));
        return jDate(date('Y-m-d')) . " {$date->format('H:i:s')}";
    }

    /**
     * Generates Jalali date for warranty expiration
     * 
     * @param int $period number of days/months warranty is valid
     * @param string $period_unit `m` for month, `d` for day
     * 
     * @return string Jalai date with `Y-m-d H:i:s` format
     */
    public static function generate_j_end_date($period, $period_unit): string
    {
        if (intval($period) == 0) return '0000-00-00';

        $date = new DateTime('now', new \DateTimeZone(wp_timezone_string()));
        $jdate = new DateTime(jDate($date->format('Y-m-d')) . " {$date->format('H:i:s')}");

        if ($period_unit == 'm') $jdate->modify("{$period} month");
        else $jdate->modify("{$period} day");

        return $jdate->format('Y-m-d H:i:s');
    }

    /**
     * Generates Gregorian date for warranty start
     * 
     * @return string Gregorian date with `Y-m-d H:i:s` format
     */
    public static function generate_g_start_date(): string
    {
        $date = new DateTime('now', new \DateTimeZone(wp_timezone_string()));
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Generates Gregorian date for warranty expiration
     * 
     * @param int $period number of days/months warranty is valid
     * @param string $period_unit `m` for month, `d` for day
     * 
     * @return string Gregorian date with `Y-m-d H:i:s` format
     */
    public static function generate_g_end_date($period, $period_unit): string
    {
        if (intval($period) == 0) return '0000-00-00';

        $date = new DateTime('now');

        if ($period_unit == 'm') $date->modify("{$period} month");
        else $date->modify("{$period} day");
        
        return $date->format('Y-m-d H:i:s');
    }
}
