<?php
	include("util.php");
	$sql = SQL::get_connection();

	echo "$_POST[tagID] - $_POST[memID]";

	$member = Member::SQL_Load_Member_ID($_POST["memID"]);

	echo " $member->sql_id";

	$sql->query("UPDATE tags SET memberID=$member->sql_id WHERE id=$_POST[tagID]");
	
	header("Location: /server/tags.php");
?>
