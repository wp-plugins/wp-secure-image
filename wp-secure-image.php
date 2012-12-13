<?php
/*
Plugin Name: Secure Image
Plugin URI: http://www.artistscope.com/secure_image_protection.asp
Description: Add encrypted images and control web browser access. With Secure Image software you can use encrypted images and extend copy protection to prevent image saving while displayed online and protect the images that stored on the server even from your webmaster.
Author: ArtistScope
Version: 0.1
Author URI: http://www.artistscope.com/

	Copyright 2012 ArtistScope Pty Limited

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// ================================================================================ //
//                                                                                  //
//  WARNING : DONT CHANGE ANYTHING BELOW IF YOU DONT KNOW WHAT YOU ARE DOING        //
//                                                                                  //
// ================================================================================ //

# set script max execution time to 5mins
set_time_limit(300);

// ============================================================================================================================
# register WordPress menus
function wpsiw_admin_menus() {
    add_menu_page( 'Secure Image', 'Secure Image', 'publish_posts', 'wpsiw_list' );
    add_submenu_page( 'wpsiw_list', 'Secure Image List Files', 'List Files', 'publish_posts', 'wpsiw_list', 'wpsiw_admin_page_list' );
    add_submenu_page( 'wpsiw_list', 'Secure Image Settings', 'Settings', 'publish_posts', 'wpsiw_settings', 'wpsiw_admin_page_settings' );
}

// ============================================================================================================================
# "List" Page
function wpsiw_admin_page_list() {
    // check current user capabilities.
    if ( !current_user_can( 'publish_posts' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page!' ) );
    }

    // "Edit" link clicked, proceed
    $edit_output = '';
    if ( @$_GET['id'] != false || @$_GET['id'] != null ) {
        // check securtiy key
        if ( !wp_verify_nonce( @$_GET['wpsiw_wpnonce'], 'wpsiw_list' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        // check for action
        if ( @empty( $_GET['action'] ) ) {
            wp_die( __( 'Incorrect usage!' ) );
        }

        // pre-delete file
        if ( $_GET['action'] == 'del' ) {
            $id = (int)$_GET['id'];
            $wpsiw_options = get_option( 'wpsiw_options' );

            // remove class file
            unlink ( $wpsiw_options['files_list'][$id]['path'] );

            // remove file options
            //unset ( $wpsiw_options['files_options'][$id] );
            wpsiw_delete_file_options( $id, $wpsiw_options['files_list'][$id]['name'] );

            // remove from files list
            unset ( $wpsiw_options['files_list'][$id] );

            update_option( 'wpsiw_options', $wpsiw_options );
        }
    }


    $msg      = '';
    $table    = '';
    $security = wp_create_nonce( 'wpsiw_list' );

    // get files list to display
    $wpsiw_options = get_option( 'wpsiw_options' );
    $files_list    = $wpsiw_options['files_list'];
    if ( count( $files_list ) > 0 ) {
        foreach( $files_list as $key => $file ) {
            $file_name = $file['name'];
            $file_size = $file['size'];
            $file_date = $file['date'];

            // calculate file size
            if ( round ( $file_size/1024 ,0 ) > 1 ) {
                $file_size = round ( $file_size/1024, 0 );
                $file_size = "$file_size KB";
            }
            else {
                $file_size = "$file_size B";
            }

            $file_date = date("n/j/Y g:h A");

            $edit_link = '';
            //$edit_link   = '<a href="'.home_url().'/wp-admin/admin.php?page=wpsiw_list&id='.$key.'&wpsiw_wpnonce='.$security.'&action=edit">Edit</a> | ';
            $delete_link = '<a href="'.home_url().'/wp-admin/admin.php?page=wpsiw_list&id='.$key.'&wpsiw_wpnonce='.$security.'&action=del">Delete</a>';

            // prepare table row
            $table.= '<tr><td>'.$edit_link.$delete_link.'</td><td>'.$file_name.'</td><td>'.$file_size.'</td><td>'.$file_date.'</td></tr>';
        }
    }
    else {
        $table.= '<tr><td colspan="4">'.__( 'No file uploaded yet.' ).'</td></tr>';
    }

?>
<div class="wrap">
    <div class="icon32" id="icon-file"><br /></div>
    <?php echo $msg; ?>
    <h2>List Class Files</h2>
    <div id="col-container">
        <div id="col-right">
            <div class="col-wrap">
                <!-- <h3>Edit Class File Options</h3> -->
                <?php echo $edit_output; ?>
            </div>
            <div class="clear"></div>
        </div>
        <div id="col-left">
            <div class="col-wrap">
                <h3>Uploaded Class Files</h3>
                <table class="wp-list-table widefat">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>File</th>
                            <th>Size</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php echo $table; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>&nbsp;</th>
                            <th>File</th>
                            <th>Size</th>
                            <th>Date</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>
<?php
}

// ============================================================================================================================
# "Settings" page
function wpsiw_admin_page_settings() {
    // check current user capabilities.
    if ( !current_user_can( 'publish_posts' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page!' ) );
    }

    // check securtiy key
    if ( !empty( $_POST ) && !wp_verify_nonce( @$_POST['wpsiw_wpnonce'], 'wpsiw_settings' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    $msg='';
    // settings for msubmitted, proceed
    if ( !empty( $_POST ) ) {
        extract( $_POST, EXTR_OVERWRITE );
        $wpsiw_options = get_option( 'wpsiw_options' );

        // check submitted data
        $plugin_folder = ( empty( $plugin_folder ) ? '' : esc_attr( $plugin_folder ) );
        $ie = ( empty( $ie ) ? '' : 'checked' );
        $ff = ( empty( $ff ) ? '' : 'checked' );
        $ch = ( empty( $ch ) ? '' : 'checked' );
        $op = ( empty( $op ) ? '' : 'checked' );
        $sa = ( empty( $sa ) ? '' : 'checked' );

        // update plugin settings
        $wpsiw_options['settings'] = array(
                                        'plugin_folder'=> "$plugin_folder",
                                        'mode'         => "$mode",
                                        'ie'           => "$ie",
                                        'ff'           => "$ff",
                                        'ch'           => "$ch",
                                        'op'           => "$op",
                                        'sa'           => "$sa"
                                    );

        update_option( 'wpsiw_options', $wpsiw_options );
        $msg = '<div class="updated"><p><strong>'.__( 'Settings Saved' ).'</strong></p></div>';
    }

    $security      = wp_create_nonce( 'wpsiw_settings' );
    $wpsiw_options = get_option( 'wpsiw_options' );

    // get plugin settings to display.
    $plugin_folder = $wpsiw_options['settings']['plugin_folder'];
    $mode          = $wpsiw_options['settings']['mode'];
    $ie            = $wpsiw_options['settings']['ie'];
    $ff            = $wpsiw_options['settings']['ff'];
    $ch            = $wpsiw_options['settings']['ch'];
    $op            = $wpsiw_options['settings']['op'];
    $sa            = $wpsiw_options['settings']['sa'];

    $select = '<option value="demo">Demo Mode</option><option value="licensed">Licensed</option><option value="debug">Debugging Mode</option>';
    $select = str_replace( 'value="'.$mode.'"','value="'.$mode.'" selected',$select);

    $wpsiw_token = wp_create_nonce('wpsiw_token');
    $url = trailingslashit( plugin_dir_url( __FILE__ ) );

?>
<div class="wrap">
    <div class="icon32" id="icon-settings"><br /></div>
    <?php echo $msg; ?>
    <h2>Settings</h2>
    <form action="" method="post">
    <input type="hidden" value="<?php echo $security; ?>" name="wpsiw_wpnonce" id="wpsiw_wpnonce" />
    <table class="form-table">
        <p><strong>Default settings applied to all protected pages:</strong></p>
    	<tbody>
            <tr>
    		  <th align="left"><label>Plugin Folder:</label></th>
    		  <td align="left"> <input value="<?php echo $plugin_folder; ?>" name="plugin_folder" class="regular-text code" type="text"></td>
    	    </tr>
        	<tr>
        		<th align="left"><label>Mode</label></th>
        		<td align="left"> <select name="mode"><?php echo $select; ?></select></td>
        	</tr>
            <tr>
    		  <th align="left"><label>Allow IE:</label></th>
    		  <td align="left"> <input name="ie" type="checkbox" value="1" <?php echo $ie; ?>></td>
    	    </tr>
            <tr>
    		  <th align="left"><label>Allow Firefox:</label></th>
    		  <td align="left"> <input name="ff" type="checkbox" value="1" <?php echo $ff; ?>></td>
    	    </tr>
            <tr>
    		  <th align="left"><label>Allow Chrome:</label></th>
    		  <td align="left"> <input name="ch" type="checkbox" value="1" <?php echo $ch; ?>></td>
    	    </tr>
            <tr>
    		  <th align="left"><label>Allow Opera:</label></th>
    		  <td align="left"> <input name="op" type="checkbox" value="1" <?php echo $op; ?>></td>
    	    </tr>
            <tr>
    		  <th align="left"><label>Allow Safari:</label></th>
    		  <td align="left"> <input name="sa" type="checkbox" value="1" <?php echo $sa; ?>></td>
    	    </tr>
    	</tbody>
    </table>
    <p class="submit">
        <input type="submit" value="Save Settings" class="button-primary" id="submit" name="submit">
    </p>
    </form>
    <div class="clear"></div>
</div>
<div class="clear"></div>
<?php
}

// ============================================================================================================================
# convert shortcode to html output
function wpsiw_shortcode( $atts ) {
    // get plugin options
    $wpsiw_options = get_option( 'wpsiw_options' );

    // get given file details
    $file_key = -1;
    foreach ( $wpsiw_options['files_list'] as $key => $file ) {
        if ( $file['name'] == $atts['name'] ) {
            $file_key = $key;
            break;
        }
    }

    // if given file name correct
    if ( $file_key > -1 ) {
        $file    = $wpsiw_options['files_list'][$key];
        $options = $wpsiw_options['files_options'][$key];

        // set shortcode arguments
        extract( shortcode_atts( array(
            'name'            => $options['name'],
            'border'          => $options['border'],
            'border_color'    => $options['border_color'],
            'text_color'      => $options['text_color'],
            'loading_message' => $options['loading_message'],
            'java_check'      => $options['java_check'],
	     'menu_safe'       => $options['menu_safe'],
            'hyperlink'       => $options['hyperlink'],
            'target'          => $options['target'],
            'postid'          => $options['postid'],
            'plugin_folder'   => $wpsiw_options['settings']['plugin_folder'],
            'mode'            => $wpsiw_options['settings']['mode'],
            'ie'              => $wpsiw_options['settings']['ie'],
            'ff'              => $wpsiw_options['settings']['ff'],
            'ch'              => $wpsiw_options['settings']['ch'],
            'op'              => $wpsiw_options['settings']['op'],
            'sa'              => $wpsiw_options['settings']['sa'],
        ), $atts ) );

	// convert settings

	if ($ch = 'checked') {$chrome = '1';}
	if ($ff = 'checked') {$firefox = '1';}
	if ($op = 'checked') {$opera = '1';}
	if ($sa = 'checked') {$safari = '1';}
	if ($ie = 'checked') {$msie = '1';}

	
        // display output
        $output = <<<html
	<NOSCRIPT><meta http-equiv="refresh" content="0;url=/wp-content/plugins/wp-secure-image/download_javascript.html"></NOSCRIPT>
	<script type="text/javascript" src="/wp-content/plugins/wp-secure-image/JavaVersionDisplayApplet.js"></script>
	
	 <script type="text/javascript">
	<!-- hide JavaScript from non-JavaScript browsers
		var m_bpDebugging = false;
		var m_szMode = "$mode";
		var m_szClassName = "$name";
		var m_szImageFolder = "/wp-content/uploads/secure-image/";		//  path from root with / on both ends
		var m_bpJavaCheck = "$java_check";
		var m_bpKeySafe = "$key_safe";
	//	var m_bpWindowsOnly = true;	

		var m_bpChrome = "$chrome";	
		var m_bpFx = "$firefox";			// all firefox browsers from version 5 and later
		var m_bpNav = true;
		var m_bpOpera = "$opera";
		var m_bpSafari = "$safari";
		var m_bpMSIE = "$msie";

		var m_szDefaultStyle = "ImageLink";
		var m_szDefaultTextColor = "$text_color";
		var m_szDefaultBorderColor = "$border_color";
		var m_szDefaultBorder = "$border";
		var m_szDefaultLoading = "$loading_message";
		var m_szDefaultLabel = "";
		var m_szDefaultLink = "$hyperlink";
		var m_szDefaultTargetFrame = "$target";
		var m_szDefaultMessage = "";

		if (m_szMode == "debug") {
		m_bpDebugging = true;
		}
		
		if (m_bpKeySafe == "1") {
			var cswbody = document.getElementsByTagName("body")[0];
				if (m_bpJavaCheck == "1") {
					cswbody.setAttribute("onload", "showJVMDetails();");
				}
			cswbody.setAttribute("onload", "showJVMDetails();");
			cswbody.setAttribute("onselectstart", "return false;");
			cswbody.setAttribute("ondragstart", "return false");
			cswbody.setAttribute("onmousedown", "if (event.preventDefault){event.preventDefault();}");
			cswbody.setAttribute("onBeforePrint", "document.body.style.display = '';");
			cswbody.setAttribute("onContextmenu", "return false;");
			cswbody.setAttribute("onClick", "if(event.button==2||event.button==3){event.preventDefault();event.stopPropagation();return false;}");
		}
		else {
			var cswbody = document.getElementsByTagName("body")[0];
				if (m_bpJavaCheck == "1") {
					cswbody.setAttribute("onload", "showJVMDetails();");
				}
			cswbody.setAttribute("onselectstart", "return false;");
			cswbody.setAttribute("ondragstart", "return false");
		//	cswbody.setAttribute("onmousedown", "if (event.preventDefault){event.preventDefault();}");
			cswbody.setAttribute("onBeforePrint", "document.body.style.display = '';");
			cswbody.setAttribute("onContextmenu", "return false;");
			cswbody.setAttribute("onClick", "if(event.button==2||event.button==3){event.preventDefault();event.stopPropagation();return false;}");
		}


		// -->
	 </script>
	<APPLET codeBase="/wp-content/plugins/wp-secure-image/" height="0" width="0" code="JavaVersionDisplayApplet.class" name="display"></APPLET>
	 <script src="/wp-content/plugins/wp-secure-image/wp-secure-image.js" type="text/javascript"></script>

	 <script type="text/javascript">
		<!-- hide JavaScript from non-JavaScript browsers
		if ((m_szMode == "licensed") || (m_szMode == "debug")) {
		insertSecureImage("$name");
		}
		else {
		document.writeln("<img src='/wp-content/plugins/wp-secure-image/images/secure-image-button.png' border='0' alt='Demo mode'>");
		}
		// -->
	 </script>

html;

       return $output;
    }
    else return;
}

// ============================================================================================================================
# delete short code
function wpsiw_delete_shortcode() {
    // get all posts
    $posts_array = get_posts();
    foreach ( $posts_array as $post ) {
        // delete short code
        $post->post_content = wpsiw_deactivate_shortcode( $post->post_content );
        // update post
        wp_update_post( $post );
    }
}

// ============================================================================================================================
# deactivate short code
function wpsiw_deactivate_shortcode( $content ) {
    // delete short code
    $content = preg_replace( '/\[secimage name="[^"]+"\]\[\/secimage\]/s', '', $content );
	return $content;
}

// ============================================================================================================================
# search short code in post content and get post ids
function wpsiw_search_shortcode( $file_name ) {
    // get all posts
    $posts = get_posts();
    $IDs   = false;
    foreach ( $posts as $post ) {
        $file_name = preg_quote( $file_name,'\\' );
        preg_match( '/\[secimage name="'.$file_name.'"\]\[\/secimage\]/s', $post->post_content, $matches );
        if ( is_array($matches) && isset( $matches[1] ) ) {
            $IDs[] = $post->ID;
        }
    }
    return $IDs;
}


// ============================================================================================================================
# delete file options
function wpsiw_delete_file_options( $id, $file_name ) {
    $wpsiw_options = get_option( 'wpsiw_options' );
    $posts         = wpsiw_search_shortcode( $file_name );
    $file_name     = preg_quote( $file_name,'/' );
    if ($posts) {
        foreach ( $posts as $post_id ) {
            $post = get_post( $post_id );
            $post->post_content = preg_replace( '/\[secimage name="'.$file_name.'"\]\[\/secimage\]/s', '', $post->post_content);
            wp_update_post($post);

            foreach ( $wpsiw_options['files_options'] as $key => $options ) {
                if ( $options['postid'] == $id && $options['file_name'] == $file_name ) {
                    unset($wpsiw_options['files_options'][$key]);
                    break;
                }
            }
        }
        update_option( 'wpsiw_options', $wpsiw_options );
    }
}

// ============================================================================================================================
# install media buttons
function wpsiw_media_buttons ( $context ) {
    global $post_ID;
    // generate token for links
    $token = wp_create_nonce( 'wpsiw_token' );
    $url = plugin_dir_url( __FILE__ ).'secure-image-media-upload.php?post_id='.$post_ID. '&wpsiw_token='.$token.'&TB_iframe=1';
    return $context.="<a href='$url' class='thickbox' id='wpsiw_link' title='SecureImage'><img src='".plugin_dir_url( __FILE__ )."/images/secure-image-button.png'></a>";
}


// ============================================================================================================================
# browser detector js file
function wpsiw_load_js() {
    // load custom JS file
	wp_enqueue_script( 'wpsiw-browser-detector', plugins_url( '/browser_detection.js', __FILE__), array( 'jquery' ) );
}

// ============================================================================================================================
# admin page scripts
function wpsiw_admin_load_js() {
    // load jquery suggest plugin
    wp_enqueue_script( 'suggest' );
}

// ============================================================================================================================
# admin page styles
function wpsiw_admin_load_styles() {
    // register custom CSS file & load
    wp_register_style( 'wpsiw-style', plugins_url( '/wp-secure-image.css', __FILE__ ) );
	wp_enqueue_style( 'wpsiw-style' );
}

// ============================================================================================================================
# setup plugin
function wpsiw_setup () {
    // load js file
    add_action( 'wp_enqueue_scripts', 'wpsiw_load_js' );

    // load admin CSS
    add_action( 'admin_print_styles', 'wpsiw_admin_load_styles' );

    // add short code
    add_shortcode( 'secimage', 'wpsiw_shortcode' );

    // if user logged in
    if ( is_user_logged_in() ) {
        // install admin menu
        add_action( 'admin_menu', 'wpsiw_admin_menus' );

        // check user capability
        if ( current_user_can( 'edit_posts' ) ) {
            // load admin JS
            add_action( 'admin_print_scripts', 'wpsiw_admin_load_js' );
            // load media button
            add_action( 'media_buttons_context', 'wpsiw_media_buttons' );
        }
    }
}

// ============================================================================================================================
# runs when plugin activated
function wpsiw_activate () {
    // if this is first activation, setup plugin options
    if ( !get_option( 'wpsiw_options' ) ) {
        // set plugin folder
        $plugin_folder = plugin_dir_url( __FILE__ );
        $plugin_folder = str_replace ( home_url(), '', $plugin_folder );
        // set default options
        $wpsiw_options['settings'] = array(
                                        'plugin_folder' => "$plugin_folder",
                                        'mode'          => "$mode",
                                        'ie'            => "1",
                                        'ff'            => "1",
                                        'ch'            => "1",
                                        'op'            => "1",
                                        'sa'            => "1"
                                    );
        $wpsiw_options['files_options'] = array();
        $wpsiw_options['files_list']    = array();
        add_option( 'wpsiw_options' , $wpsiw_options );
    }

    // create upload directory if it is not exist
    $upload_dir = wp_upload_dir();
    $upload_dir = trailingslashit( $upload_dir['basedir'] ).'secure-image/';
    if ( !is_dir( $upload_dir ) ) mkdir( $upload_dir );
}

// ============================================================================================================================
# runs when plugin deactivated
function wpsiw_deactivate () {
    // remove text editor short code
    remove_shortcode( 'secimage' );
}

// ============================================================================================================================
# runs when plugin deleted.
function wpsiw_uninstall () {
    // delete all uploaded files
    $upload_dir = wp_upload_dir();
    $upload_dir = trailingslashit( $upload_dir['basedir'] ).'secure-image';
    $dir = scandir( $upload_dir );
    foreach ( $dir as $file ) {
        if ( $file != '.' || $file != '..' ) {
            unlink( $upload_dir.'/'.$file );
        }
    }

    // delete upload directory
    rmdir( $upload_dir );

    // delete plugin options
    delete_option( 'wpsiw_options' );

    // unregister short code
    remove_shortcode( 'secimage' );

    // delete short code from post content
    wpsiw_delete_shortcode();
}

// ============================================================================================================================
# register plugin hooks
register_activation_hook( __FILE__, 'wpsiw_activate' ); // run when activated
register_deactivation_hook( __FILE__, 'wpsiw_deactivate' ); // run when deactivated
register_uninstall_hook( __FILE__, 'wpsiw_uninstall' ); // run when uninstalled

add_action( 'init', 'wpsiw_setup' );
?>