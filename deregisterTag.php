<?php
	include("util.php");
	$sql = SQL::get_connection();

	echo "$_POST[tagID]";

	$sql->query("UPDATE tags SET memberID=NULL WHERE id=$_POST[tagID]");
	
	header("Location: /server/tags.php");
?>
