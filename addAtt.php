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
						$aQuery = "SELECT * FROM AttendanceData WHERE tagID=".$mRow["tagID"]." ORDER BY id DESC";							$aResult = mysql_query($aQuery,$link);
						if($aResult!==FALSE) {
							$aRow = mysql_fetch_array($aResult);
							if($aRow["io"]==1) {
								$fQuery = "INSERT INTO AttendanceData (tagID,io) VALUES (".$mRow["tagID"].",0)";
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
