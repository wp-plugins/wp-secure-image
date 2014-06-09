<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
//$targetFolder = '/uploads'; // Relative to the root
//
//$verifyToken = md5('unique_salt' . $_POST['timestamp']);
//
//if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
//	$tempFile = $_FILES['Filedata']['tmp_name'];
//	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
//	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
//	
//	// Validate the file type
//	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
//	$fileParts = pathinfo($_FILES['Filedata']['name']);
//	
//	if (in_array($fileParts['extension'],$fileTypes)) {
//		move_uploaded_file($tempFile,$targetFile);
//		echo '1';
//	} else {
//		echo 'Invalid file type.';
//	}
//}
	
	
//=============================

function sanitize_file_name( $filename ) {
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
if ( !empty( $_FILES ) ) {    
    // get uploaded file informations.
    $wpsiw_file     = $_FILES['wpsiw_file'];
    $file_name      = sanitize_file_name( $wpsiw_file['name'] );
    $file_type      = $wpsiw_file['type'];
    $file_tmp_name  = $wpsiw_file['tmp_name'];
    $file_error     = $wpsiw_file['error'];
    $file_size      = $wpsiw_file['size'];
    $file_extension = pathinfo( $file_name, PATHINFO_EXTENSION );
    $upload_path    = $_POST["upload_path"] . $file_name ;
    /* old
    // there is no error, proceed
    if ( $file_error == 0 ) {
        // move uploaded file to upload directory
        if ( !move_uploaded_file( $file_tmp_name, $upload_path ) ) {
            $file_error = 7 ;
        }
    }
	*/
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
}
echo $file_error ;
?>