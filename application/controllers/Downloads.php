<?php
/**
 * Downloads 
 *
 * Manages all file downloads on the server
 *
 * @package		POS
 * @subpackage	Downloads Super Class
 * @category	File Downloads
 * @author		VisamiNetSolutions Dev Team
 */

class Downloads extends Pos {

	public $file_path;
	public $file_name;

	# start the construct
	public function __construct() {
		parent::__construct();		
	}

	public function prep_download($filename, $oldname, $file_ext) {
		// assign a new random file name
		$newname = random_string('alnum', 5);
		$this->file_path = NULL;
		$this->file_name = NULL;
		
		//confirm that the file really exists 
		if(file_exists('assets/files/docs/'.$filename)) {
			
			// new file name
			$file_newname = 'assets/files/docs/'.$newname;
			$file_oldname = 'assets/files/docs/'.$oldname.".".$file_ext;

			// first copy the file to a separate folder
			copy('assets/files/docs/'.$filename, $file_newname);
			// rename the copied file
			if (!@rename($file_newname, $file_oldname)) {
				if (copy($file_newname, $file_oldname)) {
					unlink($file_newname);
				}
			}
			// return the new file to the browser to be downloaded
			$this->file_path = $file_oldname;
			// set the file to download path as a session
			$this->session->set_userdata("file_download_path", $this->file_path);
			// set the file name 
			$this->file_name = $oldname."_$newname.".$file_ext;
		}
		return $this;
	}
	
	public function force_download($dl, $path=SITE_URL) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . basename($dl) . '"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: Keep-Alive');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($dl));
		readfile($dl);
		unlink($dl);
		exit;
	}

}
?>