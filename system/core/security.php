<?php
/**
 * Common Functions
 *
 * Loads the base classes and executes the request.
 *
 * @package		POS
 * @subpackage	POS Super Class
 * @category	Core Functions
 * @author		VisamiNetSolutions Dev Team
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * List of sanitize filename strings
 *
 * @var	array
 */
$filename_bad_chars =	array(
	'../', '<!--', '-->', '<', '>',
	"'", '"', '&', '$', '#',
	'{', '}', '[', ']', '=',
	';', '?', '%20', '%22',
	'%3c',		// <
	'%253c',	// <
	'%3e',		// >
	'%0e',		// >
	'%28',		// (
	'%29',		// )
	'%2528',	// (
	'%26',		// &
	'%24',		// $
	'%3f',		// ?
	'%3b',		// ;
	'%3d'		// =
);

/**
 * List of never allowed strings
 *
 * @var	array
 */
$_never_allowed_str =	array(
	'document.cookie' => '[removed]',
	'(document).cookie' => '[removed]',
	'document.write'  => '[removed]',
	'(document).write'  => '[removed]',
	'.parentNode'     => '[removed]',
	'.innerHTML'      => '[removed]',
	'-moz-binding'    => '[removed]',
	'<!--'            => '&lt;!--',
	'-->'             => '--&gt;',
	'<![CDATA['       => '&lt;![CDATA[',
	'<comment>'	  => '&lt;comment&gt;',
	'<%'              => '&lt;&#37;'
);

$_xss_hash = $ip_address = NULL;

$charset = 'UTF-8';
$_csrf_expire =	7200;
$_csrf_token_name =	'ci_csrf_token';
$_csrf_cookie_name =	'ci_csrf_token';

/**
 * List of never allowed regex replacements
 *
 * @var	array
 */
$_never_allowed_regex = array(
	'javascript\s*:',
	'(\(?document\)?|\(?window\)?(\.document)?)\.(location|on\w*)',
	'expression\s*(\(|&\#40;)', // CSS and IE
	'vbscript\s*:', // IE, surprise!
	'wscript\s*:', // IE
	'jscript\s*:', // IE
	'vbs\s*:', // IE
	'Redirect\s+30\d',
	"([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"
);

// --------------------------------------------------------------------

/**
 * Is ASCII?
 *
 * Tests if a string is standard 7-bit ASCII or not.
 *
 * @param	string	$str	String to check
 * @return	bool
 */
function is_ascii($str) {
	return (preg_match('/[^\x00-\x7F]/S', $str) === 0);
}

// --------------------------------------------------------------------

if ( ! function_exists('remove_invisible_characters'))
{
	/**
	 * Remove Invisible Characters
	 *
	 * This prevents sandwiching null characters
	 * between ascii characters, like Java\0script.
	 *
	 * @param	string
	 * @param	bool
	 * @return	string
	 */
	function remove_invisible_characters($str, $url_encoded = TRUE)
	{
		$non_displayables = array();

		// every control character except newline (dec 10),
		// carriage return (dec 13) and horizontal tab (dec 09)
		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/i';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/i';	// url encoded 16-31
			$non_displayables[] = '/%7f/i';	// url encoded 127
		}

		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}
}

/**
 * Strip Image Tags
 *
 * @param	string	$str
 * @return	string
 */
function strip_image_tags($str)
{
	return preg_replace(
		array(
			'#<img[\s/]+.*?src\s*=\s*(["\'])([^\\1]+?)\\1.*?\>#i',
			'#<img[\s/]+.*?src\s*=\s*?(([^\s"\'=<>`]+)).*?\>#i'
		),
		'\\2',
		$str
	);
}


// ------------------------------------------------------------------------

if ( ! function_exists('html_escape'))
{
	/**
	 * Returns HTML escaped variable.
	 *
	 * @param	mixed	$var		The input string or array of strings to be escaped.
	 * @param	bool	$double_encode	$double_encode set to FALSE prevents escaping twice.
	 * @return	mixed			The escaped string or array of strings as a result.
	 */
	function html_escape($var, $double_encode = TRUE)
	{
		if (empty($var))
		{
			return $var;
		}

		if (is_array($var))
		{
			foreach (array_keys($var) as $key)
			{
				$var[$key] = html_escape($var[$key], $double_encode);
			}

			return $var;
		}

		return htmlspecialchars($var, ENT_QUOTES, config_item('charset'), $double_encode);
	}
}


// ------------------------------------------------------------------------

if ( ! function_exists('_stringify_attributes'))
{
	/**
	 * Stringify attributes for use in HTML tags.
	 *
	 * Helper function used to convert a string, array, or object
	 * of attributes to a string.
	 *
	 * @param	mixed	string, array, object
	 * @param	bool
	 * @return	string
	 */
	function _stringify_attributes($attributes, $js = FALSE)
	{
		$atts = NULL;

		if (empty($attributes))
		{
			return $atts;
		}

		if (is_string($attributes))
		{
			return ' '.$attributes;
		}

		$attributes = (array) $attributes;

		foreach ($attributes as $key => $val)
		{
			$atts .= ($js) ? $key.'='.$val.',' : ' '.$key.'="'.$val.'"';
		}

		return rtrim($atts, ',');
	}
}

// --------------------------------------------------------------------

/**
 * Sanitize Filename
 *
 * @param	string	$str		Input file name
 * @param 	bool	$relative_path	Whether to preserve paths
 * @return	string
 */
function sanitize_filename($str, $relative_path = FALSE)
{
	global $filename_bad_chars;
	
	$bad = $filename_bad_chars;

	if ( ! $relative_path)
	{
		$bad[] = './';
		$bad[] = '/';
	}

	$str = remove_invisible_characters($str, FALSE);

	do
	{
		$old = $str;
		$str = str_replace($bad, '', $str);
	}
	while ($old !== $str);

	return stripslashes($str);
}

/**
 * Do Never Allowed
 *
 * @used-by	Security::xss_clean()
 * @param 	string
 * @return 	string
 */
function _do_never_allowed($str)
{
	global $_never_allowed_str, $_never_allowed_regex;
	
	$str = @str_replace(array_keys($_never_allowed_str), $_never_allowed_str, $str);

	foreach ($_never_allowed_regex as $regex)
	{
		$str = preg_replace('#'.$regex.'#is', '[removed]', $str);
	}

	return @$str;
}


/**
 * Sanitize Naughty HTML
 *
 * Callback method for xss_clean() to remove naughty HTML elements.
 *
 * @used-by	CI_Security::xss_clean()
 * @param	array	$matches
 * @return	string
 */
function _sanitize_naughty_html($matches)
{
	static $naughty_tags    = array(
		'alert', 'area', 'prompt', 'confirm', 'applet', 'audio', 'basefont', 'base', 'behavior', 'bgsound',
		'blink', 'body', 'embed', 'expression', 'form', 'frameset', 'frame', 'head', 'html', 'ilayer',
		'iframe', 'input', 'button', 'select', 'isindex', 'layer', 'link', 'meta', 'keygen', 'object',
		'plaintext', 'style', 'script', 'textarea', 'title', 'math', 'video', 'svg', 'xml', 'xss'
	);

	static $evil_attributes = array(
		'on\w+', 'style', 'xmlns', 'formaction', 'form', 'xlink:href', 'FSCommand', 'seekSegmentTime'
	);

	// First, escape unclosed tags
	if (empty($matches['closeTag']))
	{
		return '&lt;'.$matches[1];
	}
	// Is the element that we caught naughty? If so, escape it
	elseif (in_array(strtolower($matches['tagName']), $naughty_tags, TRUE))
	{
		return '&lt;'.$matches[1].'&gt;';
	}
	// For other tags, see if their attributes are "evil" and strip those
	elseif (isset($matches['attributes']))
	{
		// We'll store the already filtered attributes here
		$attributes = array();

		// Attribute-catching pattern
		$attributes_pattern = '#'
			.'(?<name>[^\s\042\047>/=]+)' // attribute characters
			// optional attribute-value
			.'(?:\s*=(?<value>[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*)))' // attribute-value separator
			.'#i';

		// Blacklist pattern for evil attribute names
		$is_evil_pattern = '#^('.implode('|', $evil_attributes).')$#i';

		// Each iteration filters a single attribute
		do
		{
			// Strip any non-alpha characters that may precede an attribute.
			// Browsers often parse these incorrectly and that has been a
			// of numerous XSS issues we've had.
			$matches['attributes'] = preg_replace('#^[^a-z]+#i', '', $matches['attributes']);

			if ( ! preg_match($attributes_pattern, $matches['attributes'], $attribute, PREG_OFFSET_CAPTURE))
			{
				// No (valid) attribute found? Discard everything else inside the tag
				break;
			}

			if (
				// Is it indeed an "evil" attribute?
				preg_match($is_evil_pattern, $attribute['name'][0])
				// Or does it have an equals sign, but no value and not quoted? Strip that too!
				OR (trim($attribute['value'][0]) === '')
			)
			{
				$attributes[] = 'xss=removed';
			}
			else
			{
				$attributes[] = $attribute[0][0];
			}

			$matches['attributes'] = substr($matches['attributes'], $attribute[0][1] + strlen($attribute[0][0]));
		}
		while ($matches['attributes'] !== '');

		$attributes = empty($attributes)
			? ''
			: ' '.implode(' ', $attributes);
		return '<'.$matches['slash'].$matches['tagName'].$attributes.'>';
	}

	return $matches[0];
}

function xss_hash()
{
	global $_xss_hash;
	
	load_helpers('string_helper');
	
	if ($_xss_hash === NULL)
	{
		$rand = get_random_bytes(16);
		$_xss_hash = ($rand === FALSE)
			? md5(uniqid(mt_rand(), TRUE))
			: bin2hex($rand);
	}

	return $_xss_hash;
}
	
function _compact_exploded_words($matches) {
	return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
}

function _convert_attribute($match) {
	return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
}

function _filter_attributes($str) {
	$out = '';
	if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches))
	{
		foreach ($matches[0] as $match)
		{
			$out .= preg_replace('#/\*.*?\*/#s', '', $match);
		}
	}

	return $out;
}
	
function _js_link_removal($match)
{
	return str_replace(
		$match[1],
		preg_replace(
			'#href=.*?(?:(?:alert|prompt|confirm)(?:\(|&\#40;|`|&\#96;)|javascript:|livescript:|mocha:|charset=|window\.|\(?document\)?\.|\.cookie|<script|<xss|d\s*a\s*t\s*a\s*:)#si',
			'',
			_filter_attributes($match[1])
		),
		$match[0]
	);
}

function _js_img_removal($match)
{
	return str_replace(
		$match[1],
		preg_replace(
			'#src=.*?(?:(?:alert|prompt|confirm|eval)(?:\(|&\#40;|`|&\#96;)|javascript:|livescript:|mocha:|charset=|window\.|\(?document\)?\.|\.cookie|<script|<xss|base64\s*,)#si',
			'',
			_filter_attributes($match[1])
		),
		$match[0]
	);
}


function entity_decode($str, $charset = NULL)
{
	if (strpos($str, '&') === FALSE)
	{
		return $str;
	}

	isset($charset) OR $charset = $this->charset;
	$flag = is_php('5.4')
		? ENT_COMPAT | ENT_HTML5
		: ENT_COMPAT;

	if ( ! isset($_entities))
	{
		$_entities = array_map('strtolower', get_html_translation_table(HTML_ENTITIES, $flag, $charset));

		// If we're not on PHP 5.4+, add the possibly dangerous HTML 5
		// entities to the array manually
		if ($flag === ENT_COMPAT)
		{
			$_entities[':'] = '&colon;';
			$_entities['('] = '&lpar;';
			$_entities[')'] = '&rpar;';
			$_entities["\n"] = '&NewLine;';
			$_entities["\t"] = '&Tab;';
		}
	}

	do
	{
		$str_compare = $str;

		// Decode standard entities, avoiding false positives
		if (preg_match_all('/&[a-z]{2,}(?![a-z;])/i', $str, $matches))
		{
			$replace = array();
			$matches = array_unique(array_map('strtolower', $matches[0]));
			foreach ($matches as &$match)
			{
				if (($char = array_search($match.';', $_entities, TRUE)) !== FALSE)
				{
					$replace[$match] = $char;
				}
			}

			$str = str_replace(array_keys($replace), array_values($replace), $str);
		}

		// Decode numeric & UTF16 two byte entities
		$str = html_entity_decode(
			preg_replace('/(&#(?:x0*[0-9a-f]{2,5}(?![0-9a-f;])|(?:0*\d{2,4}(?![0-9;]))))/iS', '$1;', $str),
			$flag,
			$charset
		);

		if ($flag === ENT_COMPAT)
		{
			$str = str_replace(array_values($_entities), array_keys($_entities), $str);
		}
	}
	while ($str_compare !== $str);
	return $str;
}

function _decode_entity($match) {
	
	global $charset;
	
	// Protect GET variables in URLs
	// 901119URL5918AMP18930PROTECT8198
	$match = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-/]+)|i', xss_hash().'\\1=\\2', $match[0]);

	// Decode, then un-protect URL GET vars
	return str_replace(
		xss_hash(),
		'&',
		entity_decode($match, $charset)
	);
}


function _urldecodespaces($matches) {
	$input    = $matches[0];
	$nospaces = preg_replace('#\s+#', '', $input);
	return ($nospaces === $input)
		? $input
		: rawurldecode($nospaces);
}
	
/*

 * XSS filter 
 *
 * This was built from numerous sources
 * (thanks all, sorry I didn't track to credit you)
 * 
 * It was tested against *most* exploits here: http://ha.ckers.org/xss.html
 * WARNING: Some weren't tested!!!
 * Those include the Actionscript and SSI samples, or any newer than Jan 2011
 *
 * TO-DO: compare to SymphonyCMS filter:
 * https://github.com/symphonycms/xssfilter/blob/master/extension.driver.php
 * (Symphony's is probably faster than my hack)
 *
 * @param	string|string[]	$str	Input data
 * @param 	bool		$is_image	Whether the input is an image
 * @return	string
 */


function xss_clean($str) {
	
	// Remove Invisible Characters
	// This prevents sandwiching null characters
	// between ascii characters, like Java\0script.
	remove_invisible_characters($str);
	
	$str = filter_var($str, FILTER_SANITIZE_STRING);
	
	// Fix &entity\n;
	$str = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $str);
	$str = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $str);
	
	$str = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $str);
	$str = html_entity_decode($str, ENT_COMPAT, 'UTF-8');
	
	// Remove any attribute starting with "on" or xmlns
	$str = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $str);
	
	// Remove javascript: and vbscript: protocols
	$str = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $str);
	
	$str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $str);
	
	$str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $str);
	
	// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
	$str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $str);
	
	$str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $str);
	
	$str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $str);
	
	// Remove namespaced elements (we do not need them)
	$str = preg_replace('#</*\w+:\w[^>]*+>#i', '', $str);
	
	do {
		// Remove really unwanted tags
		$old_data = $str;
		
		$str = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $str);
	} while ($old_data !== $str);
	
	// we are done...
	return $str;
}


/**
 * Set cookie
 *
 * Accepts an arbitrary number of parameters (up to 7) or an associative
 * array in the first parameter containing all the values.
 *
 * @param	string|mixed[]	$name		Cookie name or an array containing parameters
 * @param	string		$value		Cookie value
 * @param	int		$expire		Cookie expiration time in seconds
 * @param	string		$domain		Cookie domain (e.g.: '.yourdomain.com')
 * @param	string		$path		Cookie path (default: '/')
 * @param	string		$prefix		Cookie name prefix
 * @param	bool		$secure		Whether to only transfer cookies via SSL
 * @param	bool		$httponly	Whether to only makes the cookie accessible via HTTP (no javascript)
 * @return	void
 */
function set_cookie($name, $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = NULL, $httponly = NULL)
{
	if (is_array($name))
	{
		// always leave 'name' in last place, as the loop will break otherwise, due to $$item
		foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'httponly', 'name') as $item)
		{
			if (isset($name[$item]))
			{
				$$item = $name[$item];
			}
		}
	}

	if ($prefix === '' && config_item('cookie_prefix') !== '')
	{
		$prefix = config_item('cookie_prefix');
	}

	if ($domain == '' && config_item('cookie_domain') != '')
	{
		$domain = config_item('cookie_domain');
	}

	if ($path === '/' && config_item('cookie_path') !== '/')
	{
		$path = config_item('cookie_path');
	}

	$secure = ($secure === NULL && config_item('cookie_secure') !== NULL)
		? (bool) config_item('cookie_secure')
		: (bool) $secure;

	$httponly = ($httponly === NULL && config_item('cookie_httponly') !== NULL)
		? (bool) config_item('cookie_httponly')
		: (bool) $httponly;

	if ( ! is_numeric($expire))
	{
		$expire = time() - 86500;
	}
	else
	{
		$expire = ($expire > 0) ? time() + $expire : 0;
	}

	setcookie($prefix.$name, $value, $expire, $path, $domain, $secure, $httponly);
}

/**
* Fetch the IP Address
*
* Determines and validates the visitor's IP address.
*
* @return	string	IP address
*/

function valid_ip($ip) {
	if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
		return false;
	}
	return true;
}

function ip_address() {
	$ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
	foreach ($ip_keys as $key) {
		if (array_key_exists($key, $_SERVER) === true) {
			foreach (explode(',', $_SERVER[$key]) as $ip_address) {
				// trim for safety measures
				$ip_address = trim($ip_address);
				// attempt to validate IP
				if (valid_ip($ip_address)) {
					return $ip_address;
				}
			}
		}
	}
	return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}

#CREATE A SIMPLE FUNCTION TO RUN A TEST ON USER PASSWORD
function passwordTest($password) {
	if(strlen($password) < 8) {
		return false;
	} elseif(preg_match("/^[a-z]+$/", $password)) {
		return false;
	} elseif(preg_match("/^[A-Z]+$/", $password)) {
		return false;
	} elseif(preg_match("/^[a-zA-Z]+$/", $password)) {
		return false;
	} elseif(preg_match("/^[0-9A-Z]+$/", $password)) {
		return false;
	} elseif(preg_match("/^[0-9a-z]+$/", $password)) {
		return false;
	} elseif(preg_match("/^[0-9]+$/", $password)) {
		return false;
	} else {
		return true;
	}
}

function valid_date($date) { 
    if (false === strtotime($date)) { 
        return false;
    } 
    list($year, $month, $day) = explode('-', $date); 
    return checkdate($month, $day, $year);
}
?>