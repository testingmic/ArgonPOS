<?php
// ensure this file is being included by a parent file
if( !defined( 'SITE_URL' ) && !defined( 'SITE_DATE_FORMAT' ) ) die( 'Restricted access' );

class Dates extends Pos {
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 *
	 * @param string
	 * @return date
	 *
	 **/
	public function dateFormater(array $dateParam) {
		$dateParam = (object)$dateParam;
		$date = isset($dateParam->date) ? date("Y-m-d") : null;
		$period = isset($dateParam->period) ? "+1 days" : null;
		$format = isset($dateParam->format) ? "Y-m-d" :  null;
		return date("$format", strtotime($date . " $period "));
	}

	public function get_date($initial_info) {

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

	public function Start_End_Date_of_a_week($week, $year){
		$time = strtotime("1 January $year");
		$day = date('w', $time);
		$time += ((7*($week-1))+1-$day)*24*3600;
		$dates[0] = date('Y-m-d', $time);
		return $dates;
	}

	public function get_week($initial_info, $week_start) {
		
		
		$fix = date('D');
		if ($fix === 'Sat'){
			$thswk = date('Y-m-d', strtotime("$week_start "." -1 days"));
		} elseif ($fix === 'Sun'){
			$thswk = date('Y-m-d', strtotime("$week_start "." -2 days"));
		} elseif (($fix !== 'Sat') && ($fix !== 'Sun')){
			$thswk = date('Y-m-d', strtotime("$week_start "." +0 day"));
		}
		
		$wk = date("W", strtotime($thswk));
		$yr = date("Y", strtotime($thswk));
		$draw = $this->Start_End_Date_of_a_week($wk,$yr);
		$stat_wks = date('Y-m-d', strtotime("-1 days", strtotime($draw[0])));
		$wstrt = $draw[0];
		$wend = date('Y-m-d', strtotime("+6 days", strtotime($draw[0])));
		$stat_wke = date('Y-m-d', strtotime("+1 days", strtotime($wend)));

		$laswk = date("Y-m-d", strtotime("-7 days", strtotime($thswk)));
		$wk = date("W", strtotime($laswk));
		$yr = date("Y", strtotime($laswk));
		$draw = $this->Start_End_Date_of_a_week($wk,$yr);
		$laswstrt = $draw[0];
		$laswend = date('Y-m-d', strtotime("+6 days", strtotime($draw[0])));

		if($initial_info == "this_wkstart")
			return $wstrt;
		elseif($initial_info == "this_wkend")
			return $wend;
		elseif($initial_info == "last_wkstart")
			return $laswstrt;
		elseif($initial_info == "last_wkend")
			return $laswend;

	}


	public function get_month($initial_info, $month, $year) {
		
		if($initial_info == "this_mntstart")
			return date('Y-m-01', strtotime("$year-$month-01"));
		elseif($initial_info == "this_mntend")
			return date('Y-m-t', strtotime("$year-$month-01"));
		elseif($initial_info == "last_mntstart")
			return date('Y-m-01', strtotime("$year-$month-01 "." last month"));
		elseif($initial_info == "last_mntend")
			return date('Y-m-t', strtotime("$year-$month-01" . " last month"));

	}

	/**
	 * @method listDays
	 * @desc It lists dates between two specified dates
	 * @param string $startDate 	This is the date to begin query from
	 * @param string $endDate	This is the date to end the request query
	 * @param string $format 	This is the format that will be applied to the date to be returned
	 * @return array
	 **/
	public function listDays($startDate, $endDate, $weekDays, $format='Y-m-d') {

		$period = new DatePeriod(
		  new DateTime($startDate),
		  new DateInterval('P1D'),
		  new DateTime(date('Y-m-d',strtotime($endDate. '+1 days')))
		);
		$days = array();
		// fetch the days to display
		foreach ($period as $key => $value) {
			// $days[] = $value->format($format);
			if((in_array(date("l", strtotime($value->format($format))), $weekDays))) {
				$days[] = $value->format($format);
			}
		}
		
		return $days;
	}

	public function convertToPeriod($timeFrame, $period) {
		// Check the time frame hourly
		if($timeFrame == "hour") {
			// get the hours for the day
			$hours = range(0, 23, 1);
			// loop through the hours
			foreach ($hours as $hr) {
				if($hr == $period) {
					return date('hA', strtotime("today +$hr hours"));
					break;
				}
			}
		}
		// Check the time frame monthly
		elseif($timeFrame == "month") {
			// get the months for the day
			$months = range(0, 11, 1);

			// loop through the months
			foreach ($months as $hr) {
				if($hr == ($period-1)) {
					return date('Y-m-01', strtotime("January +$hr month"));
					break;
				}
			}
		} else {
			//return $period;
		}
	}

	public function dataDateComparison($rangeSet, $dataSet, $dateRange = [], $weekDays = null) {
		// return data set
		$returnDataSet = [];

		if($rangeSet == "list-days") {
			// set the dates
			$dates = array();
			
			// get the list of days
			$datesList = $this->listDays($dateRange[0], $dateRange[1], $weekDays);
			
			$notFoundDays = array_diff($datesList, $dataSet);
			$dataSet = [];

			foreach($notFoundDays as $key => $value) {
				$dataSet[$value] = "0.00";
			}
			// check the rangeset that has been parsed
		} elseif($rangeSet == "hour") {
			// set the first parameter for the hours
			$hourOfDay = array();

			// get the hours for the day
			$hours = range(0, 23, 1);
			
			// loop through the hours
			foreach ($hours as $hr) {
				$hourOfDay[] = $hr;
			}
			
			// find the difference in the two array set
			$notFoundHours = array_diff($hourOfDay, $dataSet);
			$dataSet = [];
			
			foreach($notFoundHours as $key => $value) {
				$dataSet[$value] = "0.00";
			}
		} elseif($rangeSet == "year-to-months") {
			// set the first parameter for the hours
			$monthsOfYear = array();

			// loop through the hours
			for($i=1; $i <= 12; $i++) {
				$monthsOfYear[] = $i;
			}

			// find the difference in the two array set
			$notFoundMonths = array_diff($monthsOfYear, $dataSet);
			
			$dataSet = [];
			foreach($notFoundMonths as $key => $value) {
				$dataSet[$value] = "0.00";
			}
		}

		return $dataSet;
	}
}