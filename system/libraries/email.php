<?php
/**
 * Common Functions
 *
 * Loads the base classes and executes the request.
 *
 * @package		Libraries
 * @subpackage	Emails
 * @category	Core Functions
 * @author		VisamiNetSolutions Dev Team
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Email {


	/**
	 * __construct() begins to run as soon as it is
	 * initiated by the user upon a call.
	 * @return		returns some important global instances needed in all part of the class 
	 */
	public function __construct() {
		
		global $session, $DB;
		$this->db = $DB;
		$this->session = $session;
		
	}
	
	/**
	 * Send an email
	 *
	 * @deprecated	3.0.0	Use PHP's mail() instead
	 * @param	array 	$to
	 * @param	string	$subject
	 * @param	string	$message
	 * @param	string	$name
	 * @param	string	$from
	 * @param	array	$cc
	 * @param	int		$temp_id
	 * @param	(bool)	$save_copy	default=true
	 * @return	(bool)
	 */
	public function send_mail($to, $_subject, $_message, $name=NULL, $from, $cc = NULL, $temp_id = 'default', $save_copy= true) {
		
		return (bool) send_email($to, $_subject, $_message, $name, $from, $cc, $temp_id, $save_copy);
	}
	
	/**
	 * Validate Email
	 *
	 * Confirms that the email value parsed is a valid email address
	 * 
	 * @param 	string	$email
	 * @param	(bool)
	 *
	 */	 
	public function validate_email($email) {
		 
		return (bool) validate_email($email);

	}
	
	/**
	 * Template
	 * 
	 * Uses the parsed Template ID and fetches the template from the database
	 * 
	 * @param int	$template_id
	 * @return 	string 	$str
	 *
	 */
	public function template($template_id) {
		
		return (string) template($template_id);
		
	}
	
	/**
	 * Add Template
	 * 
	 * @param string $title
	 * @param string $body
	 * @param int $status 	default=1
	 * @return (bool)
	 *
	 */
	public function add_template($title, $body, $status = 1) {
		
		return (bool) $this->db->touch(TEMPLATE_TABLE, 
			array(
				'title'=>$title, 'body'=>$body, 'status'=>$status, 
				'added_by'=>$this->session->admin_username,
				'modified_by'=>$this->session->admin_username
			), 
			NULL, 'INSERT'
		);
		
	}
	
	/**
	 * Update Template
	 * 
	 * @param int $template_id
	 * @param string $title
	 * @param string $body
	 * @param int $status 	default=1
	 * @return (bool)
	 *
	 */
	public function update_template($template_id, $title, $body, $status) {
		
		return (bool) $this->db->touch(TEMPLATE_TABLE, 
			array(
				'title'=>$title, 'body'=>$body, 'status'=>$status, 
				'modified_by'=>$this->session->admin_username,
				'modified_date'=>'now()', 'status'=>$status
			), 
			array(
				'id'=>(int)$template_id				
			), 'UPDATE'
		);
	}
	
	/**
	 * Custom Update Template
	 * 
	 * @param int $template_id
	 * @param array $data
	 * @return (bool)
	 *
	 */
	public function custom_update($template_id, $data) {
		
		return (bool) $this->db->touch(TEMPLATE_TABLE, $data, 
			array(
				'id'=>(int)$template_id				
			), 'UPDATE'
		);
	}
	
	/**
	 * Add Email
	 * 
	 * @param string $receipient
	 * @param string $user_id
	 * @param string $sender
	 * @param string $subject
	 * @param string $body
	 * @param string / int $temp_id default=default
	 * @param int $status 	default=1
	 * @return (bool)
	 *
	 */
	public function add_email($receipient, $user_id, $sender, $subject, $body, $temp_id='default', $_header_str) {
		
		return (bool) $this->db->touch(EMAIL_TABLE, 
			array(
				'send_to'=>$receipient, 'user_id'=>$user_id, 'sent_from'=>$sender, 
				'subject'=>$subject, 'body'=>$body, 'template'=>$temp_id,
				'sent_by'=>$sender, 'headers'=>$_header_str
			), 
			NULL, 'INSERT'
		);
		
	}
	
} 