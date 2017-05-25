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
		     while($aRow = mysql_fetch_array($aResult)) {
			$mResult=mysql_query("SELECT * FROM `members` WHERE id=$aRow[memberID]",$link);
			if($mResult!==FALSE) {
				$mRow = mysql_fetch_array($mResult);
			}
			if($aRow["io"]==0){$io="Out";}
			else if($aRow["io"]==1){$io="In";}
			else{$io="-";}
		        printf("<tr><td> &nbsp;%s </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td>",
		           $aRow["entryDate"], $mRow["fName"], $mRow["lName"], $io);
		     }
		     mysql_free_result($result);
		     mysql_close();
		  }
         ?>

      </table>
   </div>

</body>
</html>
