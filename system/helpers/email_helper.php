<?php
/**
 * Common Functions
 *
 * Loads the base classes and executes the request.
 *
 * @package		Helpers
 * @subpackage	Email Helper Functions
 * @category	Core Functions
 * @author		VisamiNetSolutions Dev Team
 */

defined('BASEPATH') OR exit('No direct script access allowed');

# GET THE RIGHT URL
load_file(
	ARRAY(
		'string_helper'=>'helpers'
	)
);

/**
 * CodeIgniter Email Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/email_helper.html
 */

// ------------------------------------------------------------------------

// --------------------------------------------------------------------

$_headers		= array();
$_charset		= 'UTF-8';
$_crlf			= "\n";
$_useragent		= config_item('site_name');

if ( ! function_exists('valid_email'))
{
	/**
	 * Validate email address
	 *
	 * @deprecated	3.0.0	Use PHP's filter_var() instead
	 * @param	string	$email
	 * @return	bool
	 */
	function valid_email($email)
	{
		return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
	}
}

function validate_email($email)
{
	$validate = load_class('form_validation', 'libraries');
	
	if ( ! is_array($email))
	{
		return FALSE;
	}

	foreach ($email as $val)
	{
		if ( ! $validate->valid_email($val))
		{
			return FALSE;
		}
	}

	return TRUE;
}

function template($temp_id = null, $subject = null, $message = null) {
	
	global $config, $followin, $_useragent;
	
	load_helpers('url_helper');
	
	// if the admin user choses to send the mail using the default template
	// display this portion
	if($temp_id == 'default') {
		
		// parse the default template 
		$template = '<section style="font-size:15px">
				<div class="email-tem" style="background: #efefef;position: relative;overflow: hidden;">
					<div class="email-tem-inn" style="width: 90%;margin: 0 auto;padding: 50px; background: #ffffff;">
						<div class="email-tem-main" style="background: #fdfdfd;box-shadow: 0px 10px 24px -10px rgba(0, 0, 0, 0.8);margin-bottom: 50px;border-radius: 10px;">
							<div class="email-tem-head" style="width: 100%;background: #006df0 url(\''.$config->base_url().'assets/images/mail/bg.png\') repeat;padding: 50px;box-sizing: border-box;border-radius: 5px 5px 0px 0px;">
								<h2 style="color: #fff;font-size: 32px;text-transform: capitalize;">
									<img style="float: left;padding-right: 25px;width: 100px;" src="'.$config->base_url().'assets/images/logo.png" alt=""> '.$subject.'
								</h2>
							</div>
							<div class="email-tem-body" style="padding: 50px;">
								'.$message.'
							</div>
						</div>
						<div class="email-tem-foot">
							<h4 style="text-align: center;">Stay in touch</h4>
							<p style="margin-bottom: 0px;padding-top: 5px;font-size: 13px;text-align: center;">Email sent by '.$_useragent.'</p>
							<p style="margin-bottom: 0px;padding-top: 5px;font-size: 13px;text-align: center;">copyrights &copy; '.date('Y').' '.$_useragent.'. All rights reserved.</p>
						</div>
					</div>
				</div>
			</section>';
	}
	
	// confirm the email template id that the user has parsed 
	if(preg_match("/^[0-9]+$/", $temp_id)) {
		
		// set the email_template_id to parse 
		$template_id = (int)$temp_id;
		
		//query the database for the id that you want to select
		$query = $followin->query("SELECT * FROM _email_templates WHERE id='$template_id' AND status='1'");
		
		// get the number of rows found
		if($followin->rowCount($query)) {
			
			// continue and fetch the template information using 
			// the foreach loop function
			foreach($query as $template) {
				
				// assign a variable to the template 
				$temp = $template['body'];
				
				// confirm that the $subject and $message variables were parsed
				if(!empty($subject) and !empty($message)) {
					// replace some items that have been listed in the
					// database template and replace it.
					$temp = str_ireplace(
						array('{subject}','{base_url}','{site_name}','{copy_date}','{message}'),
						array($subject, $config->base_url(), $_useragent, date('Y'), $message),
						$template['body']
					);
				}
				
			}
			
			// set the template to send out to the user.
			$template = auto_link($temp);
			
		} else {
			$template = 'Failed';
		}
	}
	
	return $template;
}

// ------------------------------------------------------------------------
if ( ! function_exists('message'))
{
	function message($body) {
		$_body = rtrim(str_replace("\r", '', $body));

		/* strip slashes only if magic quotes is ON
		   if we do it with magic quotes OFF, it strips real, user-inputted chars.

		   NOTE: In PHP 5.4 get_magic_quotes_gpc() will always return 0 and
			 it will probably not exist in future versions at all.
		*/
		if ( ! is_php('5.4') && get_magic_quotes_gpc())
		{
			$_body = stripslashes($_body);
		}
		
		return $_body;
	}
}


// --------------------------------------------------------------------

/**
 * Prep Q Encoding
 *
 * Performs "Q Encoding" on a string for use in email headers.
 * It's related but not identical to quoted-printable, so it has its
 * own method.
 *
 * @param	string
 * @return	string
 */
function _prep_q_encoding($str)
{
	global $_charset, $_crlf;
	
	$str = str_replace(array("\r", "\n"), '', $str);
	
	if ($_charset === 'UTF-8')
	{
		// Note: We used to have mb_encode_mimeheader() as the first choice
		//       here, but it turned out to be buggy and unreliable. DO NOT
		//       re-add it! -- Narf
		if (ICONV_ENABLED === TRUE)
		{
			$output = @iconv_mime_encode('', $str,
				array(
					'scheme' => 'Q',
					'line-length' => 76,
					'input-charset' => $_charset,
					'output-charset' => $_charset,
					'line-break-chars' => $_crlf
				)
			);

			// There are reports that iconv_mime_encode() might fail and return FALSE
			if ($output !== FALSE)
			{
				// iconv_mime_encode() will always put a header field name.
				// We've passed it an empty one, but it still prepends our
				// encoded string with ': ', so we need to strip it.
				return substr($output, 2);
			}

			$chars = iconv_strlen($str, 'UTF-8');
		}
		elseif (MB_ENABLED === TRUE)
		{
			$chars = mb_strlen($str, 'UTF-8');
		}
	}

	// We might already have this set for UTF-8
	isset($chars) OR $chars = strlen($str);

	$output = '=?'.$_charset.'?Q?';
	for ($i = 0, $length = strlen($output); $i < $chars; $i++)
	{
		$chr = ($_charset === 'UTF-8' && ICONV_ENABLED === TRUE)
			? '='.implode('=', str_split(strtoupper(bin2hex(iconv_substr($str, $i, 1, $_charset))), 2))
			: '='.strtoupper(bin2hex($str[$i]));

		// RFC 2045 sets a limit of 76 characters per line.
		// We'll append ?= to the end of each line though.
		if ($length + ($l = strlen($chr)) > 74)
		{
			$output .= '?='.$_crlf // EOL
				.' =?'.$_charset.'?Q?'.$chr; // New line
			$length = 6 + strlen($_charset) + $l; // Reset the length for the new line
		}
		else
		{
			$output .= $chr;
			$length += $l;
		}
	}

	// End the header
	return $output.'?=';
}

if ( ! function_exists('_str_to_array'))
{
	/**
	 * Convert a String to an Array
	 *
	 * @param	string
	 * @return	array
	 */
	function _str_to_array($email)
	{
		if ( ! is_array($email))
		{
			return (strpos($email, ',') !== FALSE)
				? preg_split('/[\s,]/', $email, -1, PREG_SPLIT_NO_EMPTY)
				: (array) trim($email);
		}

		return $email;
	}
}

function set_header($header, $value)
{
	global $_headers;
	
	$_headers[$header] = str_replace(array("\n", "\r"), '', $value);
	return $_headers[$header];
}

/**
 * Clean Extended Email Address: Joe Smith <joe@smith.com>
 *
 * @param	string
 * @return	string
 */
function clean_email($email)
{
	if ( ! is_array($email))
	{
		return preg_match('/\<(.*)\>/', $email, $match) ? $match[1] : $email;
	}

	$clean_email = array();

	foreach ($email as $addy)
	{
		$clean_email[] = preg_match('/\<(.*)\>/', $addy, $match) ? $match[1] : $addy;
	}

	return $clean_email;
}

function subject($subject)
{
	$subject = _prep_q_encoding($subject);
	set_header('Subject', $subject);
	return $this;
}

function _get_message_id()
{
	global $_headers;
	$from = str_replace(array('>', '<'), '', $_headers['Return-Path']);
	return '<'.uniqid('').strstr($from, '@').'>';
}
	
function _build_headers()
{
	global $_useragent, $_headers;
	set_header('User-Agent', $_useragent);
	set_header('X-Sender', clean_email($_headers['From']));
	set_header('X-Mailer', $_useragent);
	set_header('X-Priority', 1);
	set_header('Message-ID', _get_message_id());
	set_header('Mime-Version', '1.0');
	set_header('Content-Type', 'text/html; charset=ISO-8859-1');
}
	
// ------------------------------------------------------------------------
if ( ! function_exists('send_email'))
{
	
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
	function send_email($_recipients, $_subject, $_message, $name=NULL, $cc = NULL) {
		
		// send the email
		require "C:\\xampp\htdocs\\analitica_innovare\\einventory\\system\\libraries\\Phpmailer.php";
		require "C:\\xampp\htdocs\\analitica_innovare\\einventory\\system\\libraries\\Smtp.php";

		$mail = new Phpmailer();
		$smtp = new Smtp();

		$config = (Object) array(
			'subject' => $subject,
			'headers' => "From: (Evelyn POS - Analitica Innovare) <info@analiticainnovare.com> \r\n Content-type: text/html; charset=utf-8",
			'Smtp' => true,
			'SmtpHost' => 'mail.supremecluster.com',
			'SmtpPort' => '465',
			'SmtpUser' => 'apis@analiticainnovare.net',
			'SmtpPass' => 'x24Ffuz7CK',
			'SmtpSecure' => 'ssl'
		);

		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Host = $config->SmtpHost;
		$mail->SMTPAuth = true;
		$mail->Username = $config->SmtpUser;
		$mail->Password = $config->SmtpPass;
		$mail->SMTPSecure = $config->SmtpSecure;
		$mail->Port = $config->SmtpPort;

		// set the user from which the email is been sent
		$mail->setFrom('no-reply@analiticainnovare.net', 'EvelynPOS - Analitica Innovare');

		// loop through the list of recipients for this mail
		$mail->addAddress($_recipients, $name);
		
		// this is an html message
		$mail->isHTML(true);

		// set the subject and message
		$mail->Subject = $_subject;
		$mail->Body    = $_message;
		
		// send the email message to the users
		if($mail->send()) {
			return true;
		} else {
 			return false;
		}
	}
}