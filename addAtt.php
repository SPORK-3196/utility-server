<?php
   	include("connect.php");

   	$link=Connection();

	$tagID=$_POST["tagID"];
	$io = 2;
	$oio = 2;

	$query = "SELECT * FROM AttendanceData WHERE tagID=".$tagID." ORDER BY entryDate DESC";
	$result = mysql_query($query,$link);
	if($result!==FALSE) {
		$row = mysql_fetch_array($result);
		if($row["io"]==0){$io=1;}
		else if($row["io"]==1){$io=0;}
		else {$io=0;}
	} else {
		$io=2;
	}

	$query = "INSERT INTO AttendanceData (tagID,io) VALUES (".$tagID.",".$io.")";
   	mysql_query($query,$link);

	$query = "SELECT * FROM members WHERE tagID=".$tagID;
	$result = mysql_query($query,$link);

	if($result!==FALSE) {
		$row = mysql_fetch_array($result);
		echo $row["fName"];
		echo "\n";
		echo $row["lName"];
		echo "\r";
		echo $io;
	} else {
		echo "Bad DB";
	}

	mysql_free_result($result);
	mysql_close($link);
?>
