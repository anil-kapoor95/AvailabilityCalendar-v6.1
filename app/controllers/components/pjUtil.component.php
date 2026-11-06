<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjUtil extends pjToolkit
{
	static public function html2rgb($color)
	{
		$color = str_replace('#', '', $color);
		if (strlen($color) == 6)
		{
			list($r, $g, $b) = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
		} elseif (strlen($color) == 3) {
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		} else {
			return false;
		}
		$r = hexdec($r);
		$g = hexdec($g);
		$b = hexdec($b);
		
		return array($r, $g, $b);
	}
	
	static public function uuid()
	{
		return chr(rand(65,90)) . chr(rand(65,90)) . time();
	}
	
	static public function getClass($arr, $date, $tomorrow, $yesterday, $haystack=array())
	{
		/*
		 * 1=available
		 * 2=booked
		 * 3=pending
		 */
		
		$default = array(
				'calendarStatus1' => 'calendarStatus1',
				'calendarStatus2' => 'calendarStatus2',
				'calendarStatus3' => 'calendarStatus3',
				'calendarStatus_1_2' => 'calendarStatus_1_2',
				'calendarStatus_1_3' => 'calendarStatus_1_3',
				'calendarStatus_2_1' => 'calendarStatus_2_1',
				'calendarStatus_2_3' => 'calendarStatus_2_3',
				'calendarStatus_3_1' => 'calendarStatus_3_1',
				'calendarStatus_3_2' => 'calendarStatus_3_2',
				'calendarStatusPartial' => 'calendarStatusPartial'
		);
		
		$stack = array_merge($default, $haystack);
		
		$class = NULL;
		if (isset($arr[$date]))
		{
			if($arr[$date]['status_morning'] == 1 && $arr[$date]['status_afternoon'] == 1)
			{
				$class = $stack['calendarStatus1'];
			}else if($arr[$date]['status_morning'] == 2 && $arr[$date]['status_afternoon'] == 2){
				$class = $stack['calendarStatus2'];
			}else if($arr[$date]['status_morning'] == 3 && $arr[$date]['status_afternoon'] == 3){
				$class = $stack['calendarStatus3'];
			}else if($arr[$date]['status_morning'] == 1 && $arr[$date]['status_afternoon'] == 2){
				$class = $stack['calendarStatus_1_2'];
			}else if($arr[$date]['status_morning'] == 1 && $arr[$date]['status_afternoon'] == 3){
				$class = $stack['calendarStatus_1_3'];
			}else if($arr[$date]['status_morning'] == 2 && $arr[$date]['status_afternoon'] == 1){
				$class = $stack['calendarStatus_2_1'];
			}else if($arr[$date]['status_morning'] == 2 && $arr[$date]['status_afternoon'] == 3){
				$class = $stack['calendarStatus_2_3'];
			}else if($arr[$date]['status_morning'] == 3 && $arr[$date]['status_afternoon'] == 1){
				$class = $stack['calendarStatus_3_1'];
			}else if($arr[$date]['status_morning'] == 3 && $arr[$date]['status_afternoon'] == 2){
				$class = $stack['calendarStatus_3_2'];
			}
		}
		return $class;
	}
	
	static public function pjActionGenerateImages($cid, $data)
	{
		pjUtil::pjActionBackgroundImage(PJ_UPLOAD_PATH . $cid . '_reserved_start.jpg', str_replace('#', '', $data['o_background_available']), str_replace('#', '', $data['o_background_booked']));
		pjUtil::pjActionBackgroundImage(PJ_UPLOAD_PATH . $cid . '_reserved_end.jpg', str_replace('#', '', $data['o_background_booked']), str_replace('#', '', $data['o_background_available']));
		pjUtil::pjActionBackgroundImage(PJ_UPLOAD_PATH . $cid . '_pending_pending.jpg', str_replace('#', '', $data['o_background_pending']), str_replace('#', '', $data['o_background_pending']));
		pjUtil::pjActionBackgroundImage(PJ_UPLOAD_PATH . $cid . '_reserved_pending.jpg', str_replace('#', '', $data['o_background_booked']), str_replace('#', '', $data['o_background_pending']));
		pjUtil::pjActionBackgroundImage(PJ_UPLOAD_PATH . $cid . '_pending_reserved.jpg', str_replace('#', '', $data['o_background_pending']), str_replace('#', '', $data['o_background_booked']));
		pjUtil::pjActionBackgroundImage(PJ_UPLOAD_PATH . $cid . '_reserved_reserved.jpg', str_replace('#', '', $data['o_background_booked']), str_replace('#', '', $data['o_background_booked']));
		pjUtil::pjActionBackgroundImage(PJ_UPLOAD_PATH . $cid . '_pending_start.jpg', str_replace('#', '', $data['o_background_available']), str_replace('#', '', $data['o_background_pending']));
		pjUtil::pjActionBackgroundImage(PJ_UPLOAD_PATH . $cid . '_pending_end.jpg', str_replace('#', '', $data['o_background_pending']), str_replace('#', '', $data['o_background_available']));
	}
	
	static private function pjActionBackgroundImage($dst, $color_1, $color_2, $w=200, $h=200)
	{
		if (!extension_loaded('gd') || !function_exists('gd_info'))
		{
			return false;
		}
		# Spatial_anti-aliasing. Make an image larger then it's intended
		$width = $w * 10;
		$height = $h * 10;
		
		$image = imagecreatetruecolor($width, $height);
		if ($image === false)
		{
			return false;
		}
		if (function_exists('imageantialias'))
		{
			imageantialias($image, true);
		}
		$backgroundColor = pjUtil::html2rgb($color_1);
		$color = imagecolorallocate($image, $backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
		if ($color !== false && $color !== -1)
		{
			if (function_exists('imagefilledrectangle'))
			{
				//imagefill($image, 0, 0, $color); // this one lead to an unexpected problem, works on localhost, but not at the server
				imagefilledrectangle($image, 0, 0, $width, $height, $color);
			}
		}
		if (isset($color_2) && !empty($color_2))
		{
			if ($color_1 == $color_2)
			{
				$backgroundColor = pjUtil::html2rgb('ffffff');
				$color = imagecolorallocate($image, $backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
		
				$values = array(
						0, $height-2,
						$width-2, 0,
						$width, 0,
						$width, 1,
						1, $height,
						0, $height,
						0, $height-1
				);
				if (PHP_VERSION_ID <= 80000) {
				    imagefilledpolygon($image, $values, 7, $color);
				} else {
				    imagefilledpolygon($image, $values, $color);
				}
			} else {
				$backgroundColor = pjUtil::html2rgb($color_2);
				$color = imagecolorallocate($image, $backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
				$values = array(
						$width,  0,  // Point 1 (x, y)
						$width,  $height, // Point 2 (x, y)
						0, $height,
						$width,  0
				);
				if (PHP_VERSION_ID <= 80000) {
				    imagefilledpolygon($image, $values, 4, $color);
				} else {
				    imagefilledpolygon($image, $values, $color);
				}
			}
		}
		# Shrink it down to remove the aliasing and make it it's intended size
		$new_image = imagecreatetruecolor($w, $h);
		imagecopyresampled($new_image, $image, 0, 0, 0, 0, $w, $h, $width, $height);
		# save image
		imagejpeg($new_image, $dst, 100);
		imagedestroy($image);
		imagedestroy($new_image);
	}
}
?>