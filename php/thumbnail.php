<?php

private function generate_thumbnail($file)
{
	$valid_file = preg_match('/\/(\w)+.(jpg|jpeg|png|gif)$/i', strtolower($file));
	if (!$valid_file || !file_exists('.' . $file)) {
		return FALSE;
	}

	$pos = strrpos($file, '/') + 1;
	$org_file = substr($file, $pos);
	$uploaded_dir = substr($file, 0, $pos);

	$ext = strtolower(substr($org_file, strrpos($org_file, '.') + 1));
	$file_name = str_replace(".{$ext}", '', $org_file);

	if (!$file_name || !$org_file || !$ext) {
		return FALSE;
	}

	list($width, $height) = getimagesize('.' . $file);

	$width_r = ($width * ($width > $height ? 1.5 : 2)) / 400;
	$height_r = ($height * ($width < $height ? 1.5 : 2)) / 400;

	$new_w = $width / $width_r;
	$new_h = $height / $height_r;

	$src = '';
	switch ($ext) {
		case 'jpg':
		case 'jpeg':
			$src = imagecreatefromjpeg('.' . $file);
			break;
		case 'png':
			$src = imagecreatefrompng('.' . $file);
			break;
		case 'gif':
			$src = imagecreatefromgif('.' . $file);
			break;
	}

	if (!$src) {
		return FALSE;
	}

	$dst = imagecreatetruecolor($new_h, $new_h);
	imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_w, $new_h, $width, $height);

	$thumbnail = ".{$uploaded_dir}{$file_name}-thumbnail.{$ext}";
	switch ($ext) {
		case 'jpg':
		case 'jpeg':
			imagejpeg($dst, $thumbnail);
			break;
		case 'png':
			imagepng($dst, $thumbnail);
			break;
		case 'gif':
			imagegif($dst, $thumbnail);
			break;
		default:
			imagejpeg($dst, $thumbnail);
			break;
	}

	return TRUE;

}