<!DOCTYPE html>
<?php

	include("connect.php");

	$link=Connection();
	$key = 0;

	// If no given attendance parameter, show all
	if($_GET["s"]=="") {
		$aResult = $link->query("SELECT * FROM `AttendanceData` ORDER BY `id` DESC");
		//while ($row = $aResult->fetch_assoc()) {
		//	printf ("%s (%s)\n", $row["memberID"], $row["io"]);
		//}
	}
	// Otherwise, show attendence data for the given member
	else {
		$r = $link->query("SELECT * FROM `members` WHERE fName='".$_GET["s"]."'");
		if($r!==FALSE) {
			$rRow = $r->fetch_array();
			$key = $rRow["id"];
			$r->close();
		}
		$aResult = $link->query("SELECT * FROM `AttendanceData` WHERE memberID='".$key."' ORDER BY `entryDate` DESC");
	}
?>

<html>
<head>
	<title>Attendance Log</title>
	<link rel="stylesheet" type="text/css" href="theme.css" />
	<link rel="stylesheet" type="text/css" href="header.css" />
</head>



<body>
<?php include("header.php"); ?>

	<form action="attendance.php" method="get">
		Search: <input type="text" name="s">
	</form>

   <div id="tablebody">
      <h1>Attendance Log</h1>

      <table class="data">
		<tr>
			<td>&nbsp;Timestamp&nbsp;</td>
			<td>&nbsp;First Name&nbsp;</td>
			<td>&nbsp;Last Name&nbsp;</td>
			<td>&nbsp;In/Out&nbsp;</td>
		</tr>

         <?php
		if($aResult!==FALSE) {
			while($aRow = $aResult->fetch_assoc()) {

				$mResult = $link->query("SELECT * FROM members WHERE id=$aRow[memberID]");
				$mRow = $mResult->fetch_assoc();

				if($aRow["io"]==0){$io="Out";}
				else if($aRow["io"]==1){$io="In";}
				else{$io="-";}
				printf("<tr><td> &nbsp;%s </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td>",
					$aRow["entryDate"], $mRow["fName"], $mRow["lName"], $io);

				
				$mResult->free();
			}
			$aResult->free();
		}

		else {
		echo "No results found";
		}


		$link->close();
         ?>

      </table>
   </div>

</body>
</html>
