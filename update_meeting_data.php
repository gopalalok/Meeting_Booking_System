<?php
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/classes/User.php');
	$usr = new User();

	if($_SERVER['REQUEST_METHOD'] == 'POST')	
	{	
		$m_title 		= $_POST['m_title'];
		$framework 		= $_POST['framework'];
		$m_date 		= $_POST['m_date'];
		$m_time 	    = $_POST['m_time'];
		$userId			= $_POST['userId'];
		$m_id			= $_POST['m_id'];
		$data 			= $usr->updateMeetingData($m_title,$framework,$m_date,$m_time,$userId,$m_id);
	}
?>