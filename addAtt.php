<?php
   	include("util.php");

	// Establishes a connection with MySQL
   	$sql = SQL::get_connection();
	
	// The member
	$member = null;

	// Loads member from page parameters, favoring memberID over tagID. If no
	// parameters were given, the script dies with an error
	if (isset($_POST["memID"]))
		$member = Member::SQL_Load_Member_ID($_POST["memID"]);
	else if (isset($_POST["tagID"]))
		$member = Member::SQL_Load_Member_Tag($_POST["tagID"]);
	else
		die ("No member id given");

	// Leave an error message if the member wasn't able to be loaded
	if ($member === false) die ("Bad DB - Member not found");



	// Refresh the user's sign in status
	$member->Refresh_Sign_Status();
	
	// Toggle the user's sign-in status
	if ($member->signed_in)
		$member->Sign_Out();
	else
		$member->Sign_In();



	// Prints information depending on the output settings
	if ($_POST["output"] == "formatted" || $_POST["output"] == "" || !isset($_POST["output"])) {
		// Default, formatted for Arduino
		echo ($member->firstName."\n".
		$member->lastName."\r".
		(($member->signed_in == true) ? "1" : "0"));
	}
	else if ($_POST["output"] == "redirect") {
		// Redirects to the attendance page.
		header("Location: attendance.php");
		exit();
	}
?>

