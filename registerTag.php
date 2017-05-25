<?php
	include("connect.php");
	$link=Connection();

	$tResult = mysql_query("SELECT * FROM `tags` WHERE id=$_POST[id]",$link);
	if($tResult!==FALSE) {
		$tRow = mysql_fetch_array($tResult);
		echo "$tRow[tagID]";
		$result = mysql_query("UPDATE `members` SET tagID=$tRow[tagID] WHERE fName=\"$_POST[fName]\" AND lName=\"$_POST[lName]\"",$link);
		header("Location: /tags.php");
	} else {
		die("Error");
	}
?>
