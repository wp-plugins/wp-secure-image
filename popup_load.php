<?php 
/**

 */
If (! Class_Exists ( 'WPSIWPOPUP' )) {
	 
	/**
	 * 
	 */
	class WPSIWPOPUP
	{		
		function __construct()
		{					
			WPSIWPOPUP::add_popup_stylesheet() ;
			WPSIWPOPUP::add_popup_script() ;
			call_user_func_array( array( 'WPSIWPOPUP','set_media_upload'), array() ) ;			
		}
    			
		public function header_html()
		{?><!DOCTYPE html>
		   <html <?php language_attributes(); ?>>
		   <head>
				<meta charset="<?php bloginfo( 'charset' ); ?>" />
				<title><?php echo __("Step Setting");?></title>
		   </head>
		   <body>
		   <div id="wrapper" class="hfeed">		       
		   		<ul>
		       <?php
		}
		
		public function footer_html()
		{
	             ?>	
		       </ul>		       
		    </div>
		    </body>
		<?php			
		}
		
		public function set_media_upload()
		{
			include( WPSIW_PLUGIN_PATH . "secure-image-media-upload.php" );       
		}
		
		public function add_popup_stylesheet()
		{
			echo "<link rel='stylesheet' href='" . WPSIW_PLUGIN_URL . "wp-secure-image.css' type='text/css' />" ;			
		}
		
		public function add_popup_script()
		{
			echo "<script type='text/javascript' src='" . WPSIW_PLUGIN_URL . "secure-image-media-uploader.js'></script>" ;
		}
	 }	 
	 $popup = new WPSIWPOPUP ();	 
}
 ?>