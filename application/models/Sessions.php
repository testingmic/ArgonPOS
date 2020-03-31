<?php

class Sessions extends Hr {
	
	public function __construct() {
		parent::__construct();
	}

	private function countSessionData($sessionName) {
		return (isset($_SESSION[$sessionName]) and count($_SESSION[$sessionName]) > 0) ? true : false;
	}

	public function addSessionData($sessionName, $firstVariable, $secondVariable, $thirdVariable = null, $forthVariable = null, $fifthVariable = null) {
		
		if($this->countSessionData($sessionName)) {
			foreach($_SESSION[$sessionName] as $key => $value) {			
				if($value['item_id'] == $firstVariable) {
				 	$_SESSION[$sessionName][$key]['item_value'] = trim($secondVariable);
				 	(!empty($thirdVariable)) ? ($_SESSION[$sessionName][$key]['item_added'] = trim($thirdVariable)) : null;
				 	(!empty($forthVariable)) ? ($_SESSION[$sessionName][$key]['item_name'] = trim($forthVariable)) : null;
				 	(!empty($fifthVariable)) ? ($_SESSION[$sessionName][$key]['fifth_item'] = trim($fifthVariable)) : null;
				 	break;
				}
			}

			$itemId = array_column($_SESSION[$sessionName], "item_id");

            if (!in_array($firstVariable, $itemId)) {
            	if(empty($thirdVariable) && empty($forthVariable)) {
            		$_SESSION[$sessionName][] = array(
						'item_id'=>$firstVariable, 
						'item_value' => trim($secondVariable)
					);
            	} else {
            		if(!empty($forthVariable)) {
            			if(!empty($fifthVariable)) {
            				$_SESSION[$sessionName][] = array(
								'item_id'=>$firstVariable, 
								'item_value' => trim($secondVariable),
								'item_added' => trim($thirdVariable),
								'item_name' => trim($forthVariable),
								'fifth_item' => trim($fifthVariable)
							);
            			} else {
	            			$_SESSION[$sessionName][] = array(
								'item_id'=>$firstVariable, 
								'item_value' => trim($secondVariable),
								'item_added' => trim($thirdVariable),
								'item_name' => trim($forthVariable),
							);
	            		}
            		} else {
	            		$_SESSION[$sessionName][] = array(
							'item_id'=>$firstVariable, 
							'item_value' => trim($secondVariable),
							'item_added' => trim($thirdVariable)
						);
	            	}
            	}
            	
            }

		} else {
			if(empty($thirdVariable) && empty($forthVariable)) {
				$_SESSION[$sessionName][] = array(
					'item_id' => $firstVariable,
					'item_value' => $secondVariable
				);
			} else {
				if(empty($forthVariable)) {
					$_SESSION[$sessionName][] = array(
						'item_id' => $firstVariable,
						'item_value' => $secondVariable,
						'item_added' => trim($thirdVariable)
					);
				} else {
					if(!empty($fifthVariable)) {
						$_SESSION[$sessionName][] = array(
							'item_id'=>$firstVariable, 
							'item_value' => trim($secondVariable),
							'item_added' => trim($thirdVariable),
							'item_name' => trim($forthVariable),
							'fifth_item' => trim($fifthVariable)
						);
					} else {
						$_SESSION[$sessionName][] = array(
							'item_id'=>$firstVariable, 
							'item_value' => trim($secondVariable),
							'item_added' => trim($thirdVariable),
							'item_name' => trim($forthVariable),
						);
					}
				}
			}
		}

		$this->session->set_userdata($sessionName, $_SESSION[$sessionName]);
	}

	public function removeSessionValue($sessionName, $itemId, $unlink = false) {

		if($this->countSessionData($sessionName)) {
			foreach($_SESSION[$sessionName] as $key => $value) {
				// confirm if the item id parsed is in the array
				if($value['item_id'] == $itemId) {
					// unset the session
				 	unset($_SESSION[$sessionName][$key]);
				 	// unlink the file
				 	if($unlink) {
				 		// confirm the file exists
				 		if(is_file($unlink.$itemId) && file_exists($unlink.$itemId)) {
				 			// delete the file
				 			unlink($unlink.$itemId);
				 		}
				 	}
				 	break;
				}				
			}
		}

	}


	
}
?>