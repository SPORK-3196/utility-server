<?php
   	include("connect.php");

	// Establishes a connection with MySQL and reads the tagID
   	$link = Connection();
	$tagID = $_POST["tagID"];



	// Finds the member with the given tag
	$memresults = mysql_query("SELECT * FROM members WHERE tagID=".$tagID.";", $link);
	if ($memresults !== FALSE) {
		$memrow = mysql_fetch_array($memresults);
		$memberID = $memrow[id];
	} else die("Card not found");



	$io = 2;
	$oio = 2;



	// Loads all the attendence logs, to find whether the previous entry
	// for this member was arrival or departure
	$query = "SELECT * FROM AttendanceData WHERE memberID=".$memberID." ORDER BY entryDate DESC";
	$result = mysql_query($query,$link);
	if($result!==FALSE) {
		$row = mysql_fetch_array($result);
		if($row["io"]==0) {
			$io=1;
		}
		else if($row["io"]==1) {
			$io=0;
		}
		else {
			$io=0;
		}

		mysql_query("UPDATE members SET inShop='$io' WHERE id=$memberID");
	} else {
		$io=2;
	}



	// Adds the members' arrival/departure to the logs
	$query = "INSERT INTO AttendanceData (memberID,io) VALUES (".$memberID.",".$io.")";
	mysql_query($query,$link);



	// Finds again the member with the given tag
	$query = "SELECT * FROM members WHERE id=$memberID";
	$result = mysql_query($query,$link);

	if($result!==FALSE) {
		$row = mysql_fetch_array($result);
		if($row["fName"]=="Master" && $row["lName"]=="Key") {
			$io+=3;
			echo "SPORKshop";
			echo "\n";
			if($io==4) {echo "open";}
			if($io==3) {
				$mQuery = "SELECT * FROM members";
				$mResult = mysql_query($mQuery,$link);
				if($mResult!==FALSE) {
					while($mRow = mysql_fetch_array($mResult)) {
						$aQuery = "SELECT * FROM AttendanceData WHERE memberID=".$mRow["memberID"]." ORDER BY id DESC";							$aResult = mysql_query($aQuery,$link);
						if($aResult!==FALSE) {
							$aRow = mysql_fetch_array($aResult);
							if($aRow["io"]==1) {
								$fQuery = "INSERT INTO AttendanceData (memberID,io) VALUES (".$mRow["memberID"].",0)";
								mysql_query($fQuery,$link);
							}
						}
					}
				}
				echo "closed";
			}
		} else {
			echo $row["fName"];
			echo "\n";
			echo $row["lName"];
		}
		echo "\r";
		echo $io;
	} else {
		echo "Bad DB";
	}

	mysql_free_result($aResult);
	mysql_free_result($mResult);
	mysql_free_result($result);
	mysql_close($link);
?>

