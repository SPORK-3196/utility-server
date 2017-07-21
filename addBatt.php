<?php
   	include("connect.php");

	function getClass($class) {
		switch($class) {
			case 0:
				return "A";
				break;
			case 1:
				return "B";
				break;
			case 2:
				return "C";
				break;
			case 3:
				return "D";
				break;
			default:
				return "-";
				break;
		}
	}
   	function getStatus($status) {
		switch($status) {
			case 0:
				return "Good";
				break;
			case 1:
				return "Needs charge";
				break;
			default:
				return "Other";
				break;
		}
	}
	function getEvent($event) {
		switch($event) {
			case 0:
				return "On charger";
				break;
			case 1:
				return "Off charger";
				break;
			case 2:
				return "On robot";
				break;
			case 3:
				return "Off robot";
				break;
			case 4:
				return "CHG->RBT";
				break;
			case 5:
				return "RBT->CHG";
				break;
			case 6:
				return "CHG->STR";
				break;
			case 7:
				return "STR->CHG";
				break;
			case 8:
				return "Checkup";
				break;
		}
	}

   	$link = Connection();

	$class = getClass($_POST["class"]);
	$id = $_POST["id"];
	$status = getStatus($_POST["status"]);
	$charge = $_POST["charge"];
	$v0 = $_POST["v0"]/1000;
	$v1 = $_POST["v1"]/1000;
	$v2 = $_POST["v2"]/1000;
	$rint = $_POST["rint"]/1000;
	$event = getEvent($_POST["event"]);

	$query = "INSERT INTO BatteryData (class, id, status, charge, v0, v1, v2, rint, event) 
		VALUES ('".$class."','".$id."','".$status."','".$charge."','".$v0."','".$v1."','".$v2."','".$rint."','".$event."')"; 

   	$link->query($query);
	$link->close();

   	header("Location: batteries.php");
?>
