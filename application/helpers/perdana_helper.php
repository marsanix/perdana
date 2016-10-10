<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Time zone (Note: Requires PHP 5 >= 5.1.0)
 * Read http://www.php.net/date_default_timezone_set for details
 * and http://www.php.net/timezones for supported time zones
*/
if(function_exists("date_default_timezone_set")) {
    date_default_timezone_set("Asia/Jakarta");
}

/**
 * Numeric and monetary formatting options
 * Set EW_USE_DEFAULT_LOCALE to TRUE to override localeconv and use the
 * following constants for ew_FormatCurrency/Number/Percent functions
 * Also read http://www.php.net/localeconv for description of the constants
*/
@define("USE_DEFAULT_LOCALE", FALSE, TRUE);
@define("DEFAULT_DECIMAL_POINT", ".", TRUE);
@define("DEFAULT_THOUSANDS_SEP", ",", TRUE);
@define("DEFAULT_CURRENCY_SYMBOL", "Rp. ", TRUE);
@define("DEFAULT_MON_DECIMAL_POINT", ".", TRUE);
@define("DEFAULT_MON_THOUSANDS_SEP", ",", TRUE);
@define("DEFAULT_POSITIVE_SIGN", "", TRUE);
@define("DEFAULT_NEGATIVE_SIGN", "-", TRUE);
@define("DEFAULT_FRAC_DIGITS", 2, TRUE);
@define("DEFAULT_P_CS_PRECEDES", TRUE, TRUE);
@define("DEFAULT_P_SEP_BY_SPACE", FALSE, TRUE);
@define("DEFAULT_N_CS_PRECEDES", TRUE, TRUE);
@define("DEFAULT_N_SEP_BY_SPACE", FALSE, TRUE);
@define("DEFAULT_P_SIGN_POSN", 3, TRUE);
@define("DEFAULT_N_SIGN_POSN", 3, TRUE);

define("DATE_SEPARATOR", "/", TRUE); // Date separator
define("DEFAULT_DATE_FORMAT", "dd/mm/yyyy", TRUE); // Default date format
define("UNFORMAT_YEAR", 50, TRUE); // Unformat year

if (!function_exists('assets_url')) {

    function assets_url($str = '', $params = '') {

        if(file_exists(realpath(FCPATH.'assets/'.$str))) {
            // echo base_url('assets/'.$str).(($params != '')?$params:'');
            return base_url('assets/'.$str).(($params != '')?$params:'');
        } else {
            return false;
        }

        return false;
    }

}

if (!function_exists('load_header')) {
    function load_header($headers = array()) {
        $CI =& get_instance();
        $CI->load->view('header', $headers);
    }
}

if (!function_exists('load_footer')){
    function load_footer($footers = array()) {
        $CI =& get_instance();
        $CI->load->view('footer', $footers);
    }
}

if (!function_exists('md5x')) {

    function md5x($str) {
        $salt1 = base64_decode('TjRtQCUyMHNBeTItNGRhTDRoX000cjVATjElN0UwSyUyMSU1RSUyQzAlM0Fw');
        $salt2 = sha1(md5($str));
        return md5($salt1 . md5($str) . $salt2);
    }

}


// Get current date in default date format with time in hh:mm:ss format
// $namedformat = -1, 5-7, 9-11 (see comment for FormatDateTime)
if (!function_exists('CurrentDateTime')) {

    function CurrentDateTime($namedformat = -1) {
        if (in_array($namedformat, array(5, 6, 7, 9, 10, 11, 12, 13, 14, 15, 16, 17))) {
            if ($namedformat == 5 || $namedformat == 9 || $namedformat == 12 || $namedformat == 15) {
                $DT = FormatDateTime(date('Y-m-d H:i:s'), 9);
            } elseif ($namedformat == 6 || $namedformat == 10 || $namedformat == 13 || $namedformat == 16) {
                $DT = FormatDateTime(date('Y-m-d H:i:s'), 10);
            } else {
                $DT = FormatDateTime(date('Y-m-d H:i:s'), 11);
            }
            return $DT;
        } else {
            return date('Y-m-d H:i:s');
        }
    }

}


// Format a timestamp, datetime, date or time field from MySQL
// $namedformat:
// 0 - General Date
// 1 - Long Date
// 2 - Short Date (Default)
// 3 - Long Time
// 4 - Short Time (hh:mm:ss)
// 5 - Short Date (yyyy/mm/dd)
// 6 - Short Date (mm/dd/yyyy)
// 7 - Short Date (dd/mm/yyyy)
// 8 - Short Date (Default) + Short Time (if not 00:00:00)
// 9 - Short Date (yyyy/mm/dd) + Short Time (hh:mm:ss)
// 10 - Short Date (mm/dd/yyyy) + Short Time (hh:mm:ss)
// 11 - Short Date (dd/mm/yyyy) + Short Time (hh:mm:ss)
// 12 - Short Date - 2 digit year (yy/mm/dd)
// 13 - Short Date - 2 digit year (mm/dd/yy)
// 14 - Short Date - 2 digit year (dd/mm/yy)
// 15 - Short Date - 2 digit year (yy/mm/dd) + Short Time (hh:mm:ss)
// 16 - Short Date (mm/dd/yyyy) + Short Time (hh:mm:ss)
// 17 - Short Date (dd/mm/yyyy) + Short Time (hh:mm:ss)
if (!function_exists('FormatDateTime')) {

    function FormatDateTime($ts, $namedformat) {
        if (is_numeric($ts)) { // timestamp
            switch (strlen($ts)) {
                case 14:
                    $patt = '/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
                    break;
                case 12:
                    $patt = '/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
                    break;
                case 10:
                    $patt = '/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
                    break;
                case 8:
                    $patt = '/(\d{4})(\d{2})(\d{2})/';
                    break;
                case 6:
                    $patt = '/(\d{2})(\d{2})(\d{2})/';
                    break;
                case 4:
                    $patt = '/(\d{2})(\d{2})/';
                    break;
                case 2:
                    $patt = '/(\d{2})/';
                    break;
                default:
                    return $ts;
            }
            if ((isset($patt)) && (preg_match($patt, $ts, $matches))) {
                $year = $matches[1];
                $month = @$matches[2];
                $day = @$matches[3];
                $hour = @$matches[4];
                $min = @$matches[5];
                $sec = @$matches[6];
            }
            if (($namedformat == 0) && (strlen($ts) < 10))
                $namedformat = 2;
        }
        elseif (is_string($ts)) {
            if (preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $ts, $matches)) { // datetime
                $year = $matches[1];
                $month = $matches[2];
                $day = $matches[3];
                $hour = $matches[4];
                $min = $matches[5];
                $sec = $matches[6];
            } elseif (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $ts, $matches)) { // date
                $year = $matches[1];
                $month = $matches[2];
                $day = $matches[3];
                if ($namedformat == 0)
                    $namedformat = 2;
            }
            elseif (preg_match('/(^|\s)(\d{2}):(\d{2}):(\d{2})/', $ts, $matches)) { // time
                $hour = $matches[2];
                $min = $matches[3];
                $sec = $matches[4];
                if (($namedformat == 0) || ($namedformat == 1))
                    $namedformat = 3;
                if ($namedformat == 2)
                    $namedformat = 4;
            }
            else {
                return $ts;
            }
        } else {
            return $ts;
        }
        if (!isset($year))
            $year = 0; // dummy value for times
        if (!isset($month))
            $month = 1;
        if (!isset($day))
            $day = 1;
        if (!isset($hour))
            $hour = 0;
        if (!isset($min))
            $min = 0;
        if (!isset($sec))
            $sec = 0;
        $uts = @mktime($hour, $min, $sec, $month, $day, $year);
        if ($uts < 0 || $uts == FALSE || // failed to convert
                (intval($year) == 0 && intval($month) == 0 && intval($day) == 0)) {
            $year = substr_replace("0000", $year, -1 * strlen($year));
            $month = substr_replace("00", $month, -1 * strlen($month));
            $day = substr_replace("00", $day, -1 * strlen($day));
            $hour = substr_replace("00", $hour, -1 * strlen($hour));
            $min = substr_replace("00", $min, -1 * strlen($min));
            $sec = substr_replace("00", $sec, -1 * strlen($sec));
            $DefDateFormat = str_replace("yyyy", $year, DEFAULT_DATE_FORMAT);
            $DefDateFormat = str_replace("mm", $month, $DefDateFormat);
            $DefDateFormat = str_replace("dd", $day, $DefDateFormat);
            switch ($namedformat) {
                case 0:
                    return $DefDateFormat . " $hour:$min:$sec";
                    break;
                case 1://unsupported, return general date
                    return $DefDateFormat . " $hour:$min:$sec";
                    break;
                case 2:
                    return $DefDateFormat;
                    break;
                case 3:
                    if (intval($hour) == 0)
                        return "12:$min:$sec AM";
                    elseif (intval($hour) > 0 && intval($hour) < 12)
                        return "$hour:$min:$sec AM";
                    elseif (intval($hour) == 12)
                        return "$hour:$min:$sec PM";
                    elseif (intval($hour) > 12 && intval($hour) <= 23)
                        return (intval($hour) - 12) . ":$min:$sec PM";
                    else
                        return "$hour:$min:$sec";
                    break;
                case 4:
                    return "$hour:$min:$sec";
                    break;
                case 5:
                    return "$year" . DATE_SEPARATOR . "$month" . DATE_SEPARATOR . "$day";
                    break;
                case 6:
                    return "$month" . DATE_SEPARATOR . "$day" . DATE_SEPARATOR . "$year";
                    break;
                case 7:
                    return "$day" . DATE_SEPARATOR . "$month" . DATE_SEPARATOR . "$year";
                    break;
                case 8:
                    return $DefDateFormat . (($hour == 0 && $min == 0 && $sec == 0) ? "" : " $hour:$min:$sec");
                    break;
                case 9:
                    return "$year" . DATE_SEPARATOR . "$month" . DATE_SEPARATOR . "$day $hour:$min:$sec";
                    break;
                case 10:
                    return "$month" . DATE_SEPARATOR . "$day" . DATE_SEPARATOR . "$year $hour:$min:$sec";
                    break;
                case 11:
                    return "$day" . DATE_SEPARATOR . "$month" . DATE_SEPARATOR . "$year $hour:$min:$sec";
                    break;
                case 12:
                    return substr($year, -2) . DATE_SEPARATOR . $month . DATE_SEPARATOR . $day;
                    break;
                case 13:
                    return substr($year, -2) . DATE_SEPARATOR . $month . DATE_SEPARATOR . $day;
                    break;
                case 14:
                    return substr($year, -2) . DATE_SEPARATOR . $month . DATE_SEPARATOR . $day;
                    break;
            }
        } else {
            $DefDateFormat = str_replace("yyyy", $year, DEFAULT_DATE_FORMAT);
            $DefDateFormat = str_replace("mm", $month, $DefDateFormat);
            $DefDateFormat = str_replace("dd", $day, $DefDateFormat);
            switch ($namedformat) {
                case 0:
                    return strftime($DefDateFormat . " %H:%M:%S", $uts);
                    break;
                case 1:
                    return strftime("%A, %B %d, %Y", $uts);
                    break;
                case 2:
                    return strftime($DefDateFormat, $uts);
                    break;
                case 3:
                    return strftime("%I:%M:%S %p", $uts);
                    break;
                case 4:
                    return strftime("%H:%M:%S", $uts);
                    break;
                case 5:
                    return strftime("%Y" . DATE_SEPARATOR . "%m" . DATE_SEPARATOR . "%d", $uts);
                    break;
                case 6:
                    return strftime("%m" . DATE_SEPARATOR . "%d" . DATE_SEPARATOR . "%Y", $uts);
                    break;
                case 7:
                    return strftime("%d" . DATE_SEPARATOR . "%m" . DATE_SEPARATOR . "%Y", $uts);
                    break;
                case 8:
                    return strftime($DefDateFormat . (($hour == 0 && $min == 0 && $sec == 0) ? "" : " %H:%M:%S"), $uts);
                    break;
                case 9:
                    return strftime("%Y" . DATE_SEPARATOR . "%m" . DATE_SEPARATOR . "%d %H:%M:%S", $uts);
                    break;
                case 10:
                    return strftime("%m" . DATE_SEPARATOR . "%d" . DATE_SEPARATOR . "%Y %H:%M:%S", $uts);
                    break;
                case 11:
                    return strftime("%d" . DATE_SEPARATOR . "%m" . DATE_SEPARATOR . "%Y %H:%M:%S", $uts);
                    break;
                case 12:
                    return strftime("%y" . DATE_SEPARATOR . "%m" . DATE_SEPARATOR . "%d", $uts);
                    break;
                case 13:
                    return strftime("%m" . DATE_SEPARATOR . "%d" . DATE_SEPARATOR . "%y", $uts);
                    break;
                case 14:
                    return strftime("%d" . DATE_SEPARATOR . "%m" . DATE_SEPARATOR . "%y", $uts);
                    break;
                case 15:
                    return strftime("%y" . DATE_SEPARATOR . "%m" . DATE_SEPARATOR . "%d %H:%M:%S", $uts);
                    break;
                case 16:
                    return strftime("%m" . DATE_SEPARATOR . "%d" . DATE_SEPARATOR . "%y %H:%M:%S", $uts);
                    break;
                case 17:
                    return strftime("%d" . DATE_SEPARATOR . "%m" . DATE_SEPARATOR . "%y %H:%M:%S", $uts);
                    break;
                case 18:
                    return strftime("%d %B %Y", $uts);
                    break;
                case 19:
                    return strftime("%d %b %Y", $uts);
                    break;
            }
        }
    }

}

// Unformat date time based on format type
if (!function_exists('UnFormatDateTime')) {
    // unset($dt);
    function UnFormatDateTime($dt, $namedformat) {
        if (preg_match('/^([0-9]{4})-([0][1-9]|[1][0-2])-([0][1-9]|[1|2][0-9]|[3][0|1])( (0[0-9]|1[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9]))?$/', $dt))
            return $dt;
        $dt = trim($dt);
        while (strpos($dt, "  ") !== FALSE)
            $dt = str_replace("  ", " ", $dt);
        $arDateTime = explode(" ", $dt);
        if (count($arDateTime) == 0)
            return $dt;
        if ($namedformat == 0 || $namedformat == 1 || $namedformat == 2 || $namedformat == 8) {
            $arDefFmt = explode(DATE_SEPARATOR, DEFAULT_DATE_FORMAT);
            if ($arDefFmt[0] == "yyyy") {
                $namedformat = 9;
            } elseif ($arDefFmt[0] == "mm") {
                $namedformat = 10;
            } elseif ($arDefFmt[0] == "dd") {
                $namedformat = 11;
            }
        }
        $arDatePt = explode(DATE_SEPARATOR, $arDateTime[0]);
        if (count($arDatePt) == 3) {
            switch ($namedformat) {
                case 5:
                case 9: //yyyymmdd
                    if (Check_Date($arDateTime[0])) {
                        list($year, $month, $day) = $arDatePt;
                        break;
                    } else {
                        return $dt;
                    }
                case 6:
                case 10: //mmddyyyy
                    if (CheckUSDate($arDateTime[0])) {
                        list($month, $day, $year) = $arDatePt;
                        break;
                    } else {
                        return $dt;
                    }
                case 7:
                case 11: //ddmmyyyy
                    if (CheckEuroDate($arDateTime[0])) {
                        list($day, $month, $year) = $arDatePt;
                        break;
                    } else {
                        return $dt;
                    }
                case 12:
                case 15: //yymmdd
                    if (CheckShortDate($arDateTime[0])) {
                        list($year, $month, $day) = $arDatePt;
                        $year = UnformatYear($year);
                        break;
                    } else {
                        return $dt;
                    }
                case 13:
                case 16: //mmddyy
                    if (CheckShortUSDate($arDateTime[0])) {
                        list($month, $day, $year) = $arDatePt;
                        $year = UnformatYear($year);
                        break;
                    } else {
                        return $dt;
                    }
                case 14:
                case 17: //ddmmyy
                    if (CheckShortEuroDate($arDateTime[0])) {
                        list($day, $month, $year) = $arDatePt;
                        $year = UnformatYear($year);
                        break;
                    } else {
                        return $dt;
                    }
                default:
                    return $dt;
            }
            return $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" .
                    str_pad($day, 2, "0", STR_PAD_LEFT) .
                    ((count($arDateTime) > 1) ? " " . $arDateTime[1] : "");
        } else {
            return $dt;
        }
    }

}

// Check date format
// format: std/stdshort/us/usshort/euro/euroshort
if (!function_exists('CheckDateEx')) {

    function CheckDateEx($value, $format, $sep) {
        if (strval($value) == "")
            return TRUE;
        while (strpos($value, "  ") !== FALSE)
            $value = str_replace("  ", " ", $value);
        $value = trim($value);
        $arDT = explode(" ", $value);
        if (count($arDT) > 0) {
            if (preg_match('/^([0-9]{4})-([0][1-9]|[1][0-2])-([0][1-9]|[1|2][0-9]|[3][0|1])$/', $arDT[0], $matches)) { // accept yyyy-mm-dd
                $sYear = $matches[1];
                $sMonth = $matches[2];
                $sDay = $matches[3];
            } else {
                $wrksep = "\\$sep";
                switch ($format) {
                    case "std":
                        $pattern = '/^([0-9]{4})' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])$/';
                        break;
                    case "stdshort":
                        $pattern = '/^([0-9]{2})' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])$/';
                        break;
                    case "us":
                        $pattern = '/^([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0-9]{4})$/';
                        break;
                    case "usshort":
                        $pattern = '/^([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0-9]{2})$/';
                        break;
                    case "euro":
                        $pattern = '/^([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0-9]{4})$/';
                        break;
                    case "euroshort":
                        $pattern = '/^([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0-9]{2})$/';
                        break;
                }
                if (!preg_match($pattern, $arDT[0]))
                    return FALSE;
                $arD = explode($sep, $arDT[0]); // change DATE_SEPARATOR to $sep
                switch ($format) {
                    case "std":
                    case "stdshort":
                        $sYear = UnformatYear($arD[0]);
                        $sMonth = $arD[1];
                        $sDay = $arD[2];
                        break;
                    case "us":
                    case "usshort":
                        $sYear = UnformatYear($arD[2]);
                        $sMonth = $arD[0];
                        $sDay = $arD[1];
                        break;
                    case "euro":
                    case "euroshort":
                        $sYear = UnformatYear($arD[2]);
                        $sMonth = $arD[1];
                        $sDay = $arD[0];
                        break;
                }
            }
            if (!CheckDay($sYear, $sMonth, $sDay))
                return FALSE;
        }
        if (count($arDT) > 1 && !CheckTime($arDT[1]))
            return FALSE;
        return TRUE;
    }

}

// Check time
if (!function_exists('CheckTime')) {

    function CheckTime($value) {
        if (strval($value) == "")
            return TRUE;
        return preg_match('/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/', $value);
    }

}

// Unformat 2 digit year to 4 digit year
if (!function_exists('UnformatYear')) {

    function UnformatYear($yr) {
        if (strlen($yr) == 2) {
            if ($yr > UNFORMAT_YEAR)
                return "19" . $yr;
            else
                return "20" . $yr;
        } else {
            return $yr;
        }
    }

}

// Check day
if (!function_exists('CheckDay')) {

    function CheckDay($checkYear, $checkMonth, $checkDay) {
        $maxDay = 31;
        if ($checkMonth == 4 || $checkMonth == 6 || $checkMonth == 9 || $checkMonth == 11) {
            $maxDay = 30;
        } elseif ($checkMonth == 2) {
            if ($checkYear % 4 > 0) {
                $maxDay = 28;
            } elseif ($checkYear % 100 == 0 && $checkYear % 400 > 0) {
                $maxDay = 28;
            } else {
                $maxDay = 29;
            }
        }
        return CheckRange($checkDay, 1, $maxDay);
    }

}

// Check range
if (!function_exists('CheckRange')) {

    function CheckRange($value, $min, $max) {
        if (strval($value) == "")
            return TRUE;
        if (!CheckNumber($value))
            return FALSE;
        return NumberRange($value, $min, $max);
    }

}

// Check number range
if (!function_exists('NumberRange')) {

    function NumberRange($value, $min, $max) {
        if ((!is_null($min) && $value < $min) ||
                (!is_null($max) && $value > $max))
            return FALSE;
        return TRUE;
    }

}

// Check number
if (!function_exists('CheckNumber')) {

    function CheckNumber($value) {
        if (strval($value) == "")
            return TRUE;
        return is_numeric(trim($value));
    }

}

// Check Date format (yyyy/mm/dd)
if (!function_exists('Check_Date')) {

    function Check_Date($value) {
        return CheckDateEx($value, "std", DATE_SEPARATOR);
    }

}

// Check Date format (yy/mm/dd)
if (!function_exists('CheckShortDate')) {

    function CheckShortDate($value) {
        return CheckDateEx($value, "stdshort", DATE_SEPARATOR);
    }

}

// Check US Date format (mm/dd/yyyy)
if (!function_exists('CheckUSDate')) {

    function CheckUSDate($value) {
        return CheckDateEx($value, "us", DATE_SEPARATOR);
    }

}

// Check US Date format (mm/dd/yy)
if (!function_exists('CheckShortUSDate')) {

    function CheckShortUSDate($value) {
        return CheckDateEx($value, "usshort", DATE_SEPARATOR);
    }

}

// Check Euro Date format (dd/mm/yyyy)
if (!function_exists('CheckEuroDate')) {

    function CheckEuroDate($value) {
        return CheckDateEx($value, "euro", DATE_SEPARATOR);
    }

}

// Check Euro Date format (dd/mm/yy)
if (!function_exists('CheckShortEuroDate')) {

    function CheckShortEuroDate($value) {
        return CheckDateEx($value, "euroshort", DATE_SEPARATOR);
    }

}

// Get current date in default date format
// $namedformat = -1|5|6|7 (see comment for FormatDateTime)
if (!function_exists('CurrentDate')) {

    function CurrentDate($namedformat = -1) {
        if ($namedformat > -1) {
            if ($namedformat == 6 || $namedformat == 7) {
                return FormatDateTime(date('Y-m-d'), $namedformat);
            } else {
                return FormatDateTime(date('Y-m-d'), 5);
            }
        } else {
            return date('Y-m-d');
        }
    }

}

if (!function_exists('TomorrowDate')) {

    function TomorrowDate() {
        return date('Y-m-d', strtotime('+1 day'));
    }

}

if (!function_exists('GetTotalHours')) {

    function GetTotalHours($currentdate, $start_time, $end_time) {
        $today = $currentdate; //unformat date
        $start = strtotime($today . ' ' . $start_time);
        $end = strtotime($today . ' ' . $end_time);
        // $diff = floor(($end - $start) / 3600);
        // or if you don't need the exact hours
        $diff = ($end - $start) / 3600;
        return $diff;
    }

}

if (!function_exists('GetOvertime')) {

    function GetOvertime($currentdate, $end_time, $ot_time = '') {
        $today = $currentdate; //unformat date
        $end = strtotime($today . ' ' . $end_time);
        $ot = strtotime($today . ' ' . $ot_time);
        $diff = ($ot - $end) / 3600;
        return $diff;
    }

}

if (!function_exists('is_selected')) {

    function is_selected($str1, $str2) {
        if ($str1 == $str2) {
            return ' selected';
        } else {
            return '';
        }
    }

}

if (!function_exists('is_checked')) {

    function is_checked($str1, $str2) {
        if ($str1 == $str2) {
            return ' checked';
        } else {
            return '';
        }
    }

}

if (!function_exists('get_month')) {
    function get_month($idx = "") {
        $month = array('January','February','Maret','April','Mey','June','July','Augustus','September','October','November','Desember');
        return $month[$idx];
    }
}

// Format currency
// Arguments: Expression [,NumDigitsAfterDecimal [,IncludeLeadingDigit [,UseParensForNegativeNumbers [,GroupDigits]]]])
// NumDigitsAfterDecimal is the numeric value indicating how many places to the
// right of the decimal are displayed
// -1 Use Default
// The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits
// arguments have the following settings:
// -1 True
// 0 False
// -2 Use Default
if (!function_exists('FormatCurrency')) {
    function FormatCurrency($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit = -2, $UseParensForNegativeNumbers = -2, $GroupDigits = -2) {

        $CI =& get_instance();


        // export the values returned by localeconv into the local scope
        if (!USE_DEFAULT_LOCALE) {
            extract(localeconv());
            if ($CI->config->item('charset') == "utf-8") {
                if ($int_curr_symbol == "EUR" && ord($currency_symbol) == 128) {
                    $currency_symbol = "\xe2\x82\xac";
                } elseif ($int_curr_symbol == "GBP" && ord($currency_symbol) == 163) {
                    $currency_symbol = "\xc2\xa3";
                }
            }
        }

        $currency_symbol = DEFAULT_CURRENCY_SYMBOL;


        // set defaults if locale is not set
        if (empty($decimal_point)) $decimal_point = DEFAULT_DECIMAL_POINT;
        if (empty($thousands_sep)) $thousands_sep = DEFAULT_THOUSANDS_SEP;
        if (empty($currency_symbol)) $currency_symbol = DEFAULT_CURRENCY_SYMBOL;
        if (empty($mon_decimal_point)) $mon_decimal_point = DEFAULT_MON_DECIMAL_POINT;
        if (empty($mon_thousands_sep)) $mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
        if (empty($positive_sign)) $positive_sign = DEFAULT_POSITIVE_SIGN;
        if (empty($negative_sign)) $negative_sign = DEFAULT_NEGATIVE_SIGN;
        if (empty($frac_digits) || $frac_digits == CHAR_MAX) $frac_digits = DEFAULT_FRAC_DIGITS;
        if (empty($p_cs_precedes) || $p_cs_precedes == CHAR_MAX) $p_cs_precedes = DEFAULT_P_CS_PRECEDES;
        if (empty($p_sep_by_space) || $p_sep_by_space == CHAR_MAX) $p_sep_by_space = DEFAULT_P_SEP_BY_SPACE;
        if (empty($n_cs_precedes) || $n_cs_precedes == CHAR_MAX) $n_cs_precedes = DEFAULT_N_CS_PRECEDES;
        if (empty($n_sep_by_space) || $n_sep_by_space == CHAR_MAX) $n_sep_by_space = DEFAULT_N_SEP_BY_SPACE;
        if (empty($p_sign_posn) || $p_sign_posn == CHAR_MAX) $p_sign_posn = DEFAULT_P_SIGN_POSN;
        if (empty($n_sign_posn) || $n_sign_posn == CHAR_MAX) $n_sign_posn = DEFAULT_N_SIGN_POSN;

        // check $NumDigitsAfterDecimal
        if ($NumDigitsAfterDecimal > -1)
            $frac_digits = $NumDigitsAfterDecimal;

        // check $UseParensForNegativeNumbers
        if ($UseParensForNegativeNumbers == -1) {
            $n_sign_posn = 0;
            if ($p_sign_posn == 0) {
                if (DEFAULT_P_SIGN_POSN != 0)
                    $p_sign_posn = DEFAULT_P_SIGN_POSN;
                else
                    $p_sign_posn = 3;
            }
        } elseif ($UseParensForNegativeNumbers == 0) {
            if ($n_sign_posn == 0)
                if (DEFAULT_P_SIGN_POSN != 0)
                    $n_sign_posn = DEFAULT_P_SIGN_POSN;
                else
                    $n_sign_posn = 3;
        }

        // check $GroupDigits
        if ($GroupDigits == -1) {
            $mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
        } elseif ($GroupDigits == 0) {
            $mon_thousands_sep = "";
        }

        // start by formatting the unsigned number
        $number = number_format(abs($amount),
                                $frac_digits,
                                $mon_decimal_point,
                                $mon_thousands_sep);

        // check $IncludeLeadingDigit
        if ($IncludeLeadingDigit == 0) {
            if (substr($number, 0, 2) == "0.")
                $number = substr($number, 1, strlen($number)-1);
        }
        if ($amount < 0) {
            $sign = $negative_sign;

            // "extracts" the boolean value as an integer
            $n_cs_precedes  = intval($n_cs_precedes  == true);
            $n_sep_by_space = intval($n_sep_by_space == true);
            $key = $n_cs_precedes . $n_sep_by_space . $n_sign_posn;
        } else {
            $sign = $positive_sign;
            $p_cs_precedes  = intval($p_cs_precedes  == true);
            $p_sep_by_space = intval($p_sep_by_space == true);
            $key = $p_cs_precedes . $p_sep_by_space . $p_sign_posn;
        }
        $formats = array(

          // currency symbol is after amount
          // no space between amount and sign

          '000' => '(%s' . $currency_symbol . ')',
          '001' => $sign . '%s ' . $currency_symbol,
          '002' => '%s' . $currency_symbol . $sign,
          '003' => '%s' . $sign . $currency_symbol,
          '004' => '%s' . $sign . $currency_symbol,

          // one space between amount and sign
          '010' => '(%s ' . $currency_symbol . ')',
          '011' => $sign . '%s ' . $currency_symbol,
          '012' => '%s ' . $currency_symbol . $sign,
          '013' => '%s ' . $sign . $currency_symbol,
          '014' => '%s ' . $sign . $currency_symbol,

          // currency symbol is before amount
          // no space between amount and sign

          '100' => '(' . $currency_symbol . '%s)',
          '101' => $sign . $currency_symbol . '%s',
          '102' => $currency_symbol . '%s' . $sign,
          '103' => $sign . $currency_symbol . '%s',
          '104' => $currency_symbol . $sign . '%s',

          // one space between amount and sign
          '110' => '(' . $currency_symbol . ' %s)',
          '111' => $sign . $currency_symbol . ' %s',
          '112' => $currency_symbol . ' %s' . $sign,
          '113' => $sign . $currency_symbol . ' %s',
          '114' => $currency_symbol . ' ' . $sign . '%s');

      // lookup the key in the above array
        return sprintf($formats[$key], $number);
    }
}

if (!function_exists('FormatCurrency2')) {
    function FormatCurrency2($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit = -2, $UseParensForNegativeNumbers = -2, $GroupDigits = -2) {

            $CI =& get_instance();

            // export the values returned by localeconv into the local scope
        if (!USE_DEFAULT_LOCALE) {
            extract(localeconv());
            if ($CI->config->item('charset') == "utf-8") {
                if ($int_curr_symbol == "EUR" && ord($currency_symbol) == 128) {
                    $currency_symbol = "\xe2\x82\xac";
                } elseif ($int_curr_symbol == "GBP" && ord($currency_symbol) == 163) {
                    $currency_symbol = "\xc2\xa3";
                }
            }
        }

            $currency_symbol = '';

        // set defaults if locale is not set
        if (empty($decimal_point)) $decimal_point = DEFAULT_DECIMAL_POINT;
        if (empty($thousands_sep)) $thousands_sep = DEFAULT_THOUSANDS_SEP;
        if (empty($mon_decimal_point)) $mon_decimal_point = DEFAULT_MON_DECIMAL_POINT;
        if (empty($mon_thousands_sep)) $mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
        if (empty($positive_sign)) $positive_sign = DEFAULT_POSITIVE_SIGN;
        if (empty($negative_sign)) $negative_sign = DEFAULT_NEGATIVE_SIGN;
        if (empty($frac_digits) || $frac_digits == CHAR_MAX) $frac_digits = DEFAULT_FRAC_DIGITS;
        if (empty($p_cs_precedes) || $p_cs_precedes == CHAR_MAX) $p_cs_precedes = DEFAULT_P_CS_PRECEDES;
        if (empty($p_sep_by_space) || $p_sep_by_space == CHAR_MAX) $p_sep_by_space = DEFAULT_P_SEP_BY_SPACE;
        if (empty($n_cs_precedes) || $n_cs_precedes == CHAR_MAX) $n_cs_precedes = DEFAULT_N_CS_PRECEDES;
        if (empty($n_sep_by_space) || $n_sep_by_space == CHAR_MAX) $n_sep_by_space = DEFAULT_N_SEP_BY_SPACE;
        if (empty($p_sign_posn) || $p_sign_posn == CHAR_MAX) $p_sign_posn = DEFAULT_P_SIGN_POSN;
        if (empty($n_sign_posn) || $n_sign_posn == CHAR_MAX) $n_sign_posn = DEFAULT_N_SIGN_POSN;

        // check $NumDigitsAfterDecimal
        if ($NumDigitsAfterDecimal > -1)
            $frac_digits = $NumDigitsAfterDecimal;

        // check $UseParensForNegativeNumbers
        if ($UseParensForNegativeNumbers == -1) {
            $n_sign_posn = 0;
            if ($p_sign_posn == 0) {
                if (DEFAULT_P_SIGN_POSN != 0)
                    $p_sign_posn = DEFAULT_P_SIGN_POSN;
                else
                    $p_sign_posn = 3;
            }
        } elseif ($UseParensForNegativeNumbers == 0) {
            if ($n_sign_posn == 0)
                if (DEFAULT_P_SIGN_POSN != 0)
                    $n_sign_posn = DEFAULT_P_SIGN_POSN;
                else
                    $n_sign_posn = 3;
        }

        // check $GroupDigits
        if ($GroupDigits == -1) {
            $mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
        } elseif ($GroupDigits == 0) {
            $mon_thousands_sep = "";
        }

        // start by formatting the unsigned number
        $number = number_format(abs($amount),
                                $frac_digits,
                                $mon_decimal_point,
                                $mon_thousands_sep);

        // check $IncludeLeadingDigit
        if ($IncludeLeadingDigit == 0) {
            if (substr($number, 0, 2) == "0.")
                $number = substr($number, 1, strlen($number)-1);
        }
        if ($amount < 0) {
            $sign = $negative_sign;

            // "extracts" the boolean value as an integer
            $n_cs_precedes  = intval($n_cs_precedes  == true);
            $n_sep_by_space = intval($n_sep_by_space == true);
            $key = $n_cs_precedes . $n_sep_by_space . $n_sign_posn;
        } else {
            $sign = $positive_sign;
            $p_cs_precedes  = intval($p_cs_precedes  == true);
            $p_sep_by_space = intval($p_sep_by_space == true);
            $key = $p_cs_precedes . $p_sep_by_space . $p_sign_posn;
        }
        $formats = array(

          // currency symbol is after amount
          // no space between amount and sign

          '000' => '(%s' . $currency_symbol . ')',
          '001' => $sign . '%s ' . $currency_symbol,
          '002' => '%s' . $currency_symbol . $sign,
          '003' => '%s' . $sign . $currency_symbol,
          '004' => '%s' . $sign . $currency_symbol,

          // one space between amount and sign
          '010' => '(%s ' . $currency_symbol . ')',
          '011' => $sign . '%s ' . $currency_symbol,
          '012' => '%s ' . $currency_symbol . $sign,
          '013' => '%s ' . $sign . $currency_symbol,
          '014' => '%s ' . $sign . $currency_symbol,

          // currency symbol is before amount
          // no space between amount and sign

          '100' => '(' . $currency_symbol . '%s)',
          '101' => $sign . $currency_symbol . '%s',
          '102' => $currency_symbol . '%s' . $sign,
          '103' => $sign . $currency_symbol . '%s',
          '104' => $currency_symbol . $sign . '%s',

          // one space between amount and sign
          '110' => '(' . $currency_symbol . ' %s)',
          '111' => $sign . $currency_symbol . ' %s',
          '112' => $currency_symbol . ' %s' . $sign,
          '113' => $sign . $currency_symbol . ' %s',
          '114' => $currency_symbol . ' ' . $sign . '%s');

      // lookup the key in the above array
        return sprintf($formats[$key], $number);
    }
}

if (!function_exists('UnformatNumber')) {
    function UnFormatNumber($v, $dp, $sep) {
        $v = str_replace(" ", "", $v);
        $v = str_replace($sep, "", $v);
        $v = str_replace($dp, ".", $v);
        return $v;
    }
}

if (!function_exists('print_x')) {
    /**
     * [print_x modified of print_r()]
     * @param  [type] $data [object array]
     * @return [type]       [string]
     */
    function print_x($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}

if (!function_exists('priority_label')) {
    function priority_label($priority) {
        switch($priority) {
            case 'Low';
                $label = 'btn-info';
            break;
            case 'Medium';
                $label = 'btn-warning';
            break;
            case 'High';
                $label = 'btn-important';
            break;
        }
        return $label;
    }
}

if (!function_exists('ticket_number_format')) {
    function ticket_number_format($ticket_id) {
        return '#'.str_pad($ticket_id, '7', '0', STR_PAD_LEFT);
    }
}

if (!function_exists('color_style')) {
    function color_style() {
        $style = array(array('color' => 'primary',
                           'label' => 'Primary'),
                     array('color' => 'info',
                           'label' => 'Info'),
                     array('color' => 'success',
                           'label' => 'Success'),
                     array('color' => 'warning',
                           'label' => 'Warning'),
                     array('color' => 'danger',
                           'label' => 'Danger'),
                     array('color' => 'gray',
                           'label' => 'Gray'),
                );
        return json_decode(json_encode($style));

    }
}


if (!function_exists('image_types')) {
    function image_types() {

        $image_types = array('image/jpeg','image/pjpeg','image/png','image/x-png','image/bmp', 'image/x-bmp', 'image/x-bitmap', 'image/x-xbitmap', 'image/x-win-bitmap', 'image/x-windows-bmp', 'image/ms-bmp', 'image/x-ms-bmp', 'application/bmp', 'application/x-bmp', 'application/x-win-bitmap');

        return $image_types;

    }
}


if (!function_exists('log_message_x')) {
    function log_message_x($lmessage, $log_to = 'file', $ltype = 'error') {

        $CI =& get_instance();
        $username = $CI->auth->get_username();

        switch($log_to) {
            case 'file':
                log_message($ltype, uri_string().': '.$lmessage.' from: '.(!empty($username)?$username.'@':'').$CI->input->ip_address());
            break;
            case 'db':

            break;
            case 'both':
                log_message($ltype, uri_string().': '.$lmessage.' from: '.(!empty($username)?$username.'@':'').$CI->input->ip_address());
            break;
        }

    }
}


if (!function_exists('s_datediff')) {
    function s_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false){

       if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
       if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);

       $diff = date_diff( $dt_menor, $dt_maior, ! $relative);

       switch( $str_interval){
           case "y":
               $total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
           case "m":
               $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
               break;
           case "d":
               $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
               break;
           case "h":
               $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
               break;
           case "i":
               $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
               break;
           case "s":
               $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
               break;
          }
       if( $diff->invert)
               return -1 * $total;
       else    return $total;
    }
}