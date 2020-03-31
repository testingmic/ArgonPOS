<?php
/**
 * Common Functions
 *
 * Loads the base classes and executes the request.
 *
 * @package		Helpers
 * @subpackage	Upload Helper Functions
 * @category	Core Functions
 * @author		VisamiNetSolutions Dev Team
 */

defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('file_size_convert'))
{
	/**
	 * Converts File Sizes
	 *
	 * @param	string
	 * @return	string human readable file size (2,87 Мb)
	 * @author Mogilev Arseny 
	 */
	function file_size_convert($str, $unit = true)
	{
		$result = 0;
		$bytes = (preg_match("/^[0-9]+$/", $str)) ? $str : @filesize($str);
		$bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

		foreach($arBytes as $arItem) {
			if($bytes >= $arItem["VALUE"]) {
				$result = $bytes / $arItem["VALUE"];
				$result = str_replace(".", "." , strval(round($result, 2))).(($unit) ? $arItem["UNIT"] : "");
				break;
			}
		}
		return $result;
	}
}

if ( ! function_exists('file_size'))
{
	/**
	 * Converts File Sizes
	 *
	 * @param	string
	 * @return	string human readable file size in Kilobytes
	 * @author Emmanuel Obeng 
	 */
	function file_size($str)
	{
		$bytes = @filesize($str);
		$bytes = floatval($bytes);
        $result = strval(round(($bytes / 1024), 2));
		
		return $result;
	}
}

function create_thumbnail($source_image_path, $thumbnail_image_path, $THUMBNAIL_IMAGE_MAX_WIDTH = 230, $THUMBNAIL_IMAGE_MAX_HEIGHT = 184) {
	
	list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
	switch ($source_image_type) {
		case IMAGETYPE_GIF:
			$source_gd_image = imagecreatefromgif($source_image_path);
			break;
		case IMAGETYPE_JPEG:
			$source_gd_image = imagecreatefromjpeg($source_image_path);
			break;
		case IMAGETYPE_PNG:
			$source_gd_image = imagecreatefrompng($source_image_path);
			break;
	}
	if ($source_gd_image === false) {
		return false;
	}
	$source_aspect_ratio = $source_image_width / $source_image_height;
	$thumbnail_aspect_ratio = $THUMBNAIL_IMAGE_MAX_WIDTH / $THUMBNAIL_IMAGE_MAX_HEIGHT;
	if ($source_image_width <= $THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= $THUMBNAIL_IMAGE_MAX_HEIGHT) {
		$thumbnail_image_width = $source_image_width;
		$thumbnail_image_height = $source_image_height;
	} elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
		$thumbnail_image_width = (int) ($THUMBNAIL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
		$thumbnail_image_height = $THUMBNAIL_IMAGE_MAX_HEIGHT;
	} else {
		$thumbnail_image_width = $THUMBNAIL_IMAGE_MAX_WIDTH;
		$thumbnail_image_height = (int) ($THUMBNAIL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
	}
	$thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
	imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
	imagejpeg($thumbnail_gd_image, $thumbnail_image_path, 90);
	imagedestroy($source_gd_image);
	imagedestroy($thumbnail_gd_image);
	return true;
	
}