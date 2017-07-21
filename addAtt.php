<?php
   	include("connect.php");

	// Establishes a connection with MySQL and reads the tagID
   	$link = Connection();
	$tagID = $_POST["tagID"];



	// Finds the member with the given tag
	$memresults = $link->query("SELECT * FROM members WHERE tagID=".$tagID.";");
	if ($memresults !== FALSE) {
		$memrow = $memresults->fetch_assoc();
		$memberID = $memrow[id];
	} else die("Card not found");



	$io = 2;
	$oio = 2;



	// Loads all the attendence logs, to find whether the previous entry
	// for this member was arrival or departure
	$query = "SELECT * FROM AttendanceData WHERE memberID=".$memberID." ORDER BY entryDate DESC";
	$result = $link->query($query);
	if($result!==FALSE) {
		$row = $results->fetch_assoc();
		if($row["io"]==0){$io=1;}
		else if($row["io"]==1){$io=0;}
		else {$io=0;}
	} else {
		$io=2;
	}



	// Adds the members' arrival/departure to the logs
	$query = "INSERT INTO AttendanceData (memberID,io) VALUES (".$memberID.",".$io.")";
	$link->query($query);



	// Finds again the member with the given tag
	$query = "SELECT * FROM members WHERE id=$memberID";
	$result = $link->query($query);

	if($result!==FALSE) {
		$row = $result->fetch_assoc();
		if($row["fName"]=="Master" && $row["lName"]=="Key") {
			$io+=3;
			echo "SPORKshop";
			echo "\n";
			if($io==4) {echo "open";}
			if($io==3) {
				$mQuery = "SELECT * FROM members";
				$mResult = $link->query($mQuery);
				if($mResult!==FALSE) {
					while($mRow = $mResults->fetch_assoc()) {
						$aQuery = "SELECT * FROM AttendanceData WHERE memberID=".$mRow["memberID"]." ORDER BY id DESC";							$aResult = $link->query($aQuery);
						if($aResult!==FALSE) {
							$aRow = $aResult->fetch_assoc();
							if($aRow["io"]==1) {
								$fQuery = "INSERT INTO AttendanceData (memberID,io) VALUES (".$mRow["memberID"].",0)";
								$link->query($fQuery);
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

	$aResult->free();
	$mResult->free();
	$result->free();
	$link->close();
?>
