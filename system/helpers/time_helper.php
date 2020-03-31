<?php
/**
 * Common Functions
 *
 * Loads the base classes and executes the request.
 *
 * @package     Helpers
 * @subpackage  Time Helper Functions
 * @category    Core Functions
 * @author      VisamiNetSolutions Dev Team
 */

function time_diff($timestamp, $current_time=null) {
	date_default_timezone_set("UTC");
	
	$strTime = array("sec", "min", "hr", "day", "wk", "mnth", "yr");
	$length = array("60","60","24","4","30","12","10");

	$currentTime = ($current_time) ?? time();
	if($currentTime >= $timestamp) {

		$diff = $current_time - $timestamp;

		for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
			$diff = $diff / $length[$i];
		}

		$diff = round($diff);

		return $diff . " " . $strTime[$i] . "s";

	} else {
		
		$diff = $timestamp - $current_time;
		for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
		$diff = $diff / $length[$i];
		}

		$diff = round($diff);
		return $diff . " " . $strTime[$i] . "s";
	}
	
}

 function secondsToTime($inputSeconds, $shortText=true) {

    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;

    // extract days
    $days = floor($inputSeconds / $secondsInADay);
    // extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);
    // extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);
    // extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);
    // return the final array
    $obj = array(
        'd' => (int) $days,
        'h' => (int) $hours,
        'm' => (int) $minutes,
        's' => (int) $seconds,
    );

    $txt = "";
    if($shortText) {
        if($obj['h'] > 0) {
        	$txt .= " ".$obj['h']. " Hour". ($obj['h'] > 1) ? "s" : null;
        }
        if($obj['m'] > 0) {
        	$txt .= " ".$obj['m']. " Minute";
        	$txt .= ($obj['m'] > 1) ? "s" : null;
        }
        if($obj['s'] > 0) {
        	$txt .= " ".$obj['s']. " Second";
        	$txt .= ($obj['s'] > 1) ? "s" : null;
        }
    } else {
        if($obj['h'] > 0) {
            $txt .= " ".$obj['h']. "h";
        }
        if($obj['m'] > 0) {
            $txt .= " ".$obj['m']. "m";
        }
        if($obj['s'] > 0) {
            $txt .= " ".$obj['s']. "s";
        }
    }

    return $txt;
}

function get_date($initial_info) {

    $fix = date('D');
    if ($fix === 'Sat'){
        $today = date('Y-m-d', strtotime("-1 days"));
    } elseif ($fix === 'Sun'){
        $today = date('Y-m-d', strtotime("-2 days"));
    } elseif (($fix !== 'Sat') && ($fix !== 'Sun')){
        $today = date('Y-m-d');
    }
    $fix = date('D', strtotime("-1 days"));
    if ($fix === 'Sat'){
        $yesterday = date('Y-m-d',strtotime("-2 days"));
    }elseif ($fix === 'Sun'){
        $yesterday = date('Y-m-d',strtotime("-3 days"));
    }elseif(($fix !== 'Sat') && ($fix !== 'Sun')){
        $yesterday = date('Y-m-d',strtotime("-1 days"));
    }

    if($initial_info == "today")
        return $today;
    elseif($initial_info == "yesterday")
        return $yesterday;
}

function Start_End_Date_of_a_week($week, $year){
    $time = strtotime("1 January $year");
    $day = date('w', $time);
    $time += ((7*($week-1))+1-$day)*24*3600;
    $dates[0] = date('Y-m-d', $time);
    return $dates;
}

function get_week($initial_info, $week_start, $this_month, $this_year) {
    
    
    $fix = date('D');
    if ($fix === 'Sat'){
        $thswk = date('Y-m-d', strtotime("$this_year-$this_month-$week_start "." -1 days"));
    }elseif ($fix === 'Sun'){
        $thswk = date('Y-m-d', strtotime("$this_year-$this_month-$week_start "." -2 days"));
    }elseif (($fix !== 'Sat') && ($fix !== 'Sun')){
        $thswk = date('Y-m-d', strtotime("$this_year-$this_month-$week_start "." +0 day"));
    }
    
    $wk = date("W", strtotime($thswk));
    $yr = date("Y", strtotime($thswk));
    $draw = Start_End_Date_of_a_week($wk,$yr);
    $stat_wks = date('Y-m-d', strtotime("-1 days", strtotime($draw[0])));
    $wstrt = $draw[0];
    $wend = date('Y-m-d', strtotime("+4 days", strtotime($draw[0])));
    $stat_wke = date('Y-m-d', strtotime("+1 days", strtotime($wend)));

    $laswk = date("Y-m-d", strtotime("-7 days", strtotime($thswk)));
    $wk = date("W", strtotime($laswk));
    $yr = date("Y", strtotime($laswk));
    $draw = Start_End_Date_of_a_week($wk,$yr);
    $laswstrt = $draw[0];
    $laswend = date('Y-m-d', strtotime("+4 days", strtotime($draw[0])));

    if($initial_info == "this_wkstart")
        return $wstrt;
    elseif($initial_info == "this_wkend")
        return $wend;
    elseif($initial_info == "last_wkstart")
        return $laswstrt;
    elseif($initial_info == "last_wkend")
        return $laswend;

}

function get_month($initial_info, $month, $year) {
    
    if($initial_info == "this_mntstart")
        return date('Y-m-01', strtotime("$year-$month-01"));
    elseif($initial_info == "this_mntend")
        return date('Y-m-t', strtotime("$year-$month-01"));
    elseif($initial_info == "last_mntstart")
        return date('Y-m-01', strtotime("$year-$month-01 "." last month"));
    elseif($initial_info == "last_mntend")
        return date('Y-m-t', strtotime("$year-$month-01" . " last month"));

}

function convert_to_days($dateToBeginQuery, $dateToEndQuery) {
    if(strtotime($dateToEndQuery) > strtotime($dateToBeginQuery)) {
        $datetime1 = new DateTime($dateToBeginQuery);
        $datetime2 = new DateTime($dateToEndQuery);
        $interval = $datetime1->diff($datetime2);
        return $interval->days;
    } else {
        return 1;
    }
}

function date_difference($start_date, $interval) {
        
    $datetime1 = (int)($interval*24*60*60);
    $datetime2 = strtotime($start_date);

    $secs = $datetime2 - $datetime1;// == <seconds between the two times>

    return date("Y-m-d", ($secs));
}