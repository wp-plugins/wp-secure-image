<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
/* if(strtoupper($_SERVER['HTTP_USER_AGENT']) !== strtoupper('Shockwave Flash') || $_SERVER['REQUEST_URI'] 	!== $_SERVER['PHP_SELF']) {	
	echo "8"; die;//'User Not Logged In';	
} */

require_once('../../../../../wp-load.php');
if(! is_user_logged_in() && is_admin()) {	
	echo "8";
	die;//'User Not Logged In';	
}


function wpcs_image_sanitize_file_name( $filename ) {
	$filename_raw = $filename;
	$special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", chr(0));
	$filename = str_replace($special_chars, '', $filename);
	$filename = preg_replace('/[\s-]+/', '-', $filename);
	$filename = trim($filename, '.-_');

	// Split the filename into a base and extension[s]
	$parts = explode('.', $filename);

	// Return if only one extension
	if ( count($parts) <= 2 )return $filename ;

	// Process multiple extensions
	$filename = array_shift($parts);
	$extension = array_pop($parts);
	$mimes = get_allowed_mime_types();

	// Loop over any intermediate extensions. Munge them with a trailing underscore if they are a 2 - 5 character
	// long alpha string not in the extension whitelist.
	foreach ( (array) $parts as $part) {
		$filename .= '.' . $part;

		if ( preg_match("/^[a-zA-Z]{2,5}\d?$/", $part) ) {
			$allowed = false;
			foreach ( $mimes as $ext_preg => $mime_match ) {
				$ext_preg = '!^(' . $ext_preg . ')$!i';
				if ( preg_match( $ext_preg, $part ) ) {
					$allowed = true;
					break;
				}
			}
			if ( !$allowed )
				$filename .= '_';
		}
	}
	$filename .= '.' . $extension;

	return $filename ;
}

$file_error = -1 ;
$verifyToken = md5('unique_salt' . $_POST['token_timestamp']);

$token_session = $_POST['token'];
list($token,$session_id) = explode("-",$token_session);

if(! empty($session_id) ) {
  session_id($session_id);
}
if (! empty($_FILES) && $token == $verifyToken) {
	session_start();
	$is_user_logged_in = $_SESSION['is_user_logged_in'];

	if( ! empty($session_id) ) {
    // get uploaded file informations.
    $wpsiw_file     = $_FILES['wpsiw_file'];
    $file_name      = wpcs_image_sanitize_file_name( $wpsiw_file['name'] );
    $file_type      = $wpsiw_file['type'];
    $file_tmp_name  = $wpsiw_file['tmp_name'];
    $file_error     = $wpsiw_file['error'];
    $file_size      = $wpsiw_file['size'];
    $file_extension = pathinfo( $file_name, PATHINFO_EXTENSION );
    $upload_path    = $_POST["upload_path"] . $file_name ;
	$fileTypes = array('class');
	if (in_array($file_extension,$fileTypes)) {
		if ( $file_error == 0 ) {
			if ( !move_uploaded_file( $file_tmp_name, $upload_path ) ) {
				$file_error = 7 ;
			}
		}
	} else {
		$file_error = 7 ;//'Invalid file type.';
    }
	} else {
		$file_error = 8 ;//'User Not Logged In';
    }
}
echo $file_error ;
?>