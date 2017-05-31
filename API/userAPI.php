<?php
	include("../class/user.class.php");
	$data = new exUser_Profile;

	header("Access-Control-Allow-Origin: *");
	echo json_encode($data);
?>
