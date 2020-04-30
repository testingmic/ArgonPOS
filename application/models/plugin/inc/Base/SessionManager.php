<?php 
/**
 * Trigger this file on Plugin uninstall
 * @package EvelynApi
 */

namespace Inc\Base;

class SessionManager {
	
	public $result;
	
	public function manageSessions($currentItem, $queryString, $queryData) {

		// confirm that the session already exists and not empty
		if(isset($_SESSION['EvelynApi']) && !empty($_SESSION['EvelynApi'])) {
			if(is_array($_SESSION['EvelynApi'])) {
				if(isset($_SESSION['EvelynApi'][$currentItem])) {
					
					foreach($_SESSION['EvelynApi'][$currentItem] as $eachArray) {

						if($eachArray['key'] == $queryString) {
							return $eachArray['value'];
							break;
						}

					}
				} else {
					$this->addSessions($currentItem, $queryString, $queryData);
				}
			}
		}
	}

	public function addSessions($currentItem, $queryString, $queryData) {

		// add the data to the session
		$arrayData = [
			'key' => $queryString,
			'value' => $queryData
		];

		if(empty($_SESSION['EvelynApi'][$currentItem])) {
			$_SESSION['EvelynApi'][$currentItem] = [$arrayData];
		} else  {
			array_push($_SESSION['EvelynApi'][$currentItem], $arrayData);
		}

	}

	public function checkSession($currentItem, $queryString) {
		
		if(isset($_SESSION['EvelynApi'][$currentItem])) {
			foreach($_SESSION['EvelynApi'][$currentItem] as $eachArray) {
				if($eachArray['key'] == $queryString) {
					return true;
					break;
				}
			}

		}
		return false;
	}

	public function deleteSession($currentItem, $queryString) {

		@session_start();

		if(isset($_SESSION['EvelynApi'][$currentItem])) {
			foreach($_SESSION['EvelynApi'][$currentItem] as $key => $eachArray) {
				if($eachArray['key'] == $queryString) {
					unset($_SESSION['EvelynApi'][$currentItem][$key]);
					return true;
					break;
				}
			}
		}
		return true;
	}
}