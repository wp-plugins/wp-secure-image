<?php
// ddont display errors
error_reporting(0);

// loda WP bootstraps
require_once('../../../wp-load.php');
require_once('../../../wp-admin/admin.php');

// check permissions
if (!current_user_can('edit_posts')) wp_die(__('You do not have permission to upload files.'));

// search file name
if (@!empty($_GET['q']) && @strlen($_GET['q'])>2) {
    $q = $_GET["q"];
    $q = esc_attr($q);

    $wpsiw_options = get_option( 'wpsiw_options' );
    foreach ( $wpsiw_options['files_list'] as $key => $file ) {
        $file_name = $file['name'];
        if (strpos(strtolower($file_name), $q) !== false) {
    		echo "$file_name\n";
    	}
    }
    return;
}

// get selected file details
if (@!empty($_GET['search']) && @!empty($_GET['post_id'])) {
    $search = $_GET["search"];
    $search = esc_attr($search);

    // search file id
    $id = -1;
    $wpsiw_options = get_option( 'wpsiw_options' );
    foreach ( $wpsiw_options['files_list'] as $key => $file ) {
        $file_name = $file['name'];
        if ($file_name == $search) {
    		$id = $key;
            break;
    	}
    }

    // incorrect file id given
    if ($id < 0) return;

    // get file details
    $post_id = (int)$_GET['post_id'];
    $file_details  = $wpsiw_options['files_list'][$id];
    $option_id = check_file_option($post_id,$search);
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
    if ($option_id === 0 || $option_id > 0 ) {
        $file_options = $wpsiw_options['files_options'][$option_id];
    }

    extract( $file_details, EXTR_OVERWRITE );
    extract( $file_options, EXTR_OVERWRITE );
    $security = wp_create_nonce('wpsiw_token');

    echo <<<html
        <hr />
        <div class="icon32" id="icon-file"><br /></div>
        <h2>Class Settings</h2>
        <form action="" method="post" id="file_form">
        <input type="hidden" value="$security" name="wpsiw_token" id="wpsiw_token" />
        <input type="hidden" value="$name" name="name" />
        <input type="hidden" value="$id" name="id" />
        <input type="hidden" value="$post_id" name="postid" />
<table cellpadding="0" cellspacing="0" border="0" >
  <tbody> 
  <tr>
    <td align="left" width="50">&nbsp;</td>
    <td align="left" width="40"><img src="/wp-content/plugins/wp-secure-image/images/help-24-30.png" border="0" onClick="MM_popupMsg('Border thickness in pixels. For no border set 0.')"></td>
    <td align="left">Border:</td>
    <td> 
      <input name="border" type="text" value="$border" size="3">
    </td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left"><img src="/wp-content/plugins/wp-secure-image/images/help-24-30.png" border="0" onClick="MM_popupMsg('Color of the border and image backround area. For example use FFFFFF for white and 000000 is for black... without the # symbol.')"></td>
    <td align="left">Border color:</td>
    <td> 
      <input name="border_color" type="text" value="$border_color" size="7">
    </td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left"><img src="/wp-content/plugins/wp-secure-image/images/help-24-30.png" border="0" onClick="MM_popupMsg('Color of the text message that is displayed in the image area sas the image downloads.')"></td>
    <td align="left">Text color:</td>
    <td> 
      <input name="text_color" type="text" value="$text_color" size="7">
    </td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left"><img src="/wp-content/plugins/wp-secure-image/images/help-24-30.png" border="0" onClick="MM_popupMsg('Check this box to disable use of the keyboard when the class image loads.')"></td>
    <td align="left">KeySafe:</td>
    <td> 
      <input name="key_safe" type="checkbox" value="1" $key_safe>
    </td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left"><img src="/wp-content/plugins/wp-secure-image/images/help-24-30.png" border="0" onClick="MM_popupMsg('Check this box to add Java version detection to teh page.')"></td>
    <td align="left">Java Check:</td>
    <td> 
      <input name="java_check" type="checkbox" value="1" $java_check>
    </td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left"><img src="/wp-content/plugins/wp-secure-image/images/help-24-30.png" border="0" onClick="MM_popupMsg('Set the message to display as this class image loads.')"></td>
    <td align="left">Loading message:&nbsp;</td>
    <td> 
      <input name="loading_message" type="text" value="$loading_message" size="30">
    </td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left"><img src="/wp-content/plugins/wp-secure-image/images/help-24-30.png" border="0" onClick="MM_popupMsg('Add a link to another page activated by clciking on the image, or leave blank for no link.')"></td>
    <td align="left">Hyperlink:</td>
    <td> 
      <input value="$hyperlink" name="hyperlink" type="text" size="30">
    </td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left"><img src="/wp-content/plugins/wp-secure-image/images/help-24-30.png" border="0" onClick="MM_popupMsg('Set the target frame for the hyperlink, for example _top')"></td>
    <td align="left">Target frame:</td>
    <td> 
      <input value="$target" name="target" type="text" size="15">
    </td>
  </tr>
  </tbody> 
</table>
        <p class="submit">
            <input type="submit" value="Save" class="button-primary" id="submit" name="submit">
            <input type="button" value="Cancel" class="button-primary" id="cancel">
        </p>
        </form>
html;
}

function check_file_option( $postid, $filename ) {
    $wpsiw_options = get_option( 'wpsiw_options' );
    $options = $wpsiw_options['files_options'];

    $return = -1;
    foreach ($options as $key=>$option) {
        if ($option['name'] == $filename && $option['postid'] == $postid) {
            $return = $key;
           // print_r($option).$key;
            break;
        }
    }
    return $return;
}
?>