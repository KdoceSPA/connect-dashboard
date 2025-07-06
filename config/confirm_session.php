<?php
	session_name('connect_session');
	session_start();
	
	// Check
	if (isset($_SESSION['connect_id_user']) == '' || trim($_SESSION['connect_user']) == '' || trim($_SESSION['connect_type']) == '') {
		header('location: config/close_session.php');
		exit();
	}
	else {
		$id_user_current = $_SESSION['connect_id_user'];
		$user_current = $_SESSION['connect_user'];
		$type_current = $_SESSION['connect_type'];
	}
?>