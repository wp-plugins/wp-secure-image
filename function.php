<?php
function wpsiw_ajaxprocess(){
	if( $_POST["fucname"] == "file_upload" ){
		$msg = wpsiw_file_upload($_POST) ;
		$upload_list = get_wpsiw_uploadfile_list() ;
		$data = array(
					"message" => $msg, 
					"list" => $upload_list
				) ;		
		echo json_encode($data) ;
	}
	
	if( $_POST["fucname"] == "file_search" ){
		$data = wpsiw_file_search($_POST) ;
		echo $data ;
	}
	
	if( $_POST["fucname"] == "setting_save" ){
		$data = wpsiw_setting_save($_POST) ;
		echo $data ;
	}
	
	if( $_POST["fucname"] == "get_parameters" ){
		$data = wpsiw_sget_parameters($_POST) ;
		echo $data ;
	}
	exit() ;
}

function wpsiw_sget_parameters($param){
	$postid = $_POST["post_id"] ;
	$filename = trim($_POST["filename"]) ;
	$settings = get_first_class_settings() ;
	
	$options = get_option("wpsiw_settings") ;	
	if($options["classsetting"][$postid][$filename]){
		$settings = wp_parse_args( $options["classsetting"][$postid][$filename], $default_settings );
	}
	
	extract( $settings ) ;
	
	$java_check = ($java_check) ? 1 : 0 ;
	$key_safe = ($key_safe) ? 1 : 0 ;
		
	$params = 	" border='" . $border . "'" . 
				" border_color='" . $border_color . "'" .
				" java_check='" . $java_check . "'" . 
				" key_safe='" . $key_safe . "'" .
				" text_color='" . $text_color . "'" . 
				" loading_message='" . $loading_message . "'" . 
				" hyperlink='" . $hyperlink . "'" .	 
				" target='" . $target . "'" ;
	return $params ;
}

function get_first_class_settings(){
	$settings = array(
//				'mode'            => '',
//				'plugin_folder'   => WPSIW_PLUGIN_PATH . "secure-image/",
				'java_check'      => 0,
				'key_safe'        => 0,
//				'ie'			  => 'checked',
//				'ff'			  => 'checked',	
//				'ch'			  => 'checked',
//				'sa'			  => 'checked',						
//				'op'		 	  => 'checked',				
	            'border'          => 0,
	            'border_color'    => '000000',
	            'text_color'      => 'FFFFFF',
	            'loading_message' => 'Image loading...',
	            'hyperlink'       => '',
		     	'target'          => "_top",			         
			) ;
	return 	$settings ;	
}

function wpsiw_file_upload($param){
	$file_error 	= $param["error"] ;  
	$file_errors = array( 0 => __( "There is no error, the file uploaded with success" ),
                          1 => __( "The uploaded file exceeds the upload_max_filesize directive in php.ini" ),
                          2 => __( "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form" ),
                          3 => __( "The uploaded file was only partially uploaded" ),
                          4 => __( "No file was uploaded" ),
                          6 => __( "Missing a temporary folder" ),
                          7 => __( "Upload directory is not writable" )
                   );
                   
	if ( $file_error == 0 ){
		$msg = '<div class="updated"><p><strong>'.__('File Uploaded. You must save "File Details" to insert post').'</strong></p></div>';
	}else{
		$msg = '<div class="error"><p><strong>'.__('Error').'!</strong></p><p>'.$file_errors[$file_error].'</p></div>';
	}
    return $msg ;
}

function wpsiw_file_search($param){
	// get selected file details
	if (@!empty($param['search']) && @!empty($param['post_id'])) {
    	
		$postid = $param['post_id'] ;
		$search = trim($param["search"]);
    	
		$files = _get_wpsiw_uploadfile_list() ;

    	$result = false ;
    	foreach ($files as $file)
    		if( $search == trim($file["filename"]) )$result = true ;
    	    	
		if( !$result )return "<hr /><h2>No found file</h2>" ;
				
		$file_options  = array (
	                        'border'          => '0',
	                        'border_color'    => '000000',
	                        'text_color'      => 'FFFFFF',
	                        'loading_message' => 'Image loading...',
	                        'key_safe'        => '1',
	                        'java_check'      => '0',
	                        'hyperlink'       => '',
	                        'target'          => '_top',
	                    );
	                    
	    $wpsiw_options = get_option( 'wpsiw_settings' );
	    if( $wpsiw_options["classsetting"][$postid][$search] )
	    	$file_options = $wpsiw_options["classsetting"][$postid][$search] ;
	    
		extract( $file_options, EXTR_OVERWRITE );
	    $str = "<hr />
	    		<div class='icon32' id='icon-file'><br /></div>
		        <h2>Class Settings</h2>
		        <div>
	    			<table cellpadding='0' cellspacing='0' border='0' >
	  					<tbody id='wpsiw_setting_body'> 
							  <tr>
							    <td align='left' width='50'>&nbsp;</td>
							    <td align='left' width='40'><img src='" . WPSIW_PLUGIN_URL . "images/help-24-30.png' border='0' alt='Border thickness in pixels. For no border set 0.' /></td>
							    <td align='left'>Border:</td>
							    <td> 
							      <input name='border' id='wpsiw_border' type='text' value='$border' size='3' />
							    </td>
							  </tr>
							  <tr>
							    <td align='left'>&nbsp;</td>
							    <td align='left'><img src='" . WPSIW_PLUGIN_URL . "images/help-24-30.png' border='0' alt='Color of the border and image backround area. For example use FFFFFF for white and 000000 is for black... without the # symbol.' /></td>
							    <td align='left'>Border color:</td>
							    <td> 
							      <input name='border_color' id='wpsiw_border_color' type='text' value='$border_color' size='7' />
							    </td>
							  </tr>
							  <tr>
							    <td align='left'>&nbsp;</td>
							    <td align='left'><img src='" . WPSIW_PLUGIN_URL . "images/help-24-30.png' border='0' alt='Color of the text message that is displayed in the image area sas the image downloads.' /></td>
							    <td align='left'>Text color:</td>
							    <td> 
							      <input name='text_color' id='wpsiw_text_color' type='text' value='$text_color' size='7' />
							    </td>
							  </tr>
							  <tr>
							    <td align='left'>&nbsp;</td>
							    <td align='left'><img src='" . WPSIW_PLUGIN_URL . "images/help-24-30.png' border='0' alt='Check this box to disable use of the keyboard when the class image loads.' /></td>
							    <td align='left'>KeySafe:</td>
							    <td> 
							      <input name='key_safe' id='wpsiw_key_safe' type='checkbox' value='1' $key_safe>
							    </td>
							  </tr>
							  <tr>
							    <td align='left'>&nbsp;</td>
							    <td align='left'><img src='" . WPSIW_PLUGIN_URL . "images/help-24-30.png' border='0' onClick='MM_popupMsg('Check this box to add Java version detection to teh page.')' /></td>
							    <td align='left'>Java Check:</td>
							    <td> 
							      <input name='java_check' id='wpsiw_java_check' type='checkbox' value='1' $java_check />
							    </td>
							  </tr>
							  <tr>
							    <td align='left'>&nbsp;</td>
							    <td align='left'><img src='" . WPSIW_PLUGIN_URL . "images/help-24-30.png' border='0' alt='Set the message to display as this class image loads.' /></td>
							    <td align='left'>Loading message:&nbsp;</td>
							    <td> 
							      <input name='loading_message' id='wpsiw_loading_message' type='text' value='$loading_message' size='30' />
							    </td>
							  </tr>
							  <tr>
							    <td align='left'>&nbsp;</td>
							    <td align='left'><img src='" . WPSIW_PLUGIN_URL . "images/help-24-30.png' border='0' alt='Add a link to another page activated by clciking on the image, or leave blank for no link.' /></td>
							    <td align='left'>Hyperlink:</td>
							    <td> 
							      <input value='$hyperlink' name='hyperlink' id='wpsiw_hyperlink' type='text' size='30' />
							    </td>
							  </tr>
							  <tr>
							    <td align='left'>&nbsp;</td>
							    <td align='left'><img src='" . WPSIW_PLUGIN_URL . "images/help-24-30.png' border='0' alt='Set the target frame for the hyperlink, for example _top' /></td>
							    <td align='left'>Target frame:</td>
							    <td> 
							      <input value='$target' name='target' id='wpsiw_target' type='text' size='15' />
							    </td>
							  </tr>
						</tbody> 
					</table>
			        <p class='submit'>
			            <input type='button' value='Save' class='button-primary' id='setting_save' name='submit' />
			            <input type='button' value='Cancel' class='button-primary' id='cancel' />
			        </p>
        	</div>" ;
		return $str ;
	}
}

function wpsiw_setting_save($param){
	$postid = $param["post_id"] ;
	$name = trim($param["nname"]) ;
	$data = (array)json_decode(stripcslashes($param["set_data"])) ;
	// escape user inputs
    $data = array_map( "esc_attr", $data );
    extract( $data );
	
    $border          = ( empty( $border ) ? '0' : esc_attr( $border ) );
    $border_color    = ( empty( $border_color) ? '' : esc_attr( $border_color ) );
    $text_color      = ( empty( $text_color ) ? '' : esc_attr( $text_color ) );
    $loading_message = ( empty( $loading_message ) ? '' : esc_attr( $loading_message ) );
	
    $wpsiw_settings = get_option( 'wpsiw_settings' ); 
	if(!is_array($wpsiw_settings))$wpsiw_settings = array() ; 
	
	$datas = array ('border'          => "$border",
                    'border_color'    => "$border_color",
                    'text_color'      => "$text_color",
                    'loading_message' => "$loading_message",
                    'key_safe'        => "$key_safe",
                    'java_check'      => "$java_check",
                    'hyperlink'       => "$hyperlink",
                    'target'          => "$target",
                    'postid'          => "$postid",
                    'name'            => "$name"
             );
             
    
   	$wpsiw_settings["classsetting"][$postid][$name] = $datas ;    
        
    update_option( 'wpsiw_settings', $wpsiw_settings );

    $msg = '<div class="updated fade">
    			<strong>'.__('File Options Are Saved').'</strong><br />
    			<div style="margin-top:5px;"><a href="#" alt="'.$name.'" class="button-secondary sendtoeditor"><strong>Insert file to editor</strong></a></div>
		    </div>';
    return $msg ;
}

function _get_wpsiw_uploadfile_list(){
	$listdata = array() ;
	$file_list = scandir( WPSIW_UPLOAD_PATH );
	
	foreach ($file_list as $file) {
		if( $file == "." || $file == "..")continue ;		
		$file_path = WPSIW_UPLOAD_PATH . $file ;		
		if( filetype($file_path) != "file" )continue ; 
		$ext = end(explode('.', $file));
		if( $ext != "class" )continue ;
		
		$file_path = WPSIW_UPLOAD_PATH . $file ;
		$file_name = $file;
		$file_size = filesize($file_path) ;
		$file_date = filemtime ($file_path) ;
		
		if ( round ( $file_size/1024 ,0 )> 1 ) {
            $file_size = round ( $file_size/1024, 0 );
            $file_size = "$file_size KB";
        } else {
            $file_size = "$file_size B";
        }
        
        $file_date = date("n/j/Y g:h A", $file_date);
                
		$listdata[] = array(
							"filename" => $file_name,
							"filesize" => $file_size,
							"filedate" => $file_date
						) ;
	}
	return $listdata ;
}

function get_wpsiw_uploadfile_list(){
	
	$files = _get_wpsiw_uploadfile_list() ;

	foreach ($files as $file) {
		//$link = "<div class='row-actions'>
		//			<span><a href='#' alt='{$file["filename"]}' class='setdetails row-actionslink' title=''>Setting</a></span>&nbsp;|&nbsp;
		//			<span><a href='#' alt='{$file["filename"]}' class='sendtoeditor row-actionslink' title=''>Insert to post</a></span>											
		//		</div>" ;
        // prepare table row
        $table.= "<tr><td></td><td><a href='#' alt='{$file["filename"]}' class='sendtoeditor row-actionslink'>{$file["filename"]}</a></td><td width='50px'>{$file["filesize"]}</td><td width='130px'>{$file["filedate"]}</td></tr>";
	}
	
	if( !$table ){
		 $table.= '<tr><td colspan="3">'.__('No file uploaded yet.').'</td></tr>';
	}
	
	return $table ;
}

?>