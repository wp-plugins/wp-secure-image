<?php
if( !session_id() ) {	session_start();	}
$_SESSION['is_user_logged_in']=is_user_logged_in();
?>