<?php

	function Connection(){
		$server="localhost";
		$user="PLACEHOLDER_User";
		$pass="PLACEHOLDER_Pass";
		$db="PLACEHOLDER_DB";
	   	

		$mysqli = new mysqli($server, $user, $pass, $db);

		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}

		return $mysqli;
	}
?>
