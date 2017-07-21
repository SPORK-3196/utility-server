<?php

	function Connection(){
		$server="localhost";
		$user="PLACEHOLDER_User";
		$pass="PLACEHOLDER_Pass";
		$db="PLACEHOLDER_DB";
	   	
		$connection = mysql_connect($server, $user, $pass);

		if (!$connection) {
	    	die('MySQL ERROR: ' . mysql_error());
		}
		
		mysql_select_db($db) or die( 'MySQL ERROR: '. mysql_error() );

		return $connection;
	}
?>
