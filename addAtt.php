<?php
   	include("connect.php");

   	$link=Connection();

	$tagID=$_POST["tagID"];

	$query = "INSERT INTO AttendanceData (tagID) VALUES (".$tagID.")";

   	mysql_query($query,$link);
	mysql_close($link);

   	header("Location: attendance.php");
?>
