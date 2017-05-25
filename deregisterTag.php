<?php
	include("connect.php");
	$link=Connection();

	echo "$tRow[tagID]";
	$result = mysql_query("UPDATE `members` SET tagID=0 WHERE fName=\"$_POST[fName]\" AND lName=\"$_POST[lName]\"",$link);
	header("Location: /tags.php");
?>
