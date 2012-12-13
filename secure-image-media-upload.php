<?php
// dont display errors
error_reporting(0);

// loda WP bootstraps
require_once('../../../wp-load.php');
require_once('../../../wp-admin/admin.php');

// check permissions
if (!current_user_can('edit_posts')) wp_die(__('You do not have permission to upload files.'));
if (@empty($_GET['post_id'])) wp_die(__('Incorrect usage!'));
if (!empty($_REQUEST) && !wp_verify_nonce( @$_REQUEST['wpsiw_token'], 'wpsiw_token' )) wp_die(__('Incorrect usage.'));

// set variables
$post_id = $_GET['post_id'];
$plugin_folder = trailingslashit( plugin_dir_url( __FILE__ ) );
$table = $msg = '';

// If form submitted.
if ( !empty( $_FILES ) ) {
    $upload_dir = wp_upload_dir();

    // get uploaded file informations.
    $wpsiw_file     = $_FILES['wpsiw_file'];
    $file_name      = sanitize_file_name( $wpsiw_file['name'] );
    $file_type      = $wpsiw_file['type'];
    $file_tmp_name  = $wpsiw_file['tmp_name'];
    $file_error     = $wpsiw_file['error'];
    $file_size      = $wpsiw_file['size'];
    $file_extension = pathinfo( $file_name, PATHINFO_EXTENSION );
    $file_url       = trailingslashit( $upload_dir['baseurl'] ).'secure-image/'.$file_name;
    $file_path      = trailingslashit( $upload_dir['basedir'] ).'secure-image/'.$file_name;
    $file_errors    = array( 0 => __( "There is no error, the file uploaded with success" ),
                             1 => __( "The uploaded file exceeds the upload_max_filesize directive in php.ini" ),
                             2 => __( "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form" ),
                             3 => __( "The uploaded file was only partially uploaded" ),
                             4 => __( "No file was uploaded" ),
                             6 => __( "Missing a temporary folder" )
                        );

    // check for upload errors
    if ( $file_error != 0 ) $msg = '<div class="error"><p><strong>'.__('Error').'!</strong></p><p>'.$file_errors[$file_error].'</p></div>';
    // check for file extension
    if ( $file_extension != 'class' ) $msg = '<div class="error"><p><strong>'.__('Error').'!</strong></p><p>'.__('Incorrect file type').'</p></div>';
    // check for file size
    if ( $file_size < 1 ) $msg = '<div class="error"><p><strong>'.__('Error').'!</strong></p><p>'.__('Empty file').'</p></div>';

    // there is no error, proceed
    if ( empty( $msg ) ) {
        // get options
        $wpsiw_options = get_option( 'wpsiw_options' );

        // remove duplicate file names
        foreach ( $wpsiw_options['files_list'] as $key => $file ) {
            if ( $file['name'] == $file_name ) {
                unset ( $wpsiw_options['files_list'][$key] );
            }
        }

        // move uploaded file to upload directory
        if ( move_uploaded_file( $file_tmp_name, $file_path ) ) {
            // insert file
            $file_date = time();
            $wpsiw_options['files_list'][] = array(
                                                    'name' => "$file_name",
                                                    'path' => "$file_path",
                                                    'url'  => "$file_url",
                                                    'size' => "$file_size",
                                                    'date' => "$file_date"
                                            );

            // insert file default options
            /*$wpsiw_options['files_options'][] = array (
                                                    'border'          => '0',
                                                    'border_color'    => '000000',
                                                    'text_color'      => 'FFFFFF',
                                                    'loading_message' => 'Image loading...',
                                                    'java_check'      => '0',
                                                    'key_safe'        => '1',
                                                    'hyperlink'       => '',
                                                    'target'          => '_top',
                                                    'postid'         => "0",
                                                    'name'           => "$file_name"
                                                );

            // sort arrays
            */
            ksort ( $wpsiw_options['files_list'] );
            //ksort ( $wpsiw_options['files_options'] );

            // update options
            update_option( 'wpsiw_options', $wpsiw_options );

            $msg = '<div class="updated"><p><strong>'.__('File Uploaded. You must save "File Details" to insert post').'</strong></p></div>
            <script>
            jQuery(document).ready(function() {
            jQuery( "#tabs" ).tabs( "select", "tabs-2" );
            jQuery("#wpsiw_searchfile").val("'.$file_name.'");
            jQuery("#search").trigger("click");
            });
            </script>';
        }
        else {
            $msg = '<div class="error"><p><strong>'.__('Error').'!</strong></p><p>'.__('Upload directory is not writable.').'</p></div>';
        }
    }
}


if (!empty($_POST) && empty($_FILES)) {
    // escape user inputs
    $_POST = array_map( "esc_attr", $_POST );
    extract( $_POST );

    $border          = ( empty( $border ) ? '0' : esc_attr( $border ) );
    $border_color    = ( empty( $border_color) ? '' : esc_attr( $border_color ) );
    $text_color      = ( empty( $text_color ) ? '' : esc_attr( $text_color ) );
    $loading_message = ( empty( $loading_message ) ? '' : esc_attr( $loading_message ) );
    $key_safe        = ( empty( $key_safe ) ? '' : '1' );
    $java_check      = ( empty( $java_check ) ? '' : '1' );


    $option_key = check_file_option( $postid, $name );
    $wpsiw_options = get_option( 'wpsiw_options' );

    if ( $option_key === 0 || $option_key > 0  ) {
        $wpsiw_options['files_options'][$option_key] = array (
                                                        'border'          => "$border",
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
    }
    else {
        $wpsiw_options['files_options'][] = array (
                                                'border'          => "$border",
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
    }



    update_option( 'wpsiw_options', $wpsiw_options );

    $msg = '<div class="updated fade"><p><strong>'.__('File Options Are Saved').'</strong><br />
    <a href="#" title="'.$name.'" class="button-secondary sendtoeditor"><strong>Insert file to editor</strong></a></p>
    </div>';
}

// generate token
$security = wp_create_nonce('wpsiw_token');
// get max upload file size
$max_size = ini_get('upload_max_filesize');

// get files list to display
$wpsiw_options = get_option( 'wpsiw_options' );
$files_list    = $wpsiw_options['files_list'];
if ( count( $files_list ) > 0 ) {
    foreach( $files_list as $key => $file ) {
        $file_name = $file['name'];
        $file_size = $file['size'];
        $file_date = $file['date'];

        // calculate file size
        if ( round ( $file_size/1024 ,0 )> 1 ) {
            $file_size = round ( $file_size/1024, 0 );
            $file_size = "$file_size KB";
        }
        else {
            $file_size = "$file_size B";
        }

        $file_date = date("n/j/Y g:h A");

        //$edit_link   = '<a href="'.home_url().'/wp-admin/admin.php?page=wpsiw_list&id='.$key.'&wpsiw_wpnonce='.$security.'&action=edit">Edit</a> | ';
        //$delete_link = '<a href="'.home_url().'/wp-admin/admin.php?page=wpsiw_list&id='.$key.'&wpsiw_wpnonce='.$security.'&action=del">Delete</a>';
        $link ='<a href="#" title="'.$file_name.'" class="button-secondary setdetails"><strong>Set File Details</strong></a>';

        // prepare table row
        $table.= '<tr><td>'.$link.'</td><td>'.$file_name.'</td><td>'.$file_size.'</td><td>'.$file_date.'</td></tr>';
    }
}
else {
    $table.= '<tr><td colspan="3">'.__('No file uploaded yet.').'</td></tr>';
}

// output
echo <<<html
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel='stylesheet' href='../../../wp-admin/css/wp-admin.css' type='text/css' media='all' />
<link rel='stylesheet' href='../../../wp-admin/css/media.css' type='text/css' media='all' />
<link rel='stylesheet' href='../../../wp-admin/css/colors-fresh.css' type='text/css' media='all' />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/redmond/jquery-ui.css" type='text/css' media='all' />
<link rel='stylesheet' href='./wp-secure-image.css' type='text/css' media='all' />
<script type='text/javascript' src='http://code.jquery.com/jquery-1.8.3.js'></script>
<script type='text/javascript' src='http://code.jquery.com/ui/1.9.2/jquery-ui.js'></script>
<script type='text/javascript' src='../../../wp-includes/js/jquery/suggest.js'></script>
<script type='text/javascript' src='./secure-image-media-uploader.js'></script>
<title>WP Secure Image</title>
<script type="text/javascript">
<!--
function MM_popupMsg(msg) { //v1.0
  alert(msg);
}
//-->
</script>
</head>
<body class="wpadmin">
    <div id="wpwrap">
        <div id="wpcontentt">
            <div id="wpbody">
                <div id="wpbody-content">
                    <div class="wrap" id="wpsiw_div" title="SecureImage">
                        $msg
                        <div id="tabs">
                            <ul>
                                <li><a href="#tabs-1">Add New</a></li>
                                <li><a href="#tabs-2">Search</a></li>
                                <li><a href="#tabs-3">Existing Files</a></li>
                            </ul>
                            <div id="tabs-1" class="wpsiw_addnew">
                                <div class="icon32" id="icon-addnew"><br /></div>
                                <h2>Add New Class File</h2>
                                <form id="file-form" class="media-upload-form" action="" method="post" enctype="multipart/form-data">
                                    <input type="hidden" value="$post_id" name="postid" id="postid" />
                                    <input type="hidden" value="$security" name="wpsiw_token" id="wpsiw_token" />
                                    <p>
                                        <input type="file" accept=".class" id="file" name="wpsiw_file" />
                                        <input type="submit" value="Upload" class="button button-primary" id="upload" />
                                        <a class="button-secondary" onclick="try{top.tb_remove();}catch(e){}; return false;" href="#" id="close1">Close</a>
                                    </p>
                                    <p>Maximum upload size: $max_size.</p>
                                	<p>You can choose file options after file is uploaded.</p>
                                    <p>If you use same name with uploaded class file, it will be overwritten.</p>
                                </form>
                                <div class="clear"></div>
                            </div>

                            <div id="tabs-2" class="wpsiw_search">
                                <div class="icon32" id="icon-search"><br /></div>
                                <h2>Search File</h2>
                                <p>
                                    File name : <input type="text" id="wpsiw_searchfile" name="wpsiw_searchfile" class="regular-text"  />
                                    <input type="hidden" value="$post_id" name="postid" id="postid" />
                                    <input type="button" value="Search" class="button button-primary" id="search" name="search" />
                                    <a class="button-secondary" onclick="try{top.tb_remove();}catch(e){}; return false;" href="#" id="close2">Close</a>
                                </p>
                                <div id="file_details"></div>
                                <div class="clear"></div>
                            </div>

                            <div id="tabs-3" class="wpsiw_filelist">
                                <div class="icon32" id="icon-file"><br /></div>
                                <h2>Uploaded Class Files</h2>
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
                                    $table
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
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body></html>
html;

function check_file_option( $postid, $filename ) {
    $wpsiw_options = get_option( 'wpsiw_options' );
    $options = $wpsiw_options['files_options'];

    $return = -1;
    foreach ($options as $key=>$option) {
        if ($option['name'] == $filename && $option['postid'] == $postid) {
            $return = $key;
            break;
        }
    }
    return $return;
}
?>