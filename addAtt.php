<?php
   	include("connect.php");

   	$link=Connection();

	$tagID=$_POST["tagID"];

	$query = "INSERT INTO AttendanceData (tagID) VALUES (".$tagID.")";
   	mysql_query($query,$link);

	$query = "SELECT * FROM members WHERE tagID=".$tagID;
	$result = mysql_query($query,$link);

	if($result!==FALSE) {
		$row = mysql_fetch_array($result);
		echo $row["fName"];
		echo "\n";
		echo $row["lName"];
	} else {
		echo "Bad DB";
	}

	mysql_free_result($result);
	mysql_close($link);
?>
