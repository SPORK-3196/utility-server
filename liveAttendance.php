<!DOCTYPE html>
<?php

	include("connect.php");

	$link=Connection();
	$key = 0;

	if($_GET["s"]=="") {
		$aResult=mysql_query("SELECT * FROM `AttendanceData` ORDER BY `id` DESC",$link);
	} else {
		$r=mysql_query("SELECT * FROM `members` WHERE fName='".$_GET["s"]."'",$link);
		if($r!==FALSE) {
			$rRow = mysql_fetch_array($r);
			$key = $rRow["id"];
		}
		$aResult=mysql_query("SELECT * FROM `AttendanceData` WHERE memberID='".$key."' ORDER BY `entryDate` DESC",$link);
	}
?>

<html>
<meta http-equiv="refresh" content="1; URL=liveAttendance.php">
<head>
	<title>Attendance Log</title>
	<link rel="stylesheet" type="text/css" href="theme.css" />
	<link rel="stylesheet" type="text/css" href="header.css" />
</head>



<body>
   <div id="tablebody">
      <h1>Live Attendance</h1>

      <table class="data">
		<tr>
			<td>&nbsp;First Name&nbsp;</td>
			<td>&nbsp;Last Name&nbsp;</td>
		</tr>

         <?php
		  if($aResult!==FALSE) {
		     $aRow = mysql_fetch_array($aResult);
			$mResult=mysql_query("SELECT * FROM `members` WHERE id=$aRow[memberID]",$link);
			if($mResult!==FALSE) { $mRow = mysql_fetch_array($mResult); }
			if($aRow["io"]==1) {
				if($mRow["fName"] != "Master" && $mRow["lName"] != "Key") {
					printf("<tr><td> &nbsp;%s </td><td> &nbsp;%s&nbsp; </td>", $mRow["fName"], $mRow["lName"]);
				}
			}
		     mysql_free_result($result);
		     mysql_close();
		  }
         ?>

      </table>
   </div>

</body>
</html>
